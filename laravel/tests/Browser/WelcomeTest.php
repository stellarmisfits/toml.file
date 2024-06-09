<?php

namespace Tests\Browser;

use Tests\DuskTestCase;

class WelcomeTest extends DuskTestCase
{
    /** @test */
    public function basicTest()
    {
        $this->browse(function ($browser) {
            $browser->visit('/')
                ->assertSee('Laravel');
        });
    }
}
