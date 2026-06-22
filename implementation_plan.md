# Plan de Implementación: Sistema de Autenticación (Login & Registro)

Este plan describe la incorporación de un sistema de login y registro de usuarios para la tienda "Aroma Real". Los clientes deberán iniciar sesión para poder realizar pedidos, asociando sus compras a su cuenta y permitiéndoles visualizar su historial de pedidos.

---

## User Review Required

> [!IMPORTANT]
> **Cambios en Seguridad de Rutas:**
> - El endpoint de Checkout (`POST /api/checkout`) pasará a estar protegido bajo el middleware `auth:sanctum`, por lo que no se permitirán compras anónimas.
> - Se creará una nueva vista de Historial de Pedidos (`/profile` o `/orders`) accesible solo para usuarios autenticados.

> [!IMPORTANT]
> **Usuario Semilla para Pruebas Rápidas:**
> - Añadiremos un usuario por defecto en el seeder para facilitar pruebas inmediatas:
>   - **Email:** `cliente@premium.com`
>   - **Contraseña:** `password`

---

## Open Questions

> [!IMPORTANT]
> **Flujo al Intentar Comprar Sin Sesión:**
> ¿Cómo prefieres que reaccione la aplicación si un usuario intenta proceder al checkout sin haber iniciado sesión?
> - **Opción A (Recomendada):** Mostrar una alerta interactiva con SweetAlert2 que informe al usuario y le ofrezca un botón para redirigirlo directamente al formulario de login, guardando el estado del carrito para no perder su progreso.
> - **Opción B:** Redirigir de manera automática y directa a la pantalla de login sin mostrar alertas previas.

---

## Proposed Changes

### Backend (Laravel 10)

#### [MODIFY] [DatabaseSeeder.php](file:///c:/Users/User/Desktop/Cafe/backend/database/seeders/DatabaseSeeder.php)
- Sembrar un usuario por defecto en la base de datos para pruebas inmediatas.

#### [NEW] [AuthController.php](file:///c:/Users/User/Desktop/Cafe/backend/app/Http/Controllers/AuthController.php)
- Crear el controlador de autenticación con los métodos `login`, `register`, `logout` y `me`.
- Retornar tokens personales de Sanctum (`createToken`).

#### [MODIFY] [OrderRepositoryInterface.php](file:///c:/Users/User/Desktop/Cafe/backend/app/Interfaces/OrderRepositoryInterface.php) y [OrderRepository.php](file:///c:/Users/User/Desktop/Cafe/backend/app/Repositories/OrderRepository.php)
- Implementar el método `getByUserId(int $userId): array` para obtener el historial de pedidos de un cliente.

#### [MODIFY] [OrderController.php](file:///c:/Users/User/Desktop/Cafe/backend/app/Http/Controllers/OrderController.php)
- Asociar de forma automática el `user_id` del usuario autenticado (`auth()->id()`) al registrar una nueva orden en el método `store`.
- Crear el método `myOrders()` que devuelva el historial de compras del usuario autenticado.

#### [MODIFY] [api.php (Rutas)](file:///c:/Users/User/Desktop/Cafe/backend/routes/api.php)
- Registrar las rutas públicas de autenticación (`/auth/login`, `/auth/register`).
- Proteger las rutas `/checkout`, `/orders` (historial) y `/auth/logout`, `/auth/me` con el middleware `auth:sanctum`.

---

### Frontend (Vue 3)

#### [MODIFY] [apiClient.js](file:///c:/Users/User/Desktop/Cafe/frontend/src/api/apiClient.js)
- Agregar un interceptor de solicitudes de Axios para adjuntar de manera dinámica el token del usuario (`Authorization: Bearer <token>`) en cada petición saliente si existe en `localStorage`.

#### [NEW] [authStore.js](file:///c:/Users/User/Desktop/Cafe/frontend/src/stores/authStore.js)
- Crear la tienda de Pinia para gestionar el estado de autenticación (`user`, `token`, `isAuthenticated`).
- Implementar acciones `login(credentials)`, `register(data)`, `logout()` y `fetchCurrentUser()`, persistiendo el token en `localStorage`.

#### [NEW] [LoginView.vue](file:///c:/Users/User/Desktop/Cafe/frontend/src/views/LoginView.vue) y [RegisterView.vue](file:///c:/Users/User/Desktop/Cafe/frontend/src/views/RegisterView.vue)
- Crear las vistas de inicio de sesión y registro de cuentas.
- Diseñar interfaces con formularios estilizados con nuestra paleta HSL desaturada, bordes translúcidos y micro-animaciones premium en las entradas.

#### [NEW] [ProfileView.vue](file:///c:/Users/User/Desktop/Cafe/frontend/src/views/ProfileView.vue)
- Crear una vista de perfil de usuario donde se muestren sus datos y un listado/historial interactivo de sus pedidos anteriores con su respectivo estado (`paid`, `pending`, etc.).

#### [MODIFY] [router.js](file:///c:/Users/User/Desktop/Cafe/frontend/src/router.js)
- Registrar las nuevas rutas (`/login`, `/register`, `/profile`).
- Configurar metadatos de ruta (`requiresAuth: true`) para `/checkout` y `/profile`.
- Implementar un protector de navegación global (`router.beforeEach`) que verifique el token del usuario y redirija a `/login` si es necesario.

#### [MODIFY] [Navbar.vue](file:///c:/Users/User/Desktop/Cafe/frontend/src/components/Navbar.vue)
- Actualizar la barra de navegación para reaccionar al estado de autenticación:
  - **Usuario Anónimo:** Mostrar botones de "Iniciar Sesión" y "Registrarse".
  - **Usuario Autenticado:** Mostrar el nombre del usuario con un menú desplegable hacia "Mi Perfil" (historial) y el botón de "Cerrar Sesión".

---

## Verification Plan

### Automated Tests
- Ejecutar `npm run build` en el frontend para validar que no haya errores de compilación con las nuevas dependencias de ruta y estado.
- Ejecutar `php artisan route:list` para verificar el correcto mapeo de middleware en los endpoints de la API.

### Manual Verification
1. **Registro e Inicio de Sesión:** Registrar un nuevo usuario y verificar que se guarde correctamente en la base de datos MySQL, retornando e inyectando el token JWT.
2. **Control de Acceso (Route Guards):** Intentar entrar a `/checkout` o `/profile` sin estar logueado y comprobar el redireccionamiento.
3. **Flujo de Compra Autenticado:** Realizar una compra completa simulada y verificar en la base de datos que la orden se registre asociada al id del usuario.
4. **Visualización de Historial:** Ingresar a "Mi Perfil" y validar que se listen las compras previamente realizadas en formato de tarjeta premium.
