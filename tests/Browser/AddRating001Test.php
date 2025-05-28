<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AddRating001Test extends DuskTestCase
{
    /**
     * @group AddRating001
     */
    public function testAddRating001(): void
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
                ->assertPathIs('/ratings');
        });
    }
}
