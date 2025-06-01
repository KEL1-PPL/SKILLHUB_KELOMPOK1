<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class MentorAnalyzeTest extends DuskTestCase
{
    use DatabaseMigrations;

    private $mentor;
    private $quiz;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mentor = User::factory()->create([
            'role' => 'mentor',
            'name' => 'Test Mentor',
            'email' => 'mentor@test.com'
        ]);

        $course = Course::factory()->create([
            'title' => 'Test Course',
            'created_by' => $this->mentor->id
        ]);

        $this->quiz = Quiz::create([
            'course_id' => $course->id,
            'title' => 'Test Quiz',
            'description' => 'Test Quiz Description',
            'time_limit' => 60,
            'max_attempts' => 3,
            'passing_score' => 70,
            'created_by' => $this->mentor->id,
            'is_active' => true
        ]);
    }

    /**
     * Test mentor can view quiz analyze page
     */
    public function testMentorCanViewQuizAnalyzePage()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->mentor)
                ->visit('/mentor/quizzes/' . $this->quiz->id . '/analyze')
                ->assertSee('Analisis Hasil Quiz')
                ->assertSee('Test Quiz')
                ->screenshot('quiz-analyze')
                ->assertSee('Test Course');
        });
    }
}
