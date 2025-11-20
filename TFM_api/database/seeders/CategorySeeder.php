<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Fotografía y video',
            'Música y animación',
            'Lugar y catering',
            'Decoración',
            'Moda y estilismo',
            'Transporte',
            'Repostería',
            'Flores',
            'Alojamiento',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}

