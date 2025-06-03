<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class MentorDashboardTest extends DuskTestCase
{
     /**
     * @Test
     * @group dashboard-mentor
     */
        public function test_mentor_can_view_dashboard(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'Mentor@skillhub.com')
                ->type('password', 'password')
                ->press('Log In to Your Account')
                ->pause(5000)
                ->assertSee('Selamat Datang, Mentor User!')
                ->screenshot('test_mentor_can_view_dashboard');
        });
    }

    /**
     * @Test
     * @group dashboard-mentor-fail
     */
        public function test_mentor_can_view_dashboard_fail(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'mentors@skillhub.com')
                ->type('password', 'password')
                ->press('Log In to Your Account')
                ->assertPathIs('/login')
                ->screenshot('test_mentor_can_view_dashboard_fail');
        });
    }

    /**
     * @Test
     * @group dashboard-mentor-graph
     */
        public function test_mentor_can_view_graph(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'Mentor@skillhub.com')
                ->type('password', 'password')
                ->press('Log In to Your Account')
                ->pause(5000)
                ->assertSee('Selamat Datang, Mentor User!')
                ->pause(5000)
                ->screenshot('test_mentor_can_view_graph');
        });
    }

    /**
     * @Test
     * @group dashboard-mentor-graph-fail
     */
        public function test_mentor_can_view_graph_fail(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'mentors@skillhub.com')
                ->type('password', 'password')
                ->press('Log In to Your Account')
                ->assertPathIs('/login')
                ->screenshot('test_mentor_can_view_graph_fail');
        });
    }

    /**
     * @Test
     * @group dashboard-mentor-popu
     */
        public function test_mentor_can_view_popu(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'Mentor@skillhub.com')
                ->type('password', 'password')
                ->press('Log In to Your Account')
                ->pause(5000)
                ->assertSee('Selamat Datang, Mentor User!')
                ->pause(5000)
                ->screenshot('test_mentor_can_view_popu');
        });
    }

    /**
     * @Test
     * @group dashboard-mentor-popu-fail
     */
        public function test_mentor_can_view_popu_fail(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'mentors@skillhub.com')
                ->type('password', 'password')
                ->press('Log In to Your Account')
                ->assertPathIs('/login')
                ->screenshot('test_mentor_can_view_popu_fail');
        });
    }

    /**
     * @Test
     * @group dashboard-mentor-logact
     */
        public function test_mentor_can_view_logact(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'Mentor@skillhub.com')
                ->type('password', 'password')
                ->press('Log In to Your Account')
                ->pause(5000)
                ->assertSee('Selamat Datang, Mentor User!')
                ->pause(5000)
                ->screenshot('test_mentor_can_view_logact');
        });
    }

    /**
     * @Test
     * @group dashboard-mentor-logact-fail
     */
        public function test_mentor_can_view_logact_fail(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'mentors@skillhub.com')
                ->type('password', 'password')
                ->press('Log In to Your Account')
                ->assertPathIs('/login')
                ->screenshot('test_mentor_can_view_logact_fail');
        });
    }
}
