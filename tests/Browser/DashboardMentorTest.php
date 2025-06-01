<?php

namespace Tests\Browser;

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\CourseProgres;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DashboardMentorTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test mentor dapat melihat progress siswa di dashboard
     */
    public function testMentorCanViewStudentProgress()
    {
        $this->browse(function (Browser $browser) {
            $mentor = User::factory()->create([
                'role' => 'mentor',
                'name' => 'Test Mentor',
                'email' => 'mentor@test.com'
            ]);

            $course = Course::factory()->create([
                'title' => 'Laravel Basics',
                'created_by' => $mentor->id
            ]);

            $student = User::factory()->create([
                'role' => 'siswa',
                'name' => 'Test Student',
                'email' => 'student@test.com'
            ]);

            $enrollment = CourseEnrollment::create([
                'user_id' => $student->id,
                'course_id' => $course->id,
                'enrolled_at' => now()
            ]);

            CourseProgres::create([
                'enrollment_id' => $enrollment->id,
                'percentage_completed' => 65,
                'status' => 'Tidak Selesai',
                'last_accessed_at' => now()
            ]);

            $browser->loginAs($mentor)
                ->visit('/dashboard')
                ->waitForText('Tracking Kemajuan Siswa')
                ->assertSee('📊 Tracking Kemajuan Siswa')
                ->assertSee('Test Student')
                ->assertSee('Laravel Basics')
                ->screenshot('Progress-mentor');
        });
    }

    /**
     * Test mentor dapat melihat siswa yang mengalami kesulitan
     */
    public function testMentorCanIdentifyStrugglingSudents()
    {
        $this->browse(function (Browser $browser) {
            $mentor = User::factory()->create([
                'role' => 'mentor',
                'email' => 'mentor@test.com'
            ]);

            $course = Course::factory()->create([
                'title' => 'PHP Fundamentals',
                'created_by' => $mentor->id
            ]);

            $strugglingStudent = User::factory()->create([
                'role' => 'siswa',
                'name' => 'Struggling Student',
                'email' => 'struggling@test.com'
            ]);

            $enrollment = CourseEnrollment::create([
                'user_id' => $strugglingStudent->id,
                'course_id' => $course->id,
                'enrolled_at' => now()->subDays(30)
            ]);

            CourseProgres::create([
                'enrollment_id' => $enrollment->id,
                'percentage_completed' => 15,
                'status' => 'Tidak Selesai',
                'last_accessed_at' => now()->subDays(10)
            ]);

            $browser->loginAs($mentor)
                ->visit('/dashboard')
                ->waitForText('🔍 Analitik Area Kesulitan')
                ->assertSee('Struggling Student')
                ->assertSee('PHP Fundamentals')
                ->assertSee('Sangat lambat dalam menyelesaikan kursus');
        });
    }

    /**
     * Test mentor tidak dapat melihat progress siswa dari course yang bukan miliknya
     */
    public function testMentorCannotViewOtherMentorStudentProgress()
    {
        $this->browse(function (Browser $browser) {
            $mentor1 = User::factory()->create([
                'role' => 'mentor',
                'name' => 'Mentor One',
                'email' => 'mentor1@test.com'
            ]);

            $mentor2 = User::factory()->create([
                'role' => 'mentor',
                'name' => 'Mentor Two',
                'email' => 'mentor2@test.com'
            ]);

            $mentor1Course = Course::factory()->create([
                'title' => 'Mentor 1 Course',
                'created_by' => $mentor1->id
            ]);

            $mentor2Course = Course::factory()->create([
                'title' => 'Mentor 2 Course',
                'created_by' => $mentor2->id
            ]);

            $student = User::factory()->create([
                'role' => 'siswa',
                'name' => 'Test Student',
                'email' => 'student@test.com'
            ]);

            $enrollment = CourseEnrollment::create([
                'user_id' => $student->id,
                'course_id' => $mentor2Course->id,
                'enrolled_at' => now()
            ]);

            CourseProgres::create([
                'enrollment_id' => $enrollment->id,
                'percentage_completed' => 75,
                'status' => 'Tidak Selesai',
                'last_accessed_at' => now()
            ]);

            $browser->loginAs($mentor1)
                ->visit('/dashboard')
                ->waitForText('Tracking Kemajuan Siswa')
                ->assertDontSee('Mentor 3 Course')
                ->assertDontSee('Test Student 2')
                ->screenshot('mentor-cannot_see_other_mentor_students');
        });
    }

    /**
     * Test user dengan role siswa tidak dapat mengakses dashboard mentor
     */
    public function testStudentCannotAccessMentorDashboard()
    {
        $this->browse(function (Browser $browser) {
            $student = User::factory()->create([
                'role' => 'siswa',
                'name' => 'Test Student',
                'email' => 'student@test.com'
            ]);

            $browser->loginAs($student)
                ->visit('/dashboard')
                ->assertDontSee('📊 Tracking Kemajuan Siswa')
                ->assertDontSee('🔍 Analitik Area Kesulitan')
                ->screenshot('student-cannot_access_mentor_features');
        });
    }
}
