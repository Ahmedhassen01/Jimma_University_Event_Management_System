<?php
// app/Http/Controllers/EventSpeakerController.php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Speaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventSpeakerController extends Controller
{
    /**
     * Show the form for managing speakers of an event.
     */
    public function manage(Event $event)
    {
        $event->load('speakers');
        $availableSpeakers = Speaker::active()->orderBy('name')->get();
        
        return view('admin.events.speakers', compact('event', 'availableSpeakers'));
    }

    /**
     * Assign speakers to an event.
     */
    public function assign(Request $request, Event $event)
    {
        $validator = Validator::make($request->all(), [
            'speakers' => 'required|array|min:1',
            'speakers.*.speaker_id' => 'required|exists:speakers,id',
            'speakers.*.session_title' => 'nullable|string|max:255',
            'speakers.*.session_time' => 'nullable|date',
            'speakers.*.session_duration' => 'nullable|integer|min:1|max:480',
            'speakers.*.session_description' => 'nullable|string|max:1000',
            'speakers.*.order' => 'nullable|integer|min:0',
            'speakers.*.is_keynote' => 'nullable|boolean',
            'speakers.*.is_moderator' => 'nullable|boolean',
            'speakers.*.is_panelist' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $nextOrder = (int) $event->speakers()->max('event_speaker.order') + 1;
        $syncData = [];

        foreach ($request->input('speakers', []) as $speaker) {
            $speakerId = isset($speaker['speaker_id']) ? (int) $speaker['speaker_id'] : null;
            if (!$speakerId) {
                continue;
            }

            $syncData[$speakerId] = [
                'session_title' => $speaker['session_title'] ?? null,
                'session_time' => $speaker['session_time'] ?? null,
                'session_duration' => isset($speaker['session_duration']) && $speaker['session_duration'] !== ''
                    ? (int) $speaker['session_duration']
                    : null,
                'session_description' => $speaker['session_description'] ?? null,
                'order' => isset($speaker['order']) && $speaker['order'] !== ''
                    ? (int) $speaker['order']
                    : $nextOrder++,
                'is_keynote' => !empty($speaker['is_keynote']),
                'is_moderator' => !empty($speaker['is_moderator']),
                'is_panelist' => !empty($speaker['is_panelist']),
            ];
        }

        if (!empty($syncData)) {
            // Add/update selected speakers while keeping already assigned ones.
            $event->speakers()->syncWithoutDetaching($syncData);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Speakers assigned successfully!',
            ]);
        }

        return redirect()->route('admin.events.speakers.manage', $event)
            ->with('success', 'Speakers assigned successfully!');
    }

    /**
     * Remove a speaker from an event.
     */
    public function remove(Event $event, Speaker $speaker)
    {
        $event->speakers()->detach($speaker->id);

        return redirect()->back()
            ->with('success', 'Speaker removed from event successfully.');
    }

    /**
     * Update speaker details for an event.
     */
    public function updateSpeakerDetails(Request $request, Event $event, Speaker $speaker)
    {
        $validator = Validator::make($request->all(), [
            'session_title' => 'nullable|string|max:255',
            'session_time' => 'nullable|date',
            'session_duration' => 'nullable|integer|min:1|max:480',
            'session_description' => 'nullable|string|max:1000',
            'is_keynote' => 'nullable|boolean',
            'is_moderator' => 'nullable|boolean',
            'is_panelist' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            if (!$request->ajax() && !$request->wantsJson()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            return response()->json(['errors' => $validator->errors()], 422);
        }

        $event->speakers()->updateExistingPivot($speaker->id, [
            'session_title' => $request->session_title,
            'session_time' => $request->session_time,
            'session_duration' => $request->session_duration,
            'session_description' => $request->session_description,
            'is_keynote' => $request->boolean('is_keynote', false),
            'is_moderator' => $request->boolean('is_moderator', false),
            'is_panelist' => $request->boolean('is_panelist', false),
            'updated_at' => now(),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Speaker details updated successfully.'
            ]);
        }

        return redirect()->route('admin.events.speakers.manage', $event)
            ->with('success', 'Speaker details updated successfully.');
    }

    /**
     * Reorder speakers.
     */
    public function reorder(Request $request, Event $event)
    {
        $validator = Validator::make($request->all(), [
            'speakers' => 'required|array',
            'speakers.*.id' => 'required|exists:speakers,id',
            'speakers.*.order' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        foreach ($request->speakers as $speakerData) {
            $event->speakers()->updateExistingPivot($speakerData['id'], [
                'order' => $speakerData['order']
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Speakers reordered successfully.'
        ]);
    }

    /**
     * Get available speakers for AJAX.
     */
    public function getAvailableSpeakers(Request $request)
    {
        $search = $request->get('search', '');
        $excludeIds = $request->get('exclude_ids', []);
        
        $query = Speaker::active();
        
        if (!empty($search)) {
            $query->search($search);
        }
        
        if (!empty($excludeIds)) {
            $query->whereNotIn('id', $excludeIds);
        }
        
        $speakers = $query->orderBy('name')
                         ->limit(20)
                         ->get(['id', 'name', 'title', 'position', 'organization', 'photo']);
        
        return response()->json($speakers);
    }

    /**
     * Get assigned speakers for an event.
     */
    public function getAssignedSpeakers(Event $event)
    {
        $speakers = $event->speakers()->get()->map(function ($speaker) {
            return [
                'id' => $speaker->id,
                'name' => $speaker->name,
                'title' => $speaker->title,
                'position' => $speaker->position,
                'organization' => $speaker->organization,
                'photo' => $speaker->photo_url,
                'pivot' => $speaker->pivot,
            ];
        });
        
        return response()->json($speakers);
    }
}
