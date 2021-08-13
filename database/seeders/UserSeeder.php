<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User, App\Models\Article, App\Models\Label;

class UserSeeder extends Seeder
{

    private $users = array(
        'roka',
        'liba',
        'birka',
        'lo',
        'macska',
        'nyul'
    );

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $labels = [
            Label::factory()->count(1)->create(),
            Label::factory()->count(3)->create(),
            Label::factory()->count(5)->create()
        ];  

        foreach ($this->users as $user) {
            User::factory()
            ->has( Article::factory()->hasAttached($labels[mt_rand(0,2)])->count(3)  )->create([
                'name' => $user,
                'email' => $user . '@mail.hu'
            ]);
        }

        User::factory()
            ->count(10)
            ->create();

        User::factory()
            ->count(5)
            ->unverified()
            ->create();
    }
}
