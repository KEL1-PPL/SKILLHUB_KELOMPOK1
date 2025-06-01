<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Atribut yang dapat diisi (fillable) saat menggunakan mass assignment.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'learning_path'
    ];

    /**
     * Atribut yang harus disembunyikan saat serialisasi model.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Atribut yang perlu dikonversi ke tipe data lain.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function getUserRegistrationsPerMonth()
    {
        $year = Carbon::now()->year;

        $allMonths = collect(range(1, 12))->mapWithKeys(fn($m) => [$m => ['siswa' => 0, 'mentor' => 0]]);


        $registrations = DB::table('users')
            ->selectRaw('MONTH(created_at) as bulan, role, COUNT(*) as total')
            ->whereYear('created_at', $year)
            ->whereIn('role', ['siswa', 'mentor'])
            ->groupBy('bulan', 'role')
            ->get();


        $allMonths = $allMonths->map(function ($values, $month) use ($registrations) {
            $matchingData = $registrations->where('bulan', $month);

            return [
                'siswa' => (int) optional($matchingData->firstWhere('role', 'siswa'))->total ?? 0,
                'mentor' => (int) optional($matchingData->firstWhere('role', 'mentor'))->total ?? 0,
            ];
        });

        return $allMonths;
    }

    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    public function coursesCreated()
    {
        return $this->hasMany(Course::class, 'created_by');
    }

    public function completionHistory()
    {
        return $this->hasMany(CourseCompletionHistory::class);
    }

    public function analytics()
    {
        return $this->hasMany(Analytic::class, 'student_id');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isMentor()
    {
        return $this->role === 'mentor';
    }

    public function isEnrolledInCourse($courseId)
    {
        if ($this->role !== 'siswa') {
            return false;
        }

        return $this->enrollments()->where('course_id', $courseId)->exists();
    }

    public function createdQuizzes(): HasMany
    {
        return $this->hasMany(Quiz::class, 'created_by');
    }

    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function materialCompletions(): HasMany
    {
        return $this->hasMany(MaterialCompletion::class);
    }

    public function courseEnrollments(): HasMany
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function wishlistCourses()
    {
        return $this->belongsToMany(Course::class, 'wishlists');
    }
}
