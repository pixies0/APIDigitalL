<?php

namespace Database\Seeders;

use App\Models\Autores;
use Illuminate\Database\Seeder;

class AutoresTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        Autores::factory()->count(6)->create();
    }
}
