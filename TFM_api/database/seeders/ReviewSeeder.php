<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Service;
use App\Models\User;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::pluck('id', 'email')->toArray();
        $services = Service::pluck('id', 'name')->toArray();

        $reviews = [
            // Luna Novias Boutique (3)
            ['service' => 'Luna Novias Boutique', 'user' => 'cliente@gmail.com', 'rating' => 5, 'comment' => 'Vestido perfecto y atención excelente.'],
            ['service' => 'Luna Novias Boutique', 'user' => 'business@gmail.com', 'rating' => 4, 'comment' => 'Gran calidad y plazos cumplidos.'],
            ['service' => 'Luna Novias Boutique', 'user' => 'music@demo.com', 'rating' => 5, 'comment' => 'Atención personalizada y muy profesionales.'],
            ['service' => 'Luna Novias Boutique', 'user' => 'admin@gmail.com', 'rating' => 5, 'comment' => 'Un vestido precioso y una atención impecable.'],

            // Catering Delicias (3)
            ['service' => 'Catering Delicias', 'user' => 'admin@gmail.com', 'rating' => 5, 'comment' => 'Menú delicioso y servicio impecable.'],
            ['service' => 'Catering Delicias', 'user' => 'belleza@demo.com', 'rating' => 4, 'comment' => 'Todo llegó a tiempo y la presentación fue de 10.'],
            ['service' => 'Catering Delicias', 'user' => 'floristeria@demo.com', 'rating' => 5, 'comment' => 'Variedad y sabor, repetiremos.'],

            // DJ Ritmo Pro (3)
            ['service' => 'DJ Ritmo Pro', 'user' => 'decor@demo.com', 'rating' => 5, 'comment' => 'Animó la fiesta de principio a fin.'],
            ['service' => 'DJ Ritmo Pro', 'user' => 'hotel@demo.com', 'rating' => 4, 'comment' => 'Buena selección musical y equipo potente.'],
            ['service' => 'DJ Ritmo Pro', 'user' => 'dj2@demo.com', 'rating' => 5, 'comment' => 'Profesional y muy atento con las peticiones.'],

            // Hotel Mirador del Mar (4)
            ['service' => 'Hotel Mirador del Mar', 'user' => 'catering@demo.com', 'rating' => 5, 'comment' => 'Vistas increíbles y personal muy amable.'],
            ['service' => 'Hotel Mirador del Mar', 'user' => 'luna.novias@demo.com', 'rating' => 4, 'comment' => 'Habitaciones cómodas y desayuno completo.'],
            ['service' => 'Hotel Mirador del Mar', 'user' => 'bridal@demo.com', 'rating' => 5, 'comment' => 'Perfecto para alojar a la familia y amigos.'],
            ['service' => 'Hotel Mirador del Mar', 'user' => 'dj@demo.com', 'rating' => 4, 'comment' => 'Buena organización y atención rápida.'],

            // Otros servicios sueltos
            ['service' => 'Sounds Deluxe DJ', 'user' => 'cliente@gmail.com', 'rating' => 4, 'comment' => 'Buen ambiente y repertorio variado.'],
            ['service' => 'Bridal Dreams Couture', 'user' => 'business@gmail.com', 'rating' => 5, 'comment' => 'Vestidos espectaculares y trato cercano.'],
        ];

        foreach ($reviews as $data) {
            $serviceId = $services[$data['service']] ?? null;
            $userId = $users[$data['user']] ?? null;

            if (!$serviceId || !$userId) {
                continue;
            }

            Review::create([
                'service_id' => $serviceId,
                'user_id' => $userId,
                'rating' => $data['rating'],
                'comment' => $data['comment'],
            ]);
        }
    }
}
