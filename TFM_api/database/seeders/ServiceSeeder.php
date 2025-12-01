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
                        'url' => 'https://plus.unsplash.com/premium_photo-1673546785747-8068f85588ad?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Colección primavera',
                        'is_primary' => true,
                    ],
                    [
                        'url' => 'https://plus.unsplash.com/premium_photo-1676234842565-bc1df0bfd45a?q=80&w=688&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Vestido corte sirena',
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1594552072238-b8a33785b261?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Vestido con encaje',
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1676132067714-f48047af1509?q=80&w=1332&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'vestidos de novia',
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
                        'url' => 'https://images.unsplash.com/photo-1604531826248-f0eca8eeb896?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Traje slim fit',
                        'is_primary' => true,
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1529635229076-82fefed713c4?q=80&w=1169&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Traje clásico',
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1647900669139-1a968c4091e1?q=80&w=1853&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Traje a medida',
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
                        'url' => 'https://images.unsplash.com/photo-1633076748078-a21f5545c382?q=80&w=1103&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Paleta pastel',
                        'is_primary' => true,
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1562616293-1a11a7816903?q=80&w=1167&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Damas de honor',
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1588260480229-f8c4949de856?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Vestido largo elegante',
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
                        'url' => 'https://plus.unsplash.com/premium_photo-1675107359827-6de8bcf03ccf?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Tocado artesanal',
                        'is_primary' => true,
                    ],
                    [
                        'url' => 'https://plus.unsplash.com/premium_photo-1675003663371-c932159b9bca?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Joyería fina',
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1643229064900-becdea80c4ea?q=80&w=1314&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'anillos de boda',
                    ],
                    [
                        'url' => 'https://plus.unsplash.com/premium_photo-1661308271316-b5ff1c2afcb0?q=80&w=1713&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'collar de perlas',
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
                        'url' => 'https://images.unsplash.com/photo-1752160024756-a45be904f89f?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Buffet de temporada',
                        'is_primary' => true,
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1651964060295-ef9e1ee08667?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Esencia gourmet',
                    ],
                ],
            ],
            [
                'name' => 'Catering Ronda',
                'email' => 'reservas@cateringronda.com',
                'phone' => '600 887 223',
                'address' => [
                    'street' => 'Av. Delicia 17',
                    'city' => 'Burgos',
                    'zip' => '09001',
                ],
                'description' => 'Comida tradicional y de autor para bodas.',
                'price' => 75,
                'subcategory' => 'Restaurante',
                'user_email' => 'catering@demo.com',
                'images' => [
                    [
                        'url' => 'https://plus.unsplash.com/premium_photo-1676226770485-fccba5514ddf?q=80&w=1169&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Comida tradicional',
                        'is_primary' => true,
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1651964060643-ad3785f428fb?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Plato gourmet',
                    ],
                ],
            ],
            [
                'name' => 'Catering Belmont',
                'email' => 'reservas@cateringbelmont.com',
                'phone' => '688 787 523',
                'address' => [
                    'street' => 'Av. Valaquia 3',
                    'city' => 'Barcelona',
                    'zip' => '08005',
                ],
                'description' => 'Platos innovadores y menús personalizados.',
                'price' => 100,
                'subcategory' => 'Restaurante',
                'user_email' => 'catering@demo.com',
                'images' => [
                    [
                        'url' => 'https://images.unsplash.com/photo-1627580358573-ea0c4a2cb199?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Tartas innovadoras',
                        'is_primary' => true,
                    ],
                    [
                        'url' => 'https://plus.unsplash.com/premium_photo-1664206964033-55b538beaec3?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Comida de autor',
                    ],
                    [
                        'url' => 'https://plus.unsplash.com/premium_photo-1728904210892-8a637b81e099?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Menú personalizado',
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
                        'url' => 'https://images.unsplash.com/photo-1707333514156-d42751dca70d?q=80&w=764&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Jardines de la masía',
                        'is_primary' => true,
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1544137171-9f5cf7b0fafa?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Carpa para eventos',
                    ],
                ],
            ],
            [
                'name' => 'Villa Amor',
                'email' => 'eventos@villamor.com',
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
                        'url' => 'https://images.unsplash.com/photo-1707333514156-d42751dca70d?q=80&w=764&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Jardines de la masía',
                        'is_primary' => true,
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1544137171-9f5cf7b0fafa?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Carpa para eventos',
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
                        'url' => 'https://images.unsplash.com/photo-1661441248350-ffdbc88f6a68?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8cmFtbyUyMGJvZGF8ZW58MHx8MHx8fDA%3D',
                        'caption' => 'Ramo de novia',
                        'is_primary' => true,
                    ],
                    [
                        'url' => 'https://plus.unsplash.com/premium_photo-1674235768948-5d365df2f20b?q=80&w=688&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Ramo rústico',
                    ],
                    [
                        'url' => 'https://plus.unsplash.com/premium_photo-1675107360191-5b87521acc1b?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Centro de mesa',
                    ],
                ],
            ],
            [
                'name' => 'Floristería Asfodelo',
                'email' => 'info@asfodelo.com',
                'phone' => '600 754 284',
                'address' => [
                    'street' => 'Calle Blanca 12',
                    'city' => 'Bilbao',
                    'zip' => '48001',
                ],
                'description' => 'Decoración floral integral y ramos personalizados.',
                'price' => 125,
                'subcategory' => 'Floristería',
                'user_email' => 'floristeria@demo.com',
                'images' => [
                    [
                        'url' => 'https://plus.unsplash.com/premium_photo-1674759743145-02a7c98455a3?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Decoracion floral',
                        'is_primary' => true,
                    ],
                    [
                        'url' => 'https://plus.unsplash.com/premium_photo-1670291474266-6b4bc5d6016f?q=80&w=688&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Ramo personalizado',
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1632528011905-54e2464961f4?q=80&w=701&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Centro elegante',
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
                        'url' => 'https://images.unsplash.com/photo-1593470309378-bf460a1c7f10?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Decoración ceremonia',
                        'is_primary' => true,
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1629141731648-0bce61cf4f7e?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Centro de mesa',
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=800&q=80',
                        'caption' => 'Arco floral',
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
                    [
                        'url' => 'https://plus.unsplash.com/premium_photo-1661326352695-6cbe1ff74ee9?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Maquillaje natural',
                    ],
                ],
            ],
            [
                'name' => 'Maquillaje Pro Art',
                'email' => 'maquillaje@proart.com',
                'phone' => '600 223 445',
                'address' => [
                    'street' => 'Calle hermosa 33',
                    'city' => 'Bilbao',
                    'zip' => '41005',
                ],
                'description' => 'Maquillaje especializado para novias y eventos.',
                'price' => 135,
                'subcategory' => 'Maquillaje',
                'user_email' => 'belleza@demo.com',
                'images' => [
                    [
                        'url' => 'https://images.unsplash.com/photo-1612883695890-f2ab22e65215?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Maquillaje de novia',
                        'is_primary' => true,
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1680696227092-8a9d689a0f25?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Maquillaje sofisticado',
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
                        'url' => 'https://images.unsplash.com/photo-1581674210501-c760093514e8?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Recogido elegante',
                        'is_primary' => true,
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=800&q=80',
                        'caption' => 'Recogido romántico',
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1664918327381-3d531e82783b?q=80&w=1171&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Peinado con trenzas',
                    ],
                ],
            ],
            [
                'name' => 'Peinados ensueño',
                'email' => 'info@peinadosensueño.com',
                'phone' => '666 384 756',
                'address' => [
                    'street' => 'Calle Ensueño 5',
                    'city' => 'Madrid',
                    'zip' => '28015',
                ],
                'description' => 'Tu peinado perfecto para el gran día.',
                'price' => 300,
                'subcategory' => 'Peluquería novia',
                'user_email' => 'belleza@demo.com',
                'images' => [
                    [
                        'url' => 'https://images.unsplash.com/photo-1571582159064-31fbb694f6a6?q=80&w=1171&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Recogido elegante con accesorios',
                        'is_primary' => true,
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1742569179482-69bcce48ab6d?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Recogido romántico con ondas',
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1655117021087-0d7b49126a91?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Peinado con lazo',
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
                        'url' => 'https://plus.unsplash.com/premium_photo-1663040288115-757ad61a36f5?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'DJ en acción',
                        'is_primary' => true,
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1563398809469-7f81c6a366da?q=80&w=1174&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Cabina iluminada',
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
                        'url' => 'https://plus.unsplash.com/premium_photo-1703084849298-de53b56b2017?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Violín en directo',
                        'is_primary' => true,
                    ],
                    [
                        'url' => 'https://plus.unsplash.com/premium_photo-1703084848732-51cddf2e2b43?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Violínista en boda',
                    ],
                ],
            ],
            [
                'name' => 'Los Trovadores',
                'email' => 'contacto@trovadores.com',
                'phone' => '658 542 583',
                'address' => [
                    'street' => 'Av. Sol 24',
                    'city' => 'Madrid',
                    'zip' => '28020',
                ],
                'description' => 'Banda acústica para ceremonia y cóctel.',
                'price' => 820,
                'subcategory' => 'Banda en vivo',
                'user_email' => 'music@demo.com',
                'images' => [
                    [
                        'url' => 'https://plus.unsplash.com/premium_photo-1719467541042-332142d0cfb6?q=80&w=1171&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Guitarrista en boda',
                        'is_primary' => true,
                    ],
                    [
                        'url' => 'https://plus.unsplash.com/premium_photo-1719467541072-7b53ae7e93c4?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Guitarristas acústicos',
                    ],
                    [
                        'url' => 'https://plus.unsplash.com/premium_photo-1719467541041-e7a3f429b956?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Cantante en boda',
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
                    [
                        'url' => 'https://images.unsplash.com/photo-1604017011826-d3b4c23f8914?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Sesión postboda',
                    ],
                ],
            ],
            [
                'name' => 'Foto Pepito',
                'email' => 'pepitosfoto@gmail.com',
                'phone' => '600 687 872',
                'address' => [
                    'street' => 'Calle Lente 7',
                    'city' => 'Madrid',
                    'zip' => '28012',
                ],
                'description' => 'Fotografía profesional para bodas y eventos.',
                'price' => 2000,
                'subcategory' => 'Fotografía',
                'user_email' => 'business@gmail.com',
                'images' => [
                    [
                        'url' => 'https://images.unsplash.com/flagged/photo-1566150217714-ebfea356f885?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Estilo natural',
                        'is_primary' => true,
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1573676048035-9c2a72b6a12a?q=80&w=1171&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Estilo vintage',
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1608326670856-e3b41eecb106?q=80&w=1169&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Estilo artístico',
                    ],
                ],
            ],
            [
                'name' => 'Foto Antonio',
                'email' => 'antoniofoto@gmail.com',
                'phone' => '6608 685 822',
                'address' => [
                    'street' => 'Calle Espina 24',
                    'city' => 'Madrid',
                    'zip' => '28012',
                ],
                'description' => 'Fotografía profesional para bodas y eventos.',
                'price' => 1100,
                'subcategory' => 'Fotografía',
                'user_email' => 'business@gmail.com',
                'images' => [
                    [
                        'url' => 'https://images.unsplash.com/photo-1532712938310-34cb3982ef74?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Fotografía preboda',
                        'is_primary' => true,
                    ],
                    [
                        'url' => 'https://plus.unsplash.com/premium_photo-1756839167319-1aec653acc3a?q=80&w=1171&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Encantadora sesión preboda',
                    ],
                    [
                        'url' => 'https://plus.unsplash.com/premium_photo-1756839166254-f3a6e650b995?q=80&w=1171&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Fotografía creativa',
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
                        'url' => 'https://images.unsplash.com/photo-1738851952441-2a7d17487545?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
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
                        'url' => 'https://images.unsplash.com/photo-1499512670907-145ba08fcc16?q=80&w=1631&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Dron en vuelo',
                        'is_primary' => true,
                    ],
                    [
                        'url' => 'https://plus.unsplash.com/premium_photo-1669365863862-ab5c12897576?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Plano aéreo',
                    ],
                    [
                        'url' => 'https://plus.unsplash.com/premium_photo-1756839166114-8d5755b36bb2?q=80&w=1171&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Plano aéreo',
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
                        'url' => 'https://plus.unsplash.com/premium_photo-1673569395547-6ee230fc5377?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Tarta personalizada',
                        'is_primary' => true,
                    ],
                    [
                        'url' => 'https://plus.unsplash.com/premium_photo-1673569474304-8cc2b216a727?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Tarta de boda elegante',
                    ],
                ],
            ],
            [
                'name' => 'Tutarta',
                'email' => 'pedidos@tutarta.com',
                'phone' => '600 221 334',
                'address' => [
                    'street' => 'Calle Goloso 19',
                    'city' => 'Madrid',
                    'zip' => '28030',
                ],
                'description' => 'Tartas para bodas y eventos especiales.',
                'price' => 300,
                'subcategory' => 'Tarta nupcial',
                'user_email' => 'catering@demo.com',
                'images' => [
                    [
                        'url' => 'https://images.unsplash.com/photo-1631998878375-236a6826ce7f?q=80&w=1183&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Tarta personalizada',
                        'is_primary' => true,
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1559373098-e1caaccae791?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Tarta personalizada',
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1678473289821-1818e3f82e9a?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Tarta temática',
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
                'description' => 'Servicio de limusinas premium.',
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
                'name' => 'Limo Weddings',
                'email' => 'info@limoweddings.com',
                'phone' => '600 432 355',
                'address' => [
                    'street' => 'Paseo Limo 54',
                    'city' => 'Madrid',
                    'zip' => '28040',
                ],
                'description' => 'Servicio de limusinas con chófer para novios.',
                'price' => 240,
                'subcategory' => 'Limusinas',
                'user_email' => 'business@gmail.com',
                'images' => [
                    [
                        'url' => 'https://images.unsplash.com/photo-1676107648535-931375db52e2?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Limusina',
                        'is_primary' => true,
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1676107773690-9d670f8b1afa?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Limusina para bodas',
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
                        'url' => 'https://plus.unsplash.com/premium_photo-1661963542752-9a8a1d72fb28?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Bus para invitados',
                        'is_primary' => true,
                    ],
                    [
                        'url' => 'https://plus.unsplash.com/premium_photo-1676795223467-dad25a1e12d8?q=80&w=1632&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Bus lujoso',
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
                        'url' => 'https://plus.unsplash.com/premium_photo-1678286770016-6306ad61df9b?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Piscina del hotel',
                        'is_primary' => true,
                    ],
                    [
                        'url' => 'https://plus.unsplash.com/premium_photo-1678286771657-cf22aa97faf0?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Piscina del hotel 2',
                    ],
                    [
                        'url' => 'https://plus.unsplash.com/premium_photo-1678286769677-470c0777ac71?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Habitación doble',
                    ],
                ],
            ],
            [
                'name' => 'DJ Ritmo Pro',
                'email' => 'contacto@djritmo.com',
                'phone' => '600 321 999',
                'address' => [
                    'street' => 'Calle Beat 12',
                    'city' => 'Madrid',
                    'zip' => '28015',
                ],
                'description' => 'DJ para ceremonia, cóctel y fiesta con equipo completo.',
                'price' => 620,
                'subcategory' => 'DJ',
                'user_email' => 'dj@demo.com',
                'images' => [
                    [
                        'url' => 'https://plus.unsplash.com/premium_photo-1726754423208-48e352458725?q=80&w=1189&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Cabina iluminada',
                        'is_primary' => true,
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1651065699236-6a6885503943?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'DJ en directo',
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=1170&auto=format&fit=crop',
                        'caption' => 'Ambiente de fiesta',
                    ],
                ],
            ],
            [
                'name' => 'Hotel Mirador del Mar',
                'email' => 'reservas@miradordelmar.com',
                'phone' => '600 777 111',
                'address' => [
                    'street' => 'Paseo Mirador 5',
                    'city' => 'Malaga',
                    'zip' => '29010',
                ],
                'description' => 'Hotel boutique frente al mar con paquetes para invitados.',
                'price' => 110,
                'subcategory' => 'Hotel',
                'user_email' => 'hotel@demo.com',
                'images' => [
                    [
                        'url' => 'https://plus.unsplash.com/premium_photo-1748075354873-cb66bb1901bc?q=80&w=1227&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Habitación con vistas',
                        'is_primary' => true,
                    ],
                    [
                        'url' => 'https://plus.unsplash.com/premium_photo-1748075588586-525c48d6dd03?q=80&w=1113&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Piscina y terraza',
                    ],
                ],
            ],
            [
                'name' => 'Flora Urbana Studio',
                'email' => 'hola@floraurbana.com',
                'phone' => '600 888 999',
                'address' => [
                    'street' => 'Calle Verde 8',
                    'city' => 'Valencia',
                    'zip' => '46002',
                ],
                'description' => 'Decoración floral moderna: arcos, centros y ramos personalizados.',
                'price' => 380,
                'subcategory' => 'Decoración floral',
                'user_email' => 'decor@demo.com',
                'images' => [
                    [
                        'url' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=800&q=80',
                        'caption' => 'Arco floral contemporáneo',
                        'is_primary' => true,
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=800&q=80',
                        'caption' => 'Centro de mesa',
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1629141731648-0bce61cf4f7e?q=80&w=800&auto=format&fit=crop',
                        'caption' => 'Ramo personalizado',
                    ],
                ],
            ],
            [
                'name' => 'Sounds Deluxe DJ',
                'email' => 'hola@soundsdeluxe.com',
                'phone' => '600 222 333',
                'address' => [
                    'street' => 'Calle Ritmo 22',
                    'city' => 'Madrid',
                    'zip' => '28010',
                ],
                'description' => 'DJ para bodas con equipo de iluminación y sonido premium.',
                'price' => 700,
                'subcategory' => 'DJ',
                'user_email' => 'dj2@demo.com',
                'images' => [
                    [
                        'url' => 'https://images.unsplash.com/photo-1489515217757-5fd1be406fef?auto=format&fit=crop&w=1170&q=80',
                        'caption' => 'Cabina con luces',
                        'is_primary' => true,
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1464375117522-1311d6a5b81f?auto=format&fit=crop&w=1170&q=80',
                        'caption' => 'DJ animando la pista',
                    ],
                ],
            ],
            [
                'name' => 'Bridal Dreams Couture',
                'email' => 'info@bridaldreams.com',
                'phone' => '600 444 555',
                'address' => [
                    'street' => 'Av. Estilo 9',
                    'city' => 'Barcelona',
                    'zip' => '08005',
                ],
                'description' => 'Vestidos de novia de autor y colecciones exclusivas.',
                'price' => 1900,
                'subcategory' => 'Vestidos de novia',
                'user_email' => 'bridal@demo.com',
                'images' => [
                    [
                        'url' => 'https://images.unsplash.com/photo-1678862812110-2326de6ea750?q=80&w=1074&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Vestido corte sirena',
                        'is_primary' => true,
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1676132068643-9ebacec267e2?q=80&w=715&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'caption' => 'Vestido clásico con encaje',
                    ],
                    [
                        'url' => 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=1170&q=80',
                        'caption' => 'Vestido de colección',
                    ],
                ],
            ],
        ];

        foreach ($services as $entry) {
            $subcategoryId = $subcategories[$entry['subcategory']] ?? null; //Sacamos el id de la subcategoría gracias al nombre de la subcategoría
            $userId = $users[$entry['user_email']] ?? ($users['business@gmail.com'] ?? null);  //Sacamos el id del usuario o el de la cuenta gracias al email y si no existe le asignamos el id del usuario business por defecto

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
