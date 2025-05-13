<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AddRating003Test extends DuskTestCase
{
    /**
     * A Dusk test example.
     * @group AddRating003
     */
    public function testAddRating003(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'Raffanda@gmail.com') // Ganti dengan email user valid
                ->type('password', 'Raffa123')   // Ganti dengan password valid
                ->press('Log In to Your Account')
                ->assertPathIs('/dashboard')
                ->pause(1000) // Tunggu sidebar tampil
                ->waitFor('a[href="http://127.0.0.1:8000/ratings"]')
                ->click('a[href="http://127.0.0.1:8000/ratings"]')
                ->assertPathIs('/ratings')
                ->clickLink('Tambah Rating')
                ->assertPathIs('/ratings/create')
                ->select('course_id','1')
                ->select('value','5')
                ->type('comment', 'bagus') 
                ->press('Simpan Rating')
                ->assertPathIs('/ratings');

        });
    }
}