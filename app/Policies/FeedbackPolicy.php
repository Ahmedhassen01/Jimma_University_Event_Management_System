<?php
// app/Policies/FeedbackPolicy.php
namespace App\Policies;

use App\Models\User;
use App\Models\Feedback;
use Illuminate\Auth\Access\HandlesAuthorization;

class FeedbackPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermission('view_feedback');
    }

    public function view(User $user, Feedback $feedback)
    {
        return $user->hasPermission('view_feedback');
    }

    public function create(User $user)
    {
        // Everyone can create feedback (guests too)
        return true;
    }

    public function update(User $user, Feedback $feedback)
    {
        return $user->hasPermission('update_feedback');
    }

    public function delete(User $user, Feedback $feedback)
    {
        return $user->hasPermission('delete_feedback');
    }

    public function respond(User $user, Feedback $feedback)
    {
        return $user->hasPermission('respond_feedback');
    }

    public function export(User $user)
    {
        return $user->hasPermission('export_feedback');
    }

    public function viewAnalytics(User $user)
    {
        return $user->hasPermission('view_feedback_analytics');
    }
}