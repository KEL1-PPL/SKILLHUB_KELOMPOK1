<?php

namespace App\Policies;

use App\Models\Quiz;
use App\Models\User;

class QuizPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'mentor';
    }

    public function view(User $user, Quiz $quiz): bool
    {
        return $user->role === 'mentor' && $quiz->created_by === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'mentor';
    }

    public function update(User $user, Quiz $quiz): bool
    {
        return $user->role === 'mentor' && $quiz->created_by === $user->id;
    }

    public function delete(User $user, Quiz $quiz): bool
    {
        return $user->role === 'mentor' && $quiz->created_by === $user->id;
    }

    public function takeQuiz(User $user, Quiz $quiz): bool
    {
        if ($user->role !== 'siswa') {
            return false;
        }

        $isEnrolled = $user->courseEnrollments()
            ->where('course_id', $quiz->course_id)
            ->exists();

        if (!$isEnrolled) {
            return false;
        }

        if ($quiz->material_id) {
            $materialCompleted = $user->materialCompletions()
                ->where('material_id', $quiz->material_id)
                ->where('is_completed', true)
                ->exists();

            return $materialCompleted;
        }

        return true;
    }
}
