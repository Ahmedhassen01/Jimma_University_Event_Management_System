<?php
// database/seeders/EventSpeakerSeeder.php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Speaker;
use Illuminate\Database\Seeder;

class EventSpeakerSeeder extends Seeder
{
    public function run(): void
    {
        $events = Event::all();
        $speakers = Speaker::all();
        
        foreach ($events as $event) {
            // Assign 1-3 random speakers to each event
            $numberOfSpeakers = rand(1, 3);
            $selectedSpeakers = $speakers->random(min($numberOfSpeakers, $speakers->count()));
            
            foreach ($selectedSpeakers as $index => $speaker) {
                $isKeynote = $index === 0; // First speaker is keynote
                $isModerator = $index === 1 && rand(0, 1); // Second speaker might be moderator
                $isPanelist = !$isKeynote && !$isModerator && rand(0, 1);
                
                $event->speakers()->attach($speaker->id, [
                    'session_title' => $isKeynote ? 'Keynote Address: ' . $speaker->expertise[0] ?? 'Future of Technology' : 'Session with ' . $speaker->name,
                    'session_time' => $event->start_date->addHours($index * 2),
                    'session_duration' => rand(30, 90),
                    'session_description' => 'An insightful session by ' . $speaker->name . ' about ' . ($speaker->expertise[0] ?? 'their expertise'),
                    'order' => $index,
                    'is_keynote' => $isKeynote,
                    'is_moderator' => $isModerator,
                    'is_panelist' => $isPanelist,
                ]);
            }
        }
    }
}