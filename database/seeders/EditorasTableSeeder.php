<?php

namespace Database\Seeders;

use App\Models\Editora;
use Illuminate\Database\Seeder;

class EditorasTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        Editora::factory()->count(5)->create();
    }
}
