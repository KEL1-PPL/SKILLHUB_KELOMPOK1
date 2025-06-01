<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Material;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class MentorCRUDQuizTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $mentor;
    protected $course;
    protected $material;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mentor = $this->createUser([
            'role' => 'mentor',
            'learning_path' => 'web-development'
        ]);

        $this->course = $this->createCourse([
            'created_by' => $this->mentor->id,
            'title' => 'Test Course',
            'slug' => 'test-course'
        ]);

        $this->material = Material::create([
            'course_id' => $this->course->id,
            'title' => 'Test Material',
            'content' => 'Test material content for testing purposes'
        ]);
    }

    /**
     * Create a user with default or custom attributes
     */
    private function createUser(array $attributes = []): User
    {
        $defaults = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'role' => 'student',
            'learning_path' => 'web-development',
            'remember_token' => Str::random(10),
        ];

        return User::create(array_merge($defaults, $attributes));
    }

    /**
     * Create a course with default or custom attributes
     */
    private function createCourse(array $attributes = []): Course
    {
        $defaults = [
            'title' => $this->faker->sentence(3),
            'slug' => $this->faker->slug,
            'description' => $this->faker->paragraph,
            'learning_path' => 'web-development',
            'difficulty_level' => 'beginner',
            'estimated_duration' => $this->faker->numberBetween(1, 10),
            'is_published' => true,
            'created_by' => 1,
        ];

        return Course::create(array_merge($defaults, $attributes));
    }

    /**
     * Create a quiz with default or custom attributes
     */
    private function createQuiz(array $attributes = []): Quiz
    {
        $defaults = [
            'course_id' => $this->course->id,
            'material_id' => $this->material->id ?? null,
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph,
            'time_limit' => $this->faker->numberBetween(30, 120),
            'max_attempts' => $this->faker->numberBetween(1, 5),
            'passing_score' => $this->faker->numberBetween(60, 90),
            'is_active' => true,
            'created_by' => $this->mentor->id,
        ];

        return Quiz::create(array_merge($defaults, $attributes));
    }

    /** @test */
    public function test_mentor_can_view_quiz_index()
    {
        $this->createQuiz(['course_id' => $this->course->id, 'created_by' => $this->mentor->id]);
        $this->createQuiz(['course_id' => $this->course->id, 'created_by' => $this->mentor->id]);
        $this->createQuiz(['course_id' => $this->course->id, 'created_by' => $this->mentor->id]);

        $response = $this->actingAs($this->mentor)
            ->get(route('mentor.quizzes.index'));

        $response->assertStatus(200);
        $response->assertViewIs('Quiz.Mentor.quizzes.index');
        $response->assertViewHas('quizzes');
        $response->assertViewHas('title', 'index quiz');
    }

    /** @test */
    public function test_mentor_can_view_create_quiz_form()
    {
        $response = $this->actingAs($this->mentor)
            ->get(route('mentor.quizzes.create'));

        $response->assertStatus(200);
        $response->assertViewIs('Quiz.Mentor.quizzes.create');
        $response->assertViewHas('courses');
        $response->assertViewHas('title', 'tambah quiz');
    }

    /** @test */
    public function test_mentor_can_create_quiz_successfully()
    {
        $quizData = [
            'course_id' => $this->course->id,
            'material_id' => $this->material->id,
            'title' => 'Test Quiz',
            'description' => 'This is a test quiz description',
            'time_limit' => 60,
            'max_attempts' => 3,
            'passing_score' => 70
        ];

        $response = $this->withoutMiddleware()
            ->actingAs($this->mentor)
            ->post(route('mentor.quizzes.store'), $quizData);

        $this->assertDatabaseHas('quizzes', [
            'title' => 'Test Quiz',
            'course_id' => $this->course->id,
            'material_id' => $this->material->id,
            'created_by' => $this->mentor->id,
            'max_attempts' => 3,
            'passing_score' => 70
        ]);

        $quiz = Quiz::where('title', 'Test Quiz')->first();
        $response->assertRedirect(route('mentor.quizzes.show', $quiz));
        $response->assertSessionHas('success', 'Quiz berhasil dibuat!');
    }

    /** @test */
    public function test_create_quiz_validation_fails_with_invalid_data()
    {
        $invalidData = [
            'course_id' => '',
            'title' => '',
            'max_attempts' => 0,
            'passing_score' => 150 
        ];

        $response = $this->withoutMiddleware()
            ->actingAs($this->mentor)
            ->post(route('mentor.quizzes.store'), $invalidData);


        $response->assertSessionHasErrors([
            'course_id',
            'title',
            'max_attempts',
            'passing_score'
        ]);
    }

    /** @test */
    public function test_mentor_can_view_specific_quiz()
    {
        $quiz = $this->createQuiz([
            'course_id' => $this->course->id,
            'created_by' => $this->mentor->id,
            'title' => 'Viewable Quiz'
        ]);

        $response = $this->actingAs($this->mentor)
            ->get(route('mentor.quizzes.show', $quiz));

        $response->assertStatus(200);
        $response->assertViewIs('Quiz.Mentor.quizzes.show');
        $response->assertViewHas('quiz');
        $response->assertViewHas('title', 'show quiz');
        $response->assertSee('Viewable Quiz');
    }

    /** @test */
    public function test_mentor_can_view_edit_quiz_form()
    {
        $quiz = $this->createQuiz([
            'course_id' => $this->course->id,
            'material_id' => $this->material->id,
            'created_by' => $this->mentor->id
        ]);

        $response = $this->actingAs($this->mentor)
            ->get(route('mentor.quizzes.edit', $quiz));

        $response->assertStatus(200);
        $response->assertViewIs('Quiz.Mentor.quizzes.edit');
        $response->assertViewHas('quiz');
        $response->assertViewHas('courses');
        $response->assertViewHas('materials');
        $response->assertViewHas('title', 'edit quiz');
    }



    /** @test */
    public function test_mentor_can_update_quiz_successfully()
    {
        $quiz = Quiz::create([
            'title' => 'Original Quiz Title',
            'material_id' => $this->material->id,
            'course_id' => $this->material->course_id,
            'created_by' => $this->mentor->id,
            'time_limit' => 60,
            'max_attempts' => 3,
            'passing_score' => 70,
            'is_active' => false,
        ]);


        $updateData = [
            'title' => 'Updated Quiz Title',
            'material_id' => $this->material->id,
            'course_id' => $this->material->course_id,
            'time_limit' => 90,
            'max_attempts' => 5,
            'passing_score' => 80,
            'is_active' => true,
        ];

        $this->withSession(['_token' => 'test-csrf-token']);
        $response = $this->actingAs($this->mentor)
            ->withHeader('X-CSRF-TOKEN', 'test-csrf-token')
            ->put(route('mentor.quizzes.update', $quiz), $updateData);


        $response->assertRedirect(route('mentor.quizzes.show', $quiz));
        $this->assertDatabaseHas('quizzes', [
            'id' => $quiz->id,
            'title' => 'Updated Quiz Title',
            'material_id' => $this->material->id,
            'time_limit' => 90,
            'max_attempts' => 5,
            'passing_score' => 80,
            'is_active' => true,
        ]);
    }


    /** @test */
    public function test_mentor_can_delete_quiz_successfully()
    {
        $quiz = $this->createQuiz([
            'course_id' => $this->course->id,
            'created_by' => $this->mentor->id,
            'title' => 'Quiz to Delete'
        ]);

        $this->withSession(['_token' => 'test-csrf-token']);
        $response = $this->withoutMiddleware(\Illuminate\Auth\Middleware\Authorize::class)
        ->actingAs($this->mentor)
        ->withHeader('X-CSRF-TOKEN', 'test-csrf-token')
        ->delete(route('mentor.quizzes.destroy', $quiz));

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Quiz berhasil dihapus!');
    }

    /** @test */
    public function test_mentor_cannot_access_other_mentors_quiz()
    {
        $otherMentor = $this->createUser([
            'role' => 'mentor',
            'learning_path' => 'mobile-development'
        ]);

        $otherCourse = $this->createCourse([
            'created_by' => $otherMentor->id
        ]);

        $otherQuiz = $this->createQuiz([
            'course_id' => $otherCourse->id,
            'created_by' => $otherMentor->id
        ]);

        $response = $this->actingAs($this->mentor)
            ->get(route('mentor.quizzes.show', $otherQuiz));

        $response->assertStatus(403);
    }

    /** @test */
    public function test_mentor_cannot_edit_other_mentors_quiz()
    {
        $otherMentor = $this->createUser([
            'role' => 'mentor',
            'learning_path' => 'data-science'
        ]);

        $otherCourse = $this->createCourse([
            'created_by' => $otherMentor->id
        ]);

        $otherQuiz = $this->createQuiz([
            'course_id' => $otherCourse->id,
            'created_by' => $otherMentor->id
        ]);

        $response = $this->actingAs($this->mentor)
            ->get(route('mentor.quizzes.edit', $otherQuiz));

        $response->assertStatus(403);
    }
}
