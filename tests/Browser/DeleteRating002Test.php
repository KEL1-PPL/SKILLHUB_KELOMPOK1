<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DeleteRating002Test extends DuskTestCase
{
    /**
     * A Dusk test example.
     * * @group DeleteRating002
     */
    public function testDeleteRating002(): void
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
                ->press('Hapus') // Klik tombol Hapus pertama untuk membuka modal
                ->waitFor('.modal-content') // Tunggu modal muncul
                ->pause(500) // Tunggu sebentar untuk memastikan modal tampil
                ->click('.btn.btn-danger') // Klik tombol Hapus di dalam modal
                ->waitUntilMissing('.modal-content');// Tunggu sampai modal hilang
        });
    }
}
