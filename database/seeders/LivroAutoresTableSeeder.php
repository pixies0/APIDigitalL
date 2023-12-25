<?php

namespace Database\Seeders;

use App\Models\Livro_Autor;
use App\Models\Livro_Copia;
use Illuminate\Database\Seeder;

class LivroAutoresTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        Livro_Autor::factory()->count(15)->create();
        Livro_Copia::factory()->count(15)->create();
    }
}
