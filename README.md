# Instalación de un Proyecto Laravel API Existente desde GitHub

Esta guía te guiará a través del proceso de instalación de un proyecto Laravel API existente clonado desde GitHub.

## Prerrequisitos

Antes de comenzar, asegúrate de tener instalados los siguientes requisitos:

* **PHP:** Laravel requiere una versión de PHP compatible. Se recomienda PHP 8.1 o superior.
* **Composer:** Composer es un administrador de dependencias para PHP. Puedes descargarlo desde [getcomposer.org](https://getcomposer.org/).
* **Git:** Git es necesario para clonar el repositorio desde GitHub.

## Pasos de Instalación

1.  **Clonar el repositorio de GitHub:**

    Abre tu terminal y ejecuta el siguiente comando para clonar el repositorio:

    ```bash
    https://github.com/GiioBass/dcmrn_back_hotels.git
    ```

    Reemplaza `URL_DEL_REPOSITORIO.git` con la URL del repositorio de GitHub y `nombre-del-proyecto-api` con el nombre que desees para el directorio del proyecto.

2.  **Navegar al directorio del proyecto:**

    Una vez que se complete la clonación, navega al directorio del proyecto:

    ```bash
    cd nombre-del-proyecto-api
    ```

3.  **Instalar dependencias de PHP:**

    Ejecuta el siguiente comando para instalar las dependencias de PHP definidas en el archivo `composer.json`:

    ```bash
    composer install
    ```

4.  **Configurar las variables de entorno:**

    * Si el repositorio incluye un archivo `.env.example`, cópialo a `.env`:

        ```bash
        cp .env.example .env
        ```

    * Si no hay un archivo `.env.example`, consulta la documentación del proyecto o contacta al propietario del repositorio para obtener las variables de entorno necesarias.
    * Abre el archivo `.env` y configura las siguientes variables:
        * `APP_NAME`: El nombre de tu aplicación.
        * `APP_ENV`: El entorno de la aplicación (local, producción, etc.).
        * `APP_KEY`: Genera una nueva clave de aplicación ejecutando `php artisan key:generate`.
        * `DB_CONNECTION`: El tipo de conexión de base de datos (mysql, pgsql, sqlite, sqlsrv).
        * `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`: Los detalles de conexión de tu base de datos.
        * Otras variables que sean necesarias para el proyecto.

5.  **Generar la clave de la aplicación:**

    Ejecuta el siguiente comando para generar una nueva clave de aplicación:

    ```bash
    php artisan key:generate
    ```

6.  **Ejecutar las migraciones de la base de datos:**

    Ejecuta el siguiente comando para ejecutar las migraciones de la base de datos:

    ```bash
    php artisan migrate
    ```

 
7.  **Iniciar el servidor de desarrollo:**

    Ejecuta el siguiente comando para iniciar el servidor de desarrollo de Laravel:

    ```bash
    php artisan serve
    ```

    La aplicación estará disponible en `http://localhost:8000`.



## Configuración adicional

* Consulta la documentación del proyecto o los archivos README para obtener instrucciones de configuración específicas.
* Configura `CORS` para que las peticiones de otros dominios puedan acceder a tu API si el proyecto lo requiere.

## Recursos adicionales

* [Documentación de Laravel](https://laravel.com/docs)
* El README y la documentación del proyecto en GitHub.

¡Felicidades! Has instalado correctamente el proyecto Laravel API existente desde GitHub. Ahora puedes comenzar a trabajar en él.