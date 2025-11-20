<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;

class SubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mapping = [
            'Fotografía y video' => [
                'Fotografía',
                'Video',
                'Drone',
                'Fotomatón',
            ],
            'Música y animación' => [
                'DJ',
                'Banda en vivo',
                'Piano',
                'Violín',
                'Saxofón',
                'Karaoke',
            ],
            'Lugar y catering' => [
                'Hacienda',
                'Restaurante',
                'Carpas',
            ],
            'Decoración' => [
                'Decoración floral',
            ],
            'Moda y estilismo' => [
                'Vestidos de novia',
                'Traje de novio',
                'Vestidos de damas de honor',
                'Complementos',
                'Peluquería novia',
                'Peluquería novio',
                'Maquillaje',
                'Estética',
                'Barbería',
            ],
            'Transporte' => [
                'Limusinas',
                'Autobús',
            ],
            'Repostería' => [
                'Tarta nupcial',
                'Postres artesanales',
            ],
            'Flores' => [
                'Floristería',
            ],
            'Alojamiento' => [
                'Hotel',
            ],
        ];

        foreach ($mapping as $categoryName => $subcats) {
            $categoryId = Category::where('name', $categoryName)->value('id');

            if (!$categoryId) {
                continue;
            }

            foreach ($subcats as $name) {
                Subcategory::firstOrCreate([
                    'name' => $name,
                    'category_id' => $categoryId,
                ]);
            }
        }
    }
}

