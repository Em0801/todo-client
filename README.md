# TODO Client

Cliente web que consume la API-TO-DO para gestionar tareas. Este cliente permite realizar operaciones CRUD (Crear, Leer, Actualizar, Eliminar) sobre las tareas a través de una interfaz web amigable.

## Características

- Listado de tareas en formato tabla
- Creación de nuevas tareas
- Actualización del estado de tareas (pendiente/completada)
- Eliminación de tareas
- Interfaz responsive usando Bootstrap 5
- Manejo de errores y mensajes de usuario


## Requisitos

- Servidor web con PHP 7.4 o superior
- Acceso a la API-TO-DO
- Conexión a Internet (para CDN de Bootstrap y jQuery)

## Configuración

1. Asegúrate de que la API-TO-DO esté funcionando correctamente
2. Coloca la carpeta `todo-client` en tu servidor web
3. Copia `config.example.php` a `config.php`
4. Edita `config.php` y configura tu API key:
   ```php
   return [
       'api' => [
           'base_url' => 'http://localhost/API-TO-DO/api/',
           'key' => 'TU_API_KEY_AQUI'
       ]
   ];
   ```
5. Asegúrate que `config.php` esté incluido en .gitignore

## Uso

1. Accede a través del navegador: `http://tu-servidor/todo-client/`
2. Para crear una tarea:
   - Completa el formulario en la parte superior
   - Haz clic en "Crear Tarea"
3. Para gestionar tareas existentes:
   - Cambiar estado: Usa el botón con ícono de check
   - Eliminar: Usa el botón con ícono de papelera

## Endpoints Utilizados

- `GET /api/tasks` - Obtener todas las tareas
- `POST /api/tasks` - Crear nueva tarea
- `PUT /api/tasks/{id}` - Actualizar estado de tarea
- `DELETE /api/tasks/{id}` - Eliminar tarea

## Autenticación

El cliente utiliza una API key para autenticarse con la API:

javascript
headers: {
'Authorization': 'testapikey1234567890'
}


## Tecnologías Utilizadas

- PHP 7.4+
- Bootstrap 5
- jQuery 3.6
- Bootstrap Icons

## Soporte

Para reportar problemas o solicitar nuevas características, por favor crear un issue en el repositorio principal de API-TO-DO.
