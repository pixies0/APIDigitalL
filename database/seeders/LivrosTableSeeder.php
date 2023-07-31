<?php

namespace Database\Seeders;

use App\Models\Livro;
use Illuminate\Database\Seeder;

class LivrosTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        Livro::factory()->count(15)->create();
    }
}
