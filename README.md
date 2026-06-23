# Aroma Real - Tienda de Café de Especialidad

Este es el repositorio monorepo **Aroma Real**, una plataforma de comercio electrónico de café de especialidad. La plataforma cuenta con una arquitectura desacoplada conformada por un backend (API RESTful en Laravel 10) y un frontend (Single Page Application en Vue 3).

---

## 📁 Estructura del Proyecto

El espacio de trabajo está dividido en dos aplicaciones principales:

```text
Cafe/
├── backend/       # API REST en Laravel 10 (PHP)
└── frontend/      # Aplicación SPA en Vue 3 + Vite (JavaScript/Tailwind CSS)
```

---

## 🛠️ Tecnologías y Arquitectura

### Backend ([Laravel 10](file:///c:/Users/USUARIO/Desktop/PruebasDeSoftware/Cafe/backend))
* **Patrón de Diseño**: Repository-Service Pattern. Permite desacoplar las consultas a la base de datos (Eloquent) de las reglas de negocio en los servicios.
* **Autenticación**: Laravel Sanctum (emisión de tokens personales de acceso rápido).
* **Base de Datos**: MySQL.
* **Manejo de Respuestas**: Trait personalizado `ApiResponse` para estandarizar respuestas exitosas y de error en formato JSON.

### Frontend ([Vue 3](file:///c:/Users/USUARIO/Desktop/PruebasDeSoftware/Cafe/frontend))
* **Empaquetador**: Vite para compilación y recarga en caliente ultra rápidas.
* **Gestión de Estado**: Pinia (tiendas globales para el carrito y el catálogo de productos).
* **Peticiones HTTP**: Axios (con interceptores para adjuntar de forma dinámica el token JWT en las cabeceras de autorización).
* **Diseño y Estilos**: Tailwind CSS con un sistema estético premium, paletas desaturadas basadas en HSL y animaciones fluidas.
* **Notificaciones**: SweetAlert2 para interacciones agradables al usuario (ej. añadir al carrito).

---

## 📋 Requisitos Previos

Antes de ejecutar el proyecto de manera local, asegúrate de tener instalados los siguientes componentes:

* **PHP** (versión `>= 8.1`)
* **Composer** (gestor de dependencias de PHP)
* **Node.js** (versión `>= 18`) y **npm**
* **Servidor MySQL** (como XAMPP, Laragon, Docker o servicio nativo)

---

## 🚀 Instalación y Configuración

Sigue estos pasos para poner en marcha el proyecto localmente.

### 1. Configuración del Backend

1. Abre tu terminal y ve al directorio `backend`:
   ```bash
   cd backend
   ```

2. Instala las dependencias de Composer:
   ```bash
   composer install
   ```

3. Crea una copia del archivo de variables de entorno y genera la clave de aplicación:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Configura los parámetros de tu base de datos MySQL en el archivo `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=cafe_ecommerce
   DB_USERNAME=tu_usuario
   DB_PASSWORD=tu_contraseña
   ```

5. Ejecuta las migraciones y siembra los datos iniciales de prueba (cafés de especialidad y un usuario semilla):
   ```bash
   php artisan migrate --seed
   ```

6. Levanta el servidor local de desarrollo de Laravel:
   ```bash
   php artisan serve
   ```
   *Por defecto, la API estará disponible en `http://127.0.0.1:8000`.*

---

### 2. Configuración del Frontend

1. Abre otra terminal y ve al directorio `frontend`:
   ```bash
   cd frontend
   ```

2. Instala las dependencias de Node.js:
   ```bash
   npm install
   ```

3. Asegúrate de configurar la URL base del backend si se requiere. La aplicación usa `/api` como ruta relativa y un proxy o Axios configurado en [apiClient.js](file:///c:/Users/USUARIO/Desktop/PruebasDeSoftware/Cafe/frontend/src/api/apiClient.js).

4. Levanta el servidor de desarrollo de Vite:
   ```bash
   npm run dev
   ```
   *Por defecto, la aplicación frontend estará disponible en `http://localhost:5173`.*

---

## 🌐 Endpoints Principales de la API

* **Autenticación (Público)**:
  * `POST /api/auth/register` - Crear cuenta de cliente.
  * `POST /api/auth/login` - Iniciar sesión para obtener Token.
* **Catálogo de Café (Público)**:
  * `GET /api/products` - Obtener lista de cafés de especialidad.
  * `GET /api/products/{id}` - Obtener el detalle de un café por ID.
* **Órdenes & Checkout (Protegido por Token)**:
  * `POST /api/checkout` - Enviar orden de compra y descontar stock.
  * `GET /api/orders` - Obtener historial de compras del usuario autenticado.
* **Seguimiento Anónimo (Público)**:
  * `GET /api/orders/track?email={email}` - Ver pedidos asociados a un email.

---

## 🧪 Datos de Prueba Sembrados

* **Usuario de Prueba**:
  * **Email**: `cliente@premium.com`
  * **Contraseña**: `password`
* **Cafés en Catálogo**:
  * *Café Colombia Origen Huila* (Tueste Medio)
  * *Etiopía Yirgacheffe G1* (Tueste Claro)
  * *Kenia AA Mount Kenya* (Tueste Claro)
  * *Costa Rica Tarrazú Honey* (Tueste Medio)
  * *Brasil Cerrado Dulce* (Tueste Oscuro)
  * *Café Espresso Blend Premium* (Tueste Oscuro)
