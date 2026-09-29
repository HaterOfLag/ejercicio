# Catálogo de películas con PHP y MySQL

Este proyecto es una pequeña aplicación web para gestionar una base de datos de películas. Permite ver, añadir, modificar y borrar registros de forma sencilla desde una interfaz en navegador.

## Descripción
La aplicación puede usarse como un CRUD básico (Crear, Leer, Actualizar y Eliminar). Está pensada para almacenar información como el título, director, género, año y duración de cada película.

## Requisitos
- PHP 7 o superior
- Servidor local como Apache, XAMPP, WAMP o LAMP
- MySQL
- Navegador web

## Instalación y configuración
1. Importa el archivo `database.sql` en tu base de datos MySQL para crear la estructura necesaria.
2. Revisa la configuración de conexión en `config.php`.
3. Asegúrate de que el proyecto esté ubicado dentro de la carpeta pública de tu servidor local.
4. Abre la aplicación desde el navegador en una ruta similar a esta:
   - `http://localhost/<carpeta-del-proyecto>/index.php`

## Cómo funciona
La app permite realizar estas acciones:
- Mostrar todas las películas registradas
- Agregar una nueva película
- Editar los datos de una película existente
- Eliminar una película de la base de datos

## Archivos principales
- `index.php`: vista principal y formulario para añadir películas
- `editar.php`: pantalla para actualizar información
- `eliminar.php`: lógica para borrar un registro
- `config.php`: conexión con la base de datos
- `database.sql`: script de creación de la base de datos y la tabla
- `api/peliculas.php`: endpoint que devuelve los datos en formato JSON

## Ejecución
1. Inicia Apache y MySQL.
2. Accede a `index.php` desde el navegador.
3. Completa el formulario para registrar una película.
4. Desde la lista puedes editar o eliminar cualquier registro.

## Nota
El proyecto está diseñado para uso académico o práctico y demuestra el funcionamiento básico de un CRUD en PHP con conexión a MySQL.