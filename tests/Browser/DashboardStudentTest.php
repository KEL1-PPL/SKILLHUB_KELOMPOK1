<?php

namespace Tests\Browser;

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DashboardStudentTest extends DuskTestCase
{

    use DatabaseTruncation;

    /**
     * A Dusk test example.
     */
    public function test_user_can_see_their_enrolled_courses_and_progress()
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create();
            $course = Course::factory()->create();

            CourseEnrollment::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'enrolled_at' => now(),
            ]);

            $browser->loginAs($user)
                ->visitRoute('dashboard')
                ->assertSee($course->title)
                ->screenshot('user-course-progress-dashboard');
        });
    }

    /**
     * Test user tidak dapat melihat course yang tidak mereka enroll
     */
    public function test_user_cannot_see_courses_they_are_not_enrolled_in()
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create();
            $enrolledCourse = Course::factory()->create(['title' => 'My Enrolled Course']);
            $notEnrolledCourse = Course::factory()->create(['title' => 'Someone Else Course']);

            CourseEnrollment::create([
                'user_id' => $user->id,
                'course_id' => $enrolledCourse->id,
                'enrolled_at' => now(),
            ]);

            $browser->loginAs($user)
                ->visitRoute('dashboard')
                ->assertSee($enrolledCourse->title)
                ->assertDontSee($notEnrolledCourse->title)
                ->screenshot('user-cannot-see-other-courses');
        });
    }

    /**
     * Test unauthenticated user diarahkan ke login page
     */
    public function test_unauthenticated_user_redirected_to_login()
    {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('dashboard')
                ->assertRouteIs('login')
                ->screenshot('unauthenticated-redirected-to-login');
        });
    }
}
