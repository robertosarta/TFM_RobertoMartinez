🎉 MiBoda — Plataforma de gestión de bodas

TFM Full-Stack Developer · Proyecto final

Una plataforma completa para organizar bodas, gestionar proveedores, servicios, reservas y seguimiento del evento.
Construida como TFM siguiendo buenas prácticas modernas de arquitectura.

🚀 Características principales
🛠️ Backend — Laravel 11 (API REST)
    Autenticación con Sanctum (Bearer tokens)
    -CRUD completo de:
    -Usuarios (admin, user, business)
    -Servicios + imágenes
    -Categorías y subcategorías
    -Bodas del usuario + servicios asociados (pivot con precio, cantidad y estado)
    -Reviews y comentarios
    -Eloquent con eager loading
    -Validaciones potentes
    -Seeders completos
    -Documentación Swagger (L5-Swagger)

🎨 Frontend — Vue 3 + Vite
    SPA con:
    -Vue Router
    -Pinia
    -Axios con interceptores (Bearer automático)
    -Filtros dinámicos
    -Carousels
    -Gestión completa de la boda del usuario
    -Formularios reactivos
    -Estilos propios con metodología BEM

🗄️ Base de Datos — MySQL/MariaDB
    -users
    -services
    -service_images
    -reviews
    -categories
    -subcategories
    -weddings
    -wedding_service   (pivot)
    -personal_access_tokens

🧱 Arquitectura del proyecto
    TFM_root/
    │
    ├── TFM_api/       # Backend Laravel
    │   ├── app/
    │   ├── database/
    │   ├── routes/api.php
    │   ├── .env
    │   └── ...
    │
    └── TFM_front/     # Frontend Vue 3 + Vite
        ├── src/
        ├── src/stores/auth.js
        ├── src/api/axios.js
        ├── src/views/
        ├── .env
        └── ...

📦 Instalación completa
✔️ 1. Requisitos previos
    -Node.js 20+
    -PHP 8.2+
    -Composer
    -Git
    -MySQL/MariaDB (XAMPP recomendado)

✔️ 2. Backend — Laravel (TFM_api)
    -Clonar repositorio:
    -> git clone https://github.com/robertosarta/TFM_RobertoMartinez.git
    --------------------------------------------------------------------
    -Configurar entorno:
    -> cd TFM_api
    -> cp .env.example .env


Editar:
APP_NAME=MiBoda

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tfm
DB_USERNAME=root
DB_PASSWORD=


Crear la BD:
    -> CREATE DATABASE tfm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
Instalar dependencias:
    -> composer install
Generar key:
    -> php artisan key:generate
Migrar + seed:
    -> php artisan migrate --seed
Servidor:
    -> php artisan serve --host=localhost --port=8000

API disponible en
👉 http://localhost:8000/api

✔️ 3. Frontend — Vue 3 (TFM_front)
Crear archivo .env
VITE_API_BASE_URL=http://localhost:8000/api

Instalar dependencias:
    -> cd TFM_front
    -> npm install

Iniciar:
    -> npm run dev

Frontend:
👉 http://localhost:5173

👤 Autor:
Roberto Martínez
TFM — Máster Full-Stack Developer
EBIS Business School
