<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DashboardStudentTest extends DuskTestCase
{
    /**
     * @group dashboardstudent
     */
    public function testLihatDaftarKursusYangTerdaftar(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('SkillHub') // Jika ada homepage info
                    ->clickLink('Login')
                    ->assertPathIs('/login')
                    ->type('email', 'student@example.com') // Ganti dengan akun student valid
                    ->type('password', 'password')         // Sesuaikan dengan password yang benar
                    ->press('Log In to Your Account')
                    ->pause(1000)
                    ->assertPathIs('/dashboard') // Ganti jika path dashboard berbeda
                    ->assertSee('📚 Kursus yang Diikuti & Progres')
                    ->assertSee('Kursus')
                    ->assertSee('Progres')
                    ->assertSee('Status');
        });
    }
}