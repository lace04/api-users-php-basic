# api-users-php

Esta es una API simple para administrar usuarios, construida con PHP.

## Estructura del proyecto

```
api-users-php
├── src
│   ├── index.php
│   ├── Config
│   └── Database.php
└── README.md
```

## Archivos

- `src/index.php`: El punto de entrada de la aplicación. Incluye los archivos necesarios y gestiona la solicitud y respuesta.

- `src/Config/Database.php`: Contiene una clase `Database` que se encarga de conectarse a la base de datos. Tiene métodos como `getConnection` que devuelve una instancia de conexión.

## Configuración

1. Clona el repositorio.
2. Ejecute `composer install` para instalar las dependencias.
3. Configure su base de datos y actualice los detalles de la conexión en `src/Config/Database.php`.
4. Inicie el servidor y realice solicitudes a la API.

## Uso

La API tiene los siguientes puntos finales:

- `POST /usuarios`: Crea un nuevo usuario.
- `GET /users`: obtiene una lista de todos los usuarios.
- `GET /users/{id}`: Obtiene un usuario específico.
- `PUT /users/{id}`: Actualiza un usuario específico.
- `DELETE /users/{id}`: Elimina un usuario específico.

Cada punto final devuelve una respuesta JSON.
