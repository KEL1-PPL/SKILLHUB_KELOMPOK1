<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class MentorArticleTest extends DuskTestCase
{
    /**
     * @Test
     * @group artikel-mentor
     */
    public function testCreateArticle(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'mentor@skillhub.com')
                ->type('password', 'password')
                ->press('Log In to Your Account')
                ->assertPathIs('/dashboard')
                ->pause(5000)
                ->assertSee('Selamat Datang, Mentor User!')
                ->clickLink('Articles')
                ->assertPathIs('/articles')
                ->assertSee('Articles')
                ->pause(2000)
                ->clickLink('Create New Article')
                ->assertPathIs('/articles/create')
                ->type('title', 'Tes Artikel')
                ->type('content', 'Tes artikel')
                ->press('Simpan Artikel')
                ->pause(2000)
                ->screenshot('testCreateArticle');
        });
    }

    /**
     * @Test
     * @group artikel-mentor-fail
     */
    public function testCreateArticlefail(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'mentor@skillhub.com')
                ->type('password', 'password')
                ->press('Log In to Your Account')
                ->assertPathIs('/dashboard')
                ->pause(5000)
                ->assertSee('Selamat Datang, Mentor User!')
                ->clickLink('Articles')
                ->assertPathIs('/articles')
                ->assertSee('Articles')
                ->pause(2000)
                ->clickLink('Create New Article')
                ->assertPathIs('/articles/create')
                ->type('title', 'Tes Artikel')
                ->type('content', 'Tes')
                ->press('Simpan Artikel')
                ->assertSee('The content field must be at least 10 characters.');
        });
    }

    
    /**
     * @Test
     * @group artikel-siswa
     */
        public function testViewArticle(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'student@skillhub.com')
                ->type('password', 'password')
                ->press('Log In to Your Account')
                ->pause(5000)
                ->assertSee('Top 6 Kursus Populer')
                ->clickLink('Articles')
                ->assertPathIs('/articles')
                ->assertSee('Articles')
                ->pause(2000)
                ->clickLink('Lebih lanjut')
                ->assertPathIs('/articles/4')
                ->assertSee('Articles');
        });
    }

    /**
     * @Test
     * @group artikel-siswa-fail
     */
        public function testViewArticlefail(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'students@skillhub.com')
                ->type('password', 'password')
                ->press('Log In to Your Account')
                ->assertPathIs('/login');
        });
    }


    /**
     * @Test
     * @group artikel-mentor-edit
     */
   public function testEditArticle(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'mentor@skillhub.com')
                ->type('password', 'password')
                ->press('Log In to Your Account')
                ->pause(5000)
                ->assertSee('Selamat Datang, Mentor User!')
                ->clickLink('Articles')
                ->assertPathIs('/articles')
                ->assertSee('Articles')
                ->pause(2000)
                ->clickLink('Edit')
                ->assertPathIs('/articles/5/edit')
                ->type('title', 'Tes Artikell')
                ->type('content', 'Tes artikelll')
                ->press('Update Article')
                ->pause(2000)
                ->assertSee('Articles');
        });
    }

    /**
     * @Test
     * @group artikel-edit-fail
     */
    public function testEditArticlefail(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'mentor@skillhub.com')
                ->type('password', 'password')
                ->press('Log In to Your Account')
                ->assertPathIs('/dashboard')
                ->pause(5000)
                ->assertSee('Selamat Datang, Mentor User!')
                ->clickLink('Articles')
                ->assertPathIs('/articles')
                ->assertSee('Articles')
                ->pause(2000)
                ->clickLink('Edit')
                ->assertPathIs('/articles/1/edit')
                ->type('title', 'Tes Artikell')
                ->type('content', 'Tes')
                ->press('Update Article');
        });
    }


    /**
     * @Test
     * @group artikel-mentor-delete
     */
    public function testDeleteArticle(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'mentor@skillhub.com')
                ->type('password', 'password')
                ->press('Log In to Your Account')
                ->pause(5000)
                ->assertSee('Selamat Datang, Mentor User!')
                ->clickLink('Articles')
                ->assertPathIs('/articles')
                ->press('Delete')
                ->pause(2000)
                ->assertPathIs('/articles')
                ->pause(2000)
                ->assertSee('Articles');
        });
    }

    /**
     * @Test
     * @group artikel-mentor-delete-fail
     */
        public function testDeleteArticlefail(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'mentors@skillhub.com')
                ->type('password', 'password')
                ->press('Log In to Your Account')
                ->assertPathIs('/login');
        });
    }


    /**
     * @Test
     * @group artikel-admin
     */
     public function testAdminArticle(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'admin@skillhub.com')
                ->type('password', 'password')
                ->press('Log In to Your Account')
                ->pause(5000)
                ->clickLink('Articles')
                ->assertPathIs('/articles')
                ->press('Approve')
                ->pause(2000)
                ->assertPathIs('/articles')
                ->pause(2000)
                ->assertSee('Articles');
        });
    }


    /**
     * @Test
     * @group artikel-admin-fail
     */
        public function testAdminArticlefail(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'Admins@skillhub.com')
                ->type('password', 'password')
                ->press('Log In to Your Account')
                ->assertPathIs('/login');
        });
    }

}