<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(5)->create()->each(function ($user) {
            \App\Models\Store::factory(5)->create(['owner_id'=>$user->id])->each(function ($store) {
                \App\Models\Product::factory(5)->create(['store_id'=>$store->id]);
            });
        });
    }
}
