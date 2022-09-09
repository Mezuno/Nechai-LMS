<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         \App\Models\User::factory(500)->create();

         \App\Models\User::factory()->create([
             'name' => 'Цыбульский Кирилл Андреевич',
             'email' => 'mekishido@gmail.com',
             'password' => Hash::make('123654789gG'),
             'is_teacher' => 1,
         ]);

        \App\Models\Quiz::factory(40)->create();

        \App\Models\OptionType::factory()->create([
            'title' => 'radio',
        ]);
        \App\Models\OptionType::factory()->create([
            'title' => 'text',
        ]);
        \App\Models\OptionType::factory()->create([
            'title' => 'checkbox',
        ]);

        \App\Models\Question::factory(160)->create();

        \App\Models\Option::factory(640)->create();

        \App\Models\Assignment::factory(1000)->create();
    }
}
