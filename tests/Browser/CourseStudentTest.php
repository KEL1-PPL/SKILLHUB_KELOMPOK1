<?php

namespace Tests\Browser;

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CourseStudentTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function test_user_can_see_their_enrolled_courses()
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
                ->visitRoute('features.course.index')
                ->assertSee($course->title)
                ->screenshot('user-course_enrolled');
        });
    }

    /** @test */
    public function test_user_cannot_see_courses_they_are_not_enrolled_in()
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create();
            $enrolledCourse = Course::factory()->create(['title' => 'Enrolled Course']);

            CourseEnrollment::create([
                'user_id' => $user->id,
                'course_id' => $enrolledCourse->id,
                'enrolled_at' => now(),
            ]);

            $browser->loginAs($user)
                ->visitRoute('features.course.index')
                ->assertSee($enrolledCourse->title)
                ->assertDontSee('not enrolled course')
                ->screenshot('user-not_enrolled_courses_hidden');
        });
    }

    /** @test */
    public function test_unauthenticated_user_is_redirected_to_login()
    {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('features.course.index')
                ->assertRouteIs('login')
                ->screenshot('unauthenticated-redirected_to_login');
        });
    }
}
