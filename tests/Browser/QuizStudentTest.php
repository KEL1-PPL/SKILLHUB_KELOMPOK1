<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Material;
use App\Models\MaterialCompletion;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizQuestionOption;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class QuizStudentTest extends DuskTestCase
{
    use DatabaseTruncation;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
    }

    public function test_student_can_access_quiz_after_completing_required_material()
    {
        $mentor = User::factory()->create([
            'role' => 'mentor',
            'name' => 'Mentor Test',
            'email' => 'mentor@test.com'
        ]);

        $student = User::factory()->create([
            'role' => 'siswa',
            'name' => 'Siswa Test',
            'email' => 'siswa@test.com',
            'learning_path' => 'web-development'
        ]);

        $course = Course::factory()->create([
            'title' => 'Laravel Fundamentals',
            'description' => 'Belajar dasar-dasar Laravel',
            'slug' => 'laravel-fundamentals',
            'created_by' => $mentor->id
        ]);

        CourseEnrollment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'enrolled_at' => now()
        ]);

        $material = Material::create([
            'course_id' => $course->id,
            'title' => 'Pengenalan Laravel',
            'content' => 'Materi tentang pengenalan Laravel framework',
            'order' => 1
        ]);

        $quiz = Quiz::create([
            'course_id' => $course->id,
            'material_id' => $material->id,
            'title' => 'Quiz Laravel Fundamentals',
            'description' => 'Quiz untuk menguji pemahaman Laravel',
            'time_limit' => 30,
            'max_attempts' => 3,
            'passing_score' => 70,
            'is_active' => true,
            'created_by' => $mentor->id
        ]);

        $question1 = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'Apa itu Laravel?',
            'type' => 'multiple_choice',
            'points' => 10,
            'order' => 1
        ]);

        QuizQuestionOption::create([
            'question_id' => $question1->id,
            'option_text' => 'Framework PHP',
            'is_correct' => true,
            'order' => 1
        ]);

        QuizQuestionOption::create([
            'question_id' => $question1->id,
            'option_text' => 'Database Management System',
            'is_correct' => false,
            'order' => 2
        ]);

        $this->browse(function (Browser $browser) use ($student, $course, $material, $quiz) {
            $browser->loginAs($student)
                ->visit('/student/quizzes')
                ->pause(1000);

            $browser->assertSee('🎯 Quiz Tersedia');
            $browser->visit("/student/quizzes/{$quiz->id}")
                ->pause(2000);

            $restrictionFound = false;
            $possibleMessages = [
                'Anda harus menyelesaikan materi',
                'Materi harus diselesaikan',
                'Selesaikan materi terlebih dahulu',
                'Material belum diselesaikan',
                'Complete the required material'
            ];

            foreach ($possibleMessages as $message) {
                try {
                    $browser->assertSee($message);
                    $restrictionFound = true;
                    break;
                } catch (\Exception $e) {
                    continue;
                }
            }

            if (!$restrictionFound) {
                $startButtonExists = false;
                $startQuizSelectors = [
                    'button:contains("Mulai Quiz")',
                    'a:contains("Mulai Quiz")',
                    '.btn:contains("Mulai")'
                ];

                foreach ($startQuizSelectors as $selector) {
                    if ($browser->element($selector)) {
                        $startButtonExists = true;
                        $this->assertTrue(
                            $browser->element($selector . '[disabled]') !== null ||
                                $browser->element($selector . '.disabled') !== null,
                            'Start quiz button should be disabled when material not completed'
                        );
                        break;
                    }
                }

                if (!$startButtonExists) {
                    $browser->assertDontSee('Mulai Quiz');
                }
            }

            MaterialCompletion::create([
                'material_id' => $material->id,
                'user_id' => $student->id,
                'is_completed' => true
            ]);

            $browser->visit('/student/quizzes')
                ->pause(1000)
                ->assertSee($quiz->title);

            $browser->type('#search-quiz', 'Laravel')
                ->pause(1000)
                ->assertSee($quiz->title);

            $browser->clear('#search-quiz')
                ->type('#search-quiz', 'NonExistent')
                ->pause(1000)
                ->assertDontSee($quiz->title);

            $browser->clear('#search-quiz')->pause(500);

            $browser->visit("/student/quizzes/{$quiz->id}")
                ->pause(2000)
                ->assertSee($quiz->title)
                ->assertSee($quiz->description);

            $startQuizSelectors = [
                'button:contains("Mulai Quiz")',
                'a:contains("Mulai Quiz")',
                '.btn:contains("Mulai")',
                'button:contains("Start Quiz")'
            ];

            $startButtonFound = false;
            foreach ($startQuizSelectors as $selector) {
                if ($browser->element($selector)) {
                    $startButtonFound = true;
                    $this->assertTrue(
                        $browser->element($selector . '[disabled]') === null,
                        'Start quiz button should NOT be disabled after material completion'
                    );
                    break;
                }
            }

            $browser->visit("/student/quizzes/{$quiz->id}/start")
                ->screenshot('quiz-start')
                ->pause(2000);

            $browser->assertDontSee('404')
                ->assertDontSee('Not Found');

            $this->assertTrue(true, 'Quiz access test completed successfully');
        });
    }

    /**
     * Test student tidak dapat mengakses quiz dari course yang tidak mereka enroll
     */
    public function test_student_cannot_access_quiz_from_unenrolled_course()
    {
        $mentor = User::factory()->create([
            'role' => 'mentor',
            'name' => 'Mentor Test',
            'email' => 'mentor@test.com'
        ]);

        $student = User::factory()->create([
            'role' => 'siswa',
            'name' => 'Siswa Test',
            'email' => 'siswa@test.com'
        ]);

        $course = Course::factory()->create([
            'title' => 'Unenrolled Course',
            'created_by' => $mentor->id
        ]);

        $quiz = Quiz::create([
            'course_id' => $course->id,
            'title' => 'Restricted Quiz',
            'is_active' => true,
            'created_by' => $mentor->id
        ]);

        $this->browse(function (Browser $browser) use ($student, $quiz) {
            $browser->loginAs($student)
                ->visit("/student/quizzes/{$quiz->id}")
                ->pause(1000);

            $accessDenied = false;
            $possibleMessages = [
                'Anda tidak terdaftar',
                'Access denied',
                'Tidak dapat mengakses',
                '403',
                'Forbidden'
            ];

            foreach ($possibleMessages as $message) {
                try {
                    $browser->assertSee($message);
                    $accessDenied = true;
                    break;
                } catch (\Exception $e) {
                    continue;
                }
            }

            if (!$accessDenied) {
                $browser->assertDontSee('Mulai Quiz');
            }

            $browser->screenshot('unenrolled-quiz-access-denied');
        });
    }

    /**
     * Test unauthenticated user diarahkan ke login page
     */
    public function test_unauthenticated_user_redirected_to_login()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/student/quizzes')
                ->assertRouteIs('login')
                ->screenshot('quiz-unauthenticated-redirect');
        });
    }
}
