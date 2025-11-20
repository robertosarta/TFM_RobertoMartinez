<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceImage;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subcategories = Subcategory::pluck('id', 'name')->toArray();
        $users = User::pluck('id', 'email')->toArray();

        $services = [
            [
                'name' => 'Luna Novias Boutique',
                'email' => 'contacto@lunanovias.com',
                'phone' => '600 123 456',
                'address' => [
                    'street' => 'Calle del Sol 18',
                    'city' => 'Madrid',
                    'zip' => '28010',
                ],
                'description' => 'Vestidos a medida y firmas premium para novias.',
                'price' => 1800,
                'subcategory' => 'Vestidos de novia',
                'user_email' => 'luna.novias@demo.com',
                'images' => [
                    [
                        'url' => 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=800&q=80',
                        'caption' => 'Colección primavera',
                        'is_primary' => true,
                    ],
                ],
            ],
            [
                'name' => 'Trajes El Gent',
                'email' => 'info@trajeselgent.com',
                'phone' => '600 321 654',
                'address' => [
                    'street' => 'Av. Gran Vía 120',
                    'city' => 'Barcelona',
                    'zip' => '08010',
                ],
                'description' => 'Sastrería artesanal para novio y padrinos.',
                'price' => 950,
                'subcategory' => 'Traje de novio',
                'user_email' => 'business@gmail.com',
                'images' => [
                    [
                        'url' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=800&q=80',
                        'caption' => 'Traje slim fit',
                        'is_primary' => true,
                    ],
                ],
            ],
            [
                'name' => 'Damas Chic Atelier',
                'email' => 'hola@damaschic.com',
                'phone' => '600 444 111',
                'address' => [
                    'street' => 'Calle Jardín 9',
                    'city' => 'Valencia',
                    'zip' => '46001',
                ],
                'description' => 'Vestidos de damas de honor y fiesta coordinados.',
                'price' => 350,
                'subcategory' => 'Vestidos de damas de honor',
                'user_email' => 'belleza@demo.com',
                'images' => [
                    [
                        'url' => 'https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?auto=format&fit=crop&w=800&q=80',
                        'caption' => 'Paleta pastel',
                        'is_primary' => true,
                    ],
                ],
            ],
            [
                'name' => 'Aura Complementos',
                'email' => 'contacto@auracomplementos.com',
                'phone' => '600 555 222',
                'address' => [
                    'street' => 'Pasaje Elegancia 3',
                    'city' => 'Sevilla',
                    'zip' => '41001',
                ],
                'description' => 'Velos, tocados y joyería para novias.',
                'price' => 120,
                'subcategory' => 'Complementos',
                'user_email' => 'belleza@demo.com',
                'images' => [
                    [
                        'url' => 'https://images.unsplash.com/photo-1524504388940-0c3b3d5455a0?auto=format&fit=crop&w=800&q=80',
                        'caption' => 'Tocado artesanal',
                        'is_primary' => true,
                    ],
                ],
            ],
            [
                'name' => 'Catering Delicias',
                'email' => 'reservas@cateringdelicias.com',
                'phone' => '600 777 333',
                'address' => [
                    'street' => 'Av. Gourmet 22',
                    'city' => 'Madrid',
                    'zip' => '28014',
                ],
                'description' => 'Menús personalizados y showcooking en bodas.',
                'price' => 65,
                'subcategory' => 'Restaurante',
                'user_email' => 'catering@demo.com',
                'images' => [
                    [
                        'url' => 'https://images.unsplash.com/photo-1529042355635-76a1f5ef90b3?auto=format&fit=crop&w=800&q=80',
                        'caption' => 'Buffet de temporada',
                        'is_primary' => true,
                    ],
                ],
            ],
            [
                'name' => 'Masía Sant Jordi',
                'email' => 'eventos@masiasantjordi.com',
                'phone' => '600 888 444',
                'address' => [
                    'street' => 'Camí de la Vinya s/n',
                    'city' => 'Girona',
                    'zip' => '17007',
                ],
                'description' => 'Masía con jardines y carpa incluida.',
                'price' => 1200,
                'subcategory' => 'Hacienda',
                'user_email' => 'catering@demo.com',
                'images' => [
                    [
                        'url' => 'https://images.unsplash.com/photo-1501117716987-c8e1ecb210af?auto=format&fit=crop&w=800&q=80',
                        'caption' => 'Jardines de la masía',
                        'is_primary' => true,
                    ],
                ],
            ],
            [
                'name' => 'Floristería Pétalos',
                'email' => 'info@petalos.com',
                'phone' => '600 999 000',
                'address' => [
                    'street' => 'Calle Verde 15',
                    'city' => 'Bilbao',
                    'zip' => '48001',
                ],
                'description' => 'Ramos, centros y decoración floral integral.',
                'price' => 280,
                'subcategory' => 'Floristería',
                'user_email' => 'floristeria@demo.com',
                'images' => [
                    [
                        'url' => 'https://images.unsplash.com/photo-1501004318641-b39e6451bec6?auto=format&fit=crop&w=800&q=80',
                        'caption' => 'Ramo de novia',
                        'is_primary' => true,
                    ],
                ],
            ],
            [
                'name' => 'VerdeOlivo Floral',
                'email' => 'contacto@verdeolivo.com',
                'phone' => '600 112 334',
                'address' => [
                    'street' => 'Av. Botánica 5',
                    'city' => 'Madrid',
                    'zip' => '28002',
                ],
                'description' => 'Arcos, centros de mesa y ambientación floral.',
                'price' => 450,
                'subcategory' => 'Decoración floral',
                'user_email' => 'floristeria@demo.com',
                'images' => [
                    [
                        'url' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=800&q=80',
                        'caption' => 'Arco floral',
                        'is_primary' => true,
                    ],
                ],
            ],
            [
                'name' => 'Glam Studio Maquillaje',
                'email' => 'maquillaje@glamstudio.com',
                'phone' => '600 223 445',
                'address' => [
                    'street' => 'Calle Belleza 30',
                    'city' => 'Sevilla',
                    'zip' => '41005',
                ],
                'description' => 'Maquillaje de novia y pruebas previas a domicilio.',
                'price' => 130,
                'subcategory' => 'Maquillaje',
                'user_email' => 'belleza@demo.com',
                'images' => [
                    [
                        'url' => 'https://images.unsplash.com/photo-1526045478516-99145907023c?auto=format&fit=crop&w=800&q=80',
                        'caption' => 'Maquillaje editorial',
                        'is_primary' => true,
                    ],
                ],
            ],
            [
                'name' => 'Bridal Hair Co',
                'email' => 'info@bridalhairco.com',
                'phone' => '600 334 556',
                'address' => [
                    'street' => 'Calle Trenzas 7',
                    'city' => 'Madrid',
                    'zip' => '28015',
                ],
                'description' => 'Recogidos, pruebas y servicio en el día de la boda.',
                'price' => 140,
                'subcategory' => 'Peluquería novia',
                'user_email' => 'belleza@demo.com',
                'images' => [
                    [
                        'url' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=800&q=80',
                        'caption' => 'Recogido romántico',
                        'is_primary' => true,
                    ],
                ],
            ],
            [
                'name' => 'DJ Vibes',
                'email' => 'dj@vibes.com',
                'phone' => '600 445 667',
                'address' => [
                    'street' => 'Calle Música 21',
                    'city' => 'Valencia',
                    'zip' => '46002',
                ],
                'description' => 'DJ para ceremonia, cóctel y fiesta.',
                'price' => 550,
                'subcategory' => 'DJ',
                'user_email' => 'music@demo.com',
                'images' => [
                    [
                        'url' => 'https://images.unsplash.com/photo-1489515217757-5fd1be406fef?auto=format&fit=crop&w=800&q=80',
                        'caption' => 'Cabina iluminada',
                        'is_primary' => true,
                    ],
                ],
            ],
            [
                'name' => 'Cuarteto Allegro',
                'email' => 'contacto@cuartetoallegro.com',
                'phone' => '600 556 778',
                'address' => [
                    'street' => 'Av. Armonía 14',
                    'city' => 'Madrid',
                    'zip' => '28020',
                ],
                'description' => 'Violín y cuerdas para ceremonia y cóctel.',
                'price' => 820,
                'subcategory' => 'Violín',
                'user_email' => 'music@demo.com',
                'images' => [
                    [
                        'url' => 'https://images.unsplash.com/photo-1519947486511-46149fa0a254?auto=format&fit=crop&w=800&q=80',
                        'caption' => 'Violín en directo',
                        'is_primary' => true,
                    ],
                ],
            ],
            [
                'name' => 'Lens & Love',
                'email' => 'hola@lenslove.com',
                'phone' => '600 667 889',
                'address' => [
                    'street' => 'Calle Óptica 4',
                    'city' => 'Madrid',
                    'zip' => '28012',
                ],
                'description' => 'Reportaje fotográfico completo, preboda y postboda.',
                'price' => 1500,
                'subcategory' => 'Fotografía',
                'user_email' => 'business@gmail.com',
                'images' => [
                    [
                        'url' => 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=800&q=80',
                        'caption' => 'Sesión preboda',
                        'is_primary' => true,
                    ],
                ],
            ],
            [
                'name' => 'Motion Memories',
                'email' => 'video@motionmemories.com',
                'phone' => '600 778 990',
                'address' => [
                    'street' => 'Av. Cine 30',
                    'city' => 'Madrid',
                    'zip' => '28027',
                ],
                'description' => 'Vídeo cinematográfico con entrega en 4K.',
                'price' => 1400,
                'subcategory' => 'Video',
                'user_email' => 'business@gmail.com',
                'images' => [
                    [
                        'url' => 'https://images.unsplash.com/photo-1489515217757-5fd1be406fef?auto=format&fit=crop&w=800&q=80',
                        'caption' => 'Rodaje ceremonial',
                        'is_primary' => true,
                    ],
                ],
            ],
            [
                'name' => 'SkyShots Drone',
                'email' => 'info@skyshots.com',
                'phone' => '600 889 001',
                'address' => [
                    'street' => 'Calle Altura 2',
                    'city' => 'Madrid',
                    'zip' => '28018',
                ],
                'description' => 'Tomas aéreas con dron para vídeo y foto.',
                'price' => 320,
                'subcategory' => 'Drone',
                'user_email' => 'business@gmail.com',
                'images' => [
                    [
                        'url' => 'https://images.unsplash.com/photo-1489515217757-5fd1be406fef?auto=format&fit=crop&w=800&q=80',
                        'caption' => 'Plano aéreo',
                        'is_primary' => true,
                    ],
                ],
            ],
            [
                'name' => 'FunBooth 360',
                'email' => 'reservas@funbooth.com',
                'phone' => '600 990 112',
                'address' => [
                    'street' => 'Calle Sonrisas 6',
                    'city' => 'Madrid',
                    'zip' => '28025',
                ],
                'description' => 'Fotomatón 360 con atrezzo y álbum digital.',
                'price' => 380,
                'subcategory' => 'Fotomatón',
                'user_email' => 'business@gmail.com',
                'images' => [
                    [
                        'url' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=800&q=80',
                        'caption' => 'Cabina 360',
                        'is_primary' => true,
                    ],
                ],
            ],
            [
                'name' => 'Sweet Cakes',
                'email' => 'pedidos@sweetcakes.com',
                'phone' => '600 221 334',
                'address' => [
                    'street' => 'Calle Dulce 11',
                    'city' => 'Madrid',
                    'zip' => '28030',
                ],
                'description' => 'Tartas nupciales personalizadas y postres gourmet.',
                'price' => 280,
                'subcategory' => 'Tarta nupcial',
                'user_email' => 'catering@demo.com',
                'images' => [
                    [
                        'url' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=800&q=80',
                        'caption' => 'Tarta personalizada',
                        'is_primary' => true,
                    ],
                ],
            ],
            [
                'name' => 'Royal Ride',
                'email' => 'info@royalride.com',
                'phone' => '600 332 445',
                'address' => [
                    'street' => 'Paseo Príncipe 14',
                    'city' => 'Madrid',
                    'zip' => '28040',
                ],
                'description' => 'Servicio de limusinas con chófer para novios.',
                'price' => 240,
                'subcategory' => 'Limusinas',
                'user_email' => 'business@gmail.com',
                'images' => [
                    [
                        'url' => 'https://images.unsplash.com/photo-1525609004556-c46c7d6cf023?auto=format&fit=crop&w=800&q=80',
                        'caption' => 'Limusina premium',
                        'is_primary' => true,
                    ],
                ],
            ],
            [
                'name' => 'Shuttle Bus Bodas',
                'email' => 'contacto@shuttlebus.com',
                'phone' => '600 443 556',
                'address' => [
                    'street' => 'Av. Ruta 19',
                    'city' => 'Madrid',
                    'zip' => '28050',
                ],
                'description' => 'Traslado invitados en autobús y microbús.',
                'price' => 180,
                'subcategory' => 'Autobús',
                'user_email' => 'business@gmail.com',
                'images' => [
                    [
                        'url' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=800&q=80',
                        'caption' => 'Bus para invitados',
                        'is_primary' => true,
                    ],
                ],
            ],
            [
                'name' => 'Hotel Costa Azul',
                'email' => 'reservas@costazul.com',
                'phone' => '600 554 667',
                'address' => [
                    'street' => 'Paseo Marítimo 88',
                    'city' => 'Málaga',
                    'zip' => '29016',
                ],
                'description' => 'Hotel frente al mar con paquetes de alojamiento para invitados.',
                'price' => 95,
                'subcategory' => 'Hotel',
                'user_email' => 'business@gmail.com',
                'images' => [
                    [
                        'url' => 'https://images.unsplash.com/photo-1501117716987-c8e1ecb210af?auto=format&fit=crop&w=800&q=80',
                        'caption' => 'Habitación con vistas',
                        'is_primary' => true,
                    ],
                ],
            ],
        ];

        foreach ($services as $entry) {
            $subcategoryId = $subcategories[$entry['subcategory']] ?? null;
            $userId = $users[$entry['user_email']] ?? ($users['business@gmail.com'] ?? null);

            if (!$subcategoryId || !$userId) {
                continue;
            }

            $service = Service::create([
                'name' => $entry['name'],
                'email' => $entry['email'],
                'phone' => $entry['phone'],
                'address' => $entry['address'],
                'description' => $entry['description'],
                'price' => $entry['price'],
                'user_id' => $userId,
                'subcategory_id' => $subcategoryId,
            ]);

            foreach ($entry['images'] as $index => $img) {
                ServiceImage::create([
                    'service_id' => $service->id,
                    'url' => $img['url'],
                    'caption' => $img['caption'] ?? null,
                    'is_primary' => $img['is_primary'] ?? ($index === 0),
                    'sort_order' => $index,
                ]);
            }
        }
    }
}

