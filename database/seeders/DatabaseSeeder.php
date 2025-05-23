<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UsersTableSeeder::class);
        $this->call(EditorasTableSeeder::class);
        $this->call(LivrosTableSeeder::class);
        $this->call(UnidadesTableSeeder::class);
        $this->call(AutoresTableSeeder::class);
        $this->call(LivroAutoresTableSeeder::class);
        $this->call(LivroEmprestimosTableSeeder::class);
    }

}
