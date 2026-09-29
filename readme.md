# CRUD de películas

## Requisitos
- PHP 7+ o 8+
- Servidor web local (Apache/XAMPP/WAMP/LAMP)
- MySQL
- Navegador web

## Configuración
1. Importa el archivo `cine.sql` en tu base de datos MySQL.
2. Verifica la conexión en `db.php`.
3. Si usas un servidor local, coloca la carpeta del proyecto en la raíz del servidor web, por ejemplo: `htdocs/crud_examen`.

## Puesta en marcha
1. Inicia Apache y MySQL.
2. Abre en el navegador:
   - `http://localhost/crud_examen/index.php`

## Funcionamiento
La aplicación permite gestionar una base de datos de películas con estas acciones:
- Ver todas las películas
- Agregar una nueva película
- Editar una película existente
- Eliminar una película

## CRUD
- Crear: usa `create.php`
- Leer: `index.php` muestra la lista
- Actualizar: `edit.php`
- Eliminar: `delete.php`

El flujo principal se realiza desde la interfaz de `index.php`.

Enlace de github: https://github.com/HaterOfLag/ejercicio