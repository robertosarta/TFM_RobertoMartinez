<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Roberto',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'phone' => '608854163',
            'address' => 'Calle Ejemplo 123',
            'role' => 'admin',
            //no le pongo el remember token porque al ser admin me parece mejor asi
        ]);

        User::create([
            'name' => 'Carlos',
            'email' => 'cliente@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('cliente123'),
            'phone' => '600112233',
            'address' => 'Avenida Central 45',
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Empresa Demo',
            'email' => 'business@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('business123'),
            'phone' => '600445566',
            'address' => 'Calle Empresa 1',
            'role' => 'business',
        ]);

        User::create([
            'name' => 'Luna Novias',
            'email' => 'luna.novias@demo.com',
            'email_verified_at' => now(),
            'password' => Hash::make('novias123'),
            'phone' => '600111222',
            'address' => 'Calle del Álamo 12, Madrid',
            'role' => 'business',
        ]);

        User::create([
            'name' => 'Catering Delicias',
            'email' => 'catering@demo.com',
            'email_verified_at' => now(),
            'password' => Hash::make('catering123'),
            'phone' => '600333444',
            'address' => 'Av. Sabores 45, Barcelona',
            'role' => 'business',
        ]);

        User::create([
            'name' => 'Sonido & Bandas',
            'email' => 'music@demo.com',
            'email_verified_at' => now(),
            'password' => Hash::make('music123'),
            'phone' => '600555666',
            'address' => 'Calle Ritmo 8, Valencia',
            'role' => 'business',
        ]);

        User::create([
            'name' => 'Belleza Nupcial',
            'email' => 'belleza@demo.com',
            'email_verified_at' => now(),
            'password' => Hash::make('belleza123'),
            'phone' => '600777888',
            'address' => 'Plaza Estilo 3, Sevilla',
            'role' => 'business',
        ]);

        User::create([
            'name' => 'Floristería Pétalos',
            'email' => 'floristeria@demo.com',
            'email_verified_at' => now(),
            'password' => Hash::make('flores123'),
            'phone' => '600999000',
            'address' => 'Rambla Jardín 21, Bilbao',
            'role' => 'business',
        ]);

        User::create([
            'name' => 'DJ Ritmo',
            'email' => 'dj@demo.com',
            'email_verified_at' => now(),
            'password' => Hash::make('dj12345'),
            'phone' => '600123987',
            'address' => 'Av. Musica 10, Madrid',
            'role' => 'business',
        ]);

        User::create([
            'name' => 'Hotel Mirador',
            'email' => 'hotel@demo.com',
            'email_verified_at' => now(),
            'password' => Hash::make('hotel123'),
            'phone' => '600555777',
            'address' => 'Paseo Mar 50, Malaga',
            'role' => 'business',
        ]);

        User::create([
            'name' => 'Flora Urbana',
            'email' => 'decor@demo.com',
            'email_verified_at' => now(),
            'password' => Hash::make('flora123'),
            'phone' => '600888999',
            'address' => 'Calle Verde 8, Valencia',
            'role' => 'business',
        ]);

        User::create([
            'name' => 'Sounds Deluxe',
            'email' => 'dj2@demo.com',
            'email_verified_at' => now(),
            'password' => Hash::make('djdeluxe123'),
            'phone' => '600222333',
            'address' => 'Calle Ritmo 22, Madrid',
            'role' => 'business',
        ]);

        User::create([
            'name' => 'Bridal Dreams',
            'email' => 'bridal@demo.com',
            'email_verified_at' => now(),
            'password' => Hash::make('bridal123'),
            'phone' => '600444555',
            'address' => 'Av. Estilo 9, Barcelona',
            'role' => 'business',
        ]);
    }
}
