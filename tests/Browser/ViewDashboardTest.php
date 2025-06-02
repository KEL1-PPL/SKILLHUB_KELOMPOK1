<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ViewDashboardTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        // Nonaktifkan foreign key check untuk hindari error saat drop table
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    }

    /**
     * @test
     * @group dashboard
     */
    public function test_mentor_can_view_dashboard(): void
    {
        $this->browse(function (Browser $browser) {
            // Buat user mentor
            User::factory()->create([
                'email' => 'mentorrey@gmail.com',
                'password' => Hash::make('password'), // sesuai dengan input form
                'role' => 'mentor',
            ]);

            $browser->visit('/login')
                ->type('email', 'mentorrey@gmail.com')
                ->type('password', 'password')
                ->press('Log In to Your Account') // sesuaikan dengan tombol form login
                ->pause(2000) // beri waktu untuk redirect jika perlu
                ->assertPathIs('/dashboard')
                ->screenshot('mentor-dashboard'); // opsional: bantu debugging visual
        });
    }

/**
     * @test
     * @group dashboard-failed
     */
    public function test_mentor_can_view_dashboard_failed(): void
    {
        $this->browse(function (Browser $browser) {
            // Buat user mentor
            User::factory()->create([
                'email' => 'mentorrey@gmail.com',
                'password' => Hash::make('password'), // sesuai dengan input form
                'role' => 'mentor',
            ]);

            $browser->visit('/login')
                ->type('email', 'mentorreynal@gmail.com')
                ->type('password', 'password')
                ->press('Log In to Your Account') // sesuaikan dengan tombol form login
                ->pause(2000) // beri waktu untuk redirect jika perlu
                ->assertPathIs('/login')
                ->screenshot('mentor-dashboard-failed'); // opsional: bantu debugging visual
        });
    }
}
