<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

class RegisterTest extends DuskTestCase
{
     /**
     * @group registration
     */
    public function test_user_registration()
    {
        $this->browse(function (Browser $browser) {
            $faker = \Faker\Factory::create('hu');
            $name = $faker->name;

            $browser->visit('/register')
                ->assertSee('Registration')
                ->press('form button[type="submit"]')
                ->waitForText('Save')
                ->assertSee('The name field is required')
                ->assertSee('The email field is required.')
                ->assertSee('The password field is required.')
                ->type('email', Str::slug($name, '.') . '@mail.hu')
                ->type('name', $name)
                ->type('password', 'password')
                ->type('password_confirmation', 'password')
                ->press('form button[type="submit"]')
                ->waitForText('A fresh verification link has been sent to your email address. Check your email for a verification link.');
        });
    }
}
