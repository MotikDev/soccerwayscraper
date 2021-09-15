<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Chrome;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class navigationTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {


        $this->browse(function ($browser) {
            $browser->visit('/')
                    ->assertSee('Laravel');
        });



    }
}
