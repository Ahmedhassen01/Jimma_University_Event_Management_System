<?php
// app/Services/FeedbackService.php
namespace App\Services;

use App\Mail\FeedbackResponseMail;
use App\Models\Feedback;
use App\Models\FeedbackResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class FeedbackService
{
    public function submitFeedback(array $data)
    {
        return Feedback::create([
            'event_id' => $data['event_id'] ?? null,
            'user_id' => $data['user_id'] ?? null,
            'name' => $data['name'] ?? null,
            'email' => $data['email'] ?? null,
            'type' => $data['type'],
            'subject' => $data['subject'] ?? null,
            'message' => $data['message'],
            'rating' => $data['rating'] ?? null,
            'allow_contact' => $data['allow_contact'] ?? false,
            'is_public' => $data['is_public'] ?? false,
            'status' => 'pending',
        ]);
    }

    public function sendResponse(Feedback $feedback, string $message, bool $sendEmail = false)
    {
        $response = FeedbackResponse::create([
            'feedback_id' => $feedback->id,
            'responded_by' => Auth::id(),
            'message' => $message,
            'send_email' => $sendEmail,
        ]);

        if ($sendEmail) {
            $recipientEmail = $feedback->email ?: $feedback->user?->email;

            if ($recipientEmail) {
                try {
                    $response->loadMissing('responder');
                    Mail::to($recipientEmail)->send(new FeedbackResponseMail($feedback, $response));
                    $response->markEmailAsSent();
                } catch (\Throwable $e) {
                    Log::error('Failed to send feedback response email.', [
                        'feedback_id' => $feedback->id,
                        'response_id' => $response->id,
                        'recipient_email' => $recipientEmail,
                        'error' => $e->getMessage(),
                    ]);
                }
            } else {
                Log::warning('Feedback response email requested but no recipient email found.', [
                    'feedback_id' => $feedback->id,
                    'response_id' => $response->id,
                ]);
            }
        }

        // Update feedback status
        $feedback->update(['status' => 'reviewed', 'reviewed_at' => now()]);

        return $response;
    }

    public function getAnalytics(array $filters = [])
    {
        $query = Feedback::query();

        // Apply filters if provided
        if (!empty($filters['start_date'])) {
            $query->where('created_at', '>=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $query->where('created_at', '<=', $filters['end_date']);
        }
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        // Calculate analytics
        $total = $query->count();
        $withRating = $query->whereNotNull('rating')->count();
        $averageRating = $query->whereNotNull('rating')->avg('rating');

        // Get monthly trend for last 6 months
        $monthlyTrend = Feedback::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count")
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'total' => $total,
            'with_rating' => $withRating,
            'average_rating' => $averageRating ? round($averageRating, 2) : null,
            'monthly_trend' => $monthlyTrend,
        ];
    }

    public function exportFeedback(array $filters = [], string $format = 'excel')
    {
        // For now, just return to index page
        return redirect()->route('feedback.index');
    }
}
