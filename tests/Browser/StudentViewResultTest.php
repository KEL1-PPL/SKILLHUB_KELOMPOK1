<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\Models\User;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use App\Models\QuizQuestionOption;
use App\Models\QuizAnswer;
use App\Models\CourseEnrollment;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;

class StudentViewResultTest extends DuskTestCase
{
    use DatabaseMigrations;

    private $student;
    private $course;
    private $quiz;
    private $passedAttempt;
    private $failedAttempt;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupTestData();
    }

    private function setupTestData()
    {
        $this->student = User::create([
            'name' => 'Test Student',
            'email' => 'student@test.com',
            'password' => Hash::make('password'),
            'role' => 'siswa',
            'learning_path' => 'web-development'
        ]);

        $this->course = Course::create([
            'title' => 'Test Course',
            'description' => 'Test course description',
            'slug' => 'test-course',
            'created_by' => $this->student->id
        ]);

        CourseEnrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id
        ]);

        $this->quiz = Quiz::create([
            'course_id' => $this->course->id,
            'title' => 'Test Quiz',
            'description' => 'Test quiz description',
            'time_limit' => 60,
            'max_attempts' => 3,
            'passing_score' => 70,
            'is_active' => true,
            'created_by' => $this->student->id
        ]);

        $question1 = QuizQuestion::create([
            'quiz_id' => $this->quiz->id,
            'question' => 'What is Laravel?',
            'type' => 'multiple_choice',
            'points' => 10,
            'order' => 1
        ]);

        $question2 = QuizQuestion::create([
            'quiz_id' => $this->quiz->id,
            'question' => 'Explain MVC pattern',
            'type' => 'essay',
            'points' => 10,
            'order' => 2
        ]);

        QuizQuestionOption::create([
            'question_id' => $question1->id,
            'option_text' => 'A PHP Framework',
            'is_correct' => true
        ]);

        QuizQuestionOption::create([
            'question_id' => $question1->id,
            'option_text' => 'A JavaScript Framework',
            'is_correct' => false
        ]);

        $this->passedAttempt = QuizAttempt::create([
            'quiz_id' => $this->quiz->id,
            'user_id' => $this->student->id,
            'attempt_number' => 1,
            'score' => 85.00,
            'total_points' => 20,
            'earned_points' => 17,
            'status' => 'completed',
            'started_at' => now()->subMinutes(30),
            'submitted_at' => now()->subMinutes(15),
            'time_taken' => 900,
            'total_questions' => 2,
            'correct_answers' => 1,
            'is_passed' => true
        ]);

        $this->failedAttempt = QuizAttempt::create([
            'quiz_id' => $this->quiz->id,
            'user_id' => $this->student->id,
            'attempt_number' => 2,
            'score' => 50.00,
            'total_points' => 20,
            'earned_points' => 10,
            'status' => 'completed',
            'started_at' => now()->subMinutes(60),
            'submitted_at' => now()->subMinutes(45),
            'time_taken' => 900,
            'total_questions' => 2,
            'correct_answers' => 1,
            'is_passed' => false
        ]);

        QuizAnswer::create([
            'attempt_id' => $this->passedAttempt->id,
            'question_id' => $question1->id,
            'selected_option_id' => QuizQuestionOption::where('question_id', $question1->id)->where('is_correct', true)->first()->id,
            'is_correct' => true,
            'points_earned' => 10
        ]);

        QuizAnswer::create([
            'attempt_id' => $this->passedAttempt->id,
            'question_id' => $question2->id,
            'essay_answer' => 'MVC stands for Model-View-Controller pattern',
            'is_correct' => false,
            'points_earned' => 7
        ]);

        QuizAnswer::create([
            'attempt_id' => $this->failedAttempt->id,
            'question_id' => $question1->id,
            'selected_option_id' => QuizQuestionOption::where('question_id', $question1->id)->where('is_correct', false)->first()->id,
            'is_correct' => false,
            'points_earned' => 0
        ]);

        QuizAnswer::create([
            'attempt_id' => $this->failedAttempt->id,
            'question_id' => $question2->id,
            'essay_answer' => 'I dont know',
            'is_correct' => true,
            'points_earned' => 10
        ]);
    }

    /**
     * Test melihat hasil quiz yang lulus
     * Kriteria 1: Melihat hasil nilai
     */
    public function test_student_can_view_passed_quiz_result()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->student)
                ->visit("/student/quizzes/{$this->quiz->id}/attempts/{$this->passedAttempt->id}/result")
                ->waitForText('Hasil Quiz')
                ->assertSee('Hasil Quiz: ' . $this->quiz->title)
                ->assertSee('Percobaan ke-1 dari 3')
                ->assertSee('LULUS')
                ->assertSee('85.0%')
                ->assertSee('1')
                ->assertSee('1')
                ->assertSee('2')
                ->assertSee('70%')
                ->assertSee('Selamat!')
                ->assertSee('Anda telah berhasil lulus quiz ini dengan skor 85.0%')
                ->assertSee('Review Jawaban')
                ->assertSee('What is Laravel?')
                ->assertSee('A PHP Framework')
                ->assertSee('Benar')
                ->assertSee('Explain MVC pattern')
                ->assertSee('MVC stands for Model-View-Controller pattern')
                ->assertSee('Informasi Quiz')
                ->assertSee($this->course->title)
                ->assertSee('1/3')
                ->assertSee('Lulus')


                ->assertSee('Cetak Sertifikat');
        });
    }

    /**
     * Test melihat hasil quiz yang tidak lulus
     * Kriteria 1: Melihat hasil nilai
     */
    public function test_student_can_view_failed_quiz_result()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->student)
                ->visit("/student/quizzes/{$this->quiz->id}/attempts/{$this->failedAttempt->id}/result")
                ->waitForText('Hasil Quiz')
                ->assertSee('Hasil Quiz: ' . $this->quiz->title)
                ->assertSee('Percobaan ke-2 dari 3')
                ->assertSee('BELUM LULUS')
                ->assertSee('50.0%')
                ->assertSee('Belum Lulus!')
                ->assertSee('Anda memerlukan minimal 70% untuk lulus')
                ->assertSee('Anda masih memiliki 1 percobaan lagi')
                ->assertSee('Coba Lagi')
                ->assertDontSee('Cetak Sertifikat');
        });
    }

    /**
     * Test navigasi dari hasil quiz
     * Kriteria 1: Melihat hasil nilai - Navigasi
     */
    public function test_student_can_navigate_from_quiz_result()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->student)
                ->visit("/student/quizzes/{$this->quiz->id}/attempts/{$this->passedAttempt->id}/result")
                ->waitForText('Hasil Quiz')
                ->clickLink('Kembali ke Quiz')
                ->waitForLocation("/student/quizzes/{$this->quiz->id}")
                ->assertPathIs("/student/quizzes/{$this->quiz->id}")
                ->back()
                ->waitForText('Hasil Quiz');
        });
    }

    /**
     * Test cetak sertifikat untuk quiz yang lulus
     * Kriteria 2: Cetak sertifikat
     */
    public function test_student_can_print_certificate_for_passed_quiz()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->student)
                ->visit("/student/quizzes/{$this->quiz->id}/attempts/{$this->passedAttempt->id}/result")
                ->waitForText('Hasil Quiz')
                ->assertSee('Cetak Sertifikat')
                ->press('Cetak Sertifikat')
                ->waitFor('#certificateModal')
                ->assertVisible('#certificateModal')
                ->within('#certificateModal', function ($modal) {
                    $modal->assertSee('SERTIFIKAT KELULUSAN')
                        ->assertSee('Diberikan kepada:')
                        ->assertSee($this->student->name)
                        ->assertSee('Telah menyelesaikan kuis:')
                        ->assertSee($this->quiz->title)
                        ->assertSee('Dengan skor: 85.0%')
                        ->assertSee('Print Sertifikat')
                        ->assertSee('Tutup');
                })
                ->press('Tutup')
                ->waitUntilMissing('#certificateModal');
        });
    }

    /**
     * Test sertifikat tidak tersedia untuk quiz yang tidak lulus
     * Kriteria 2: Cetak sertifikat - Validasi
     */
    public function test_certificate_not_available_for_failed_quiz()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->student)
                ->visit("/student/quizzes/{$this->quiz->id}/attempts/{$this->failedAttempt->id}/result")
                ->waitForText('Hasil Quiz')
                ->assertMissing('#certificateModal');

        });
    }

    /**
     * Test konfetti animation untuk quiz yang lulus
     * Kriteria 1: Melihat hasil nilai - User Experience
     */
    public function test_confetti_animation_for_passed_quiz()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->student)
                ->visit("/student/quizzes/{$this->quiz->id}/attempts/{$this->passedAttempt->id}/result")
                ->waitForText('Hasil Quiz')
                ->pause(2000)
                ->assertSee('LULUS')
                ->assertSee('Selamat!');
        });
    }

    /**
     * Test validasi akses hasil quiz
     * Kriteria 1: Melihat hasil nilai - Security
     */
    public function test_quiz_result_access_validation()
    {
        $otherStudent = User::create([
            'name' => 'Other Student',
            'email' => 'other@test.com',
            'password' => Hash::make('password'),
            'role' => 'siswa',
            'learning_path' => 'web-development'
        ]);

        $this->browse(function (Browser $browser) use ($otherStudent) {
            $browser->loginAs($otherStudent)
                ->visit("/student/quizzes/{$this->quiz->id}/attempts/{$this->passedAttempt->id}/result")
                ->assertSee('403');
        });
    }
}
