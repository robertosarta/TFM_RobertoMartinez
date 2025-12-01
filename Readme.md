Una plataforma completa para gestionar bodas, servicios, proveedores y reservas.
Construida como TFM del Máster Full-Stack Developer, siguiendo buenas prácticas modernas de arquitectura.


-Características principales-

........-🛠️Backend – Laravel 11 (API REST)-............
-Autenticación con Sanctum (Bearer tokens)
-CRUD completo de:
  -Usuarios (roles: admin, user, business)
  -Servicios + imágenes
  -Categorías y subcategorías
  -Bodas del usuario + servicios asociados (pivot con precio, cantidad y estado)
  -Reviews/comentarios
-Relaciones Eloquent optimizadas con eager loading
-Validación robusta (FormRequest, confirmed, unique, etc.)
-Seeders y datos de ejemplo listos para usar
-Documentación Swagger (L5-Swagger)


........-🎨 Frontend – Vue 3 + Vite-............
-SPA moderna con:
  -Vue Router
  -Pinia (auth store + token + user data)
  -Axios con interceptores (incluye el Bearer token automáticamente)
  -Filtros dinámicos por categoría/subcategoría
  -Carousels de imágenes
  -Gestión de la boda del usuario
  -Formularios reactivos y validaciones
-Estilos propios con metodología BEM


........-🗄️ Base de Datos – MySQL/MariaDB-............
-Tablas:
  users, services, service_images, reviews, categories, subcategories,
  weddings, wedding_service (pivot), personal_access_tokens.
-Migraciones y seeders incluidos.


........-🧱 Arquitectura del proyecto-............
TFM_root/
│
├─ TFM_api/               # Backend Laravel
│   ├─ app/
│   ├─ routes/api.php
│   ├─ database/migrations
│   ├─ database/seeders
│   ├─ .env
│   └─ ...
│
└─ TFM_front/             # Frontend Vue 3 + Vite
    ├─ src/
    ├─ src/stores/auth.js
    ├─ src/api/axios.js
    ├─ src/views/
    ├─ .env
    └─ ...


........-🚀 Instalación completa-............
Apto para cualquier PC limpio, solo requiere Git + Node + PHP + Composer + MySQL.

---------✔️ 1. Requisitos previos---------------
Instalar en tu máquina:

-Node.js 20+
-PHP 8.2+
-Composer Última versión
-Git Para clonar
-MySQL / MariaDB Se recomienda XAMPP

---------✔️ 2. Backend — Laravel (TFM_api)---------------
Clonar repositorio:
  -> git clone https://github.com/robertosarta/TFM_RobertoMartinez.git

Configurar .env:
  -> cd TFM_api
  -> cp .env.example .env


Editar los valores:
  APP_NAME=MiBoda
  APP_KEY=

  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=tfm
  DB_USERNAME=root
  DB_PASSWORD=

⚠️ Si usas XAMPP:
-Usuario: root
-Password: (vacío)

Crear la base de datos:
En phpMyAdmin o consola MySQL:
  -> CREATE DATABASE tfm DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

Instalar dependencias:
  -> composer install

Generar APP_KEY:
  -> php artisan key:generate

Migrar + seeders
  -> php artisan migrate --seed

Ejecutar servidor:
  -> php artisan serve --host=localhost --port=8000


La API estará disponible en:

http://localhost:8000/api

---------✔️ 3. Frontend — Vue 3 (TFM_front)---------------
Crear archivo .env
En TFM_front/.env, añadir:
  -> VITE_API_BASE_URL=http://localhost:8000/api

Instalar dependencias
  -> cd TFM_front
  -> npm install

Iniciar el servidor de desarrollo
  -> npm run dev


La web se abrirá en:

http://localhost:5173


........-🤝 Autor-............
Roberto Martínez
TFM — Máster Full-Stack Developer
EBIS Business School