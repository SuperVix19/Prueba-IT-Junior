# Prueba IT Junior

Este proyecto es una aplicación web que implementa un CRUD (Crear, Leer, Actualizar, Eliminar) para gestionar usuarios. Incluye un formulario para agregar usuarios y una tabla para visualizarlos. La aplicación está desarrollada en PHP y utiliza una base de datos MySQL.

# Requisitos Previos

* PHP 7.x o superior
* MySQL
* Composer
* Servidor web (Apache, Nginx, etc.)
* Git

# Instalación

1. Hay que clonar el repositorio en su máquina local.
   
3. Configurar la Base de Datos:
   - Acceda a su gestor de base de datos (phpMyAdmin, MySQL Workbench, línea de comandos, etc.).
   - Ejecute el siguiente script SQL para crear la base de datos y la tabla users:

     ```
     CREATE DATABASE IF NOT EXISTS usuarios_db;
     USE usuarios_db;
     
     CREATE TABLE IF NOT EXISTS users (
         id INT AUTO_INCREMENT PRIMARY KEY,
         nombre VARCHAR(50) NOT NULL,
         apellido VARCHAR(50) NOT NULL,
         email VARCHAR(100) NOT NULL UNIQUE,
         password VARCHAR(255) NOT NULL,
         fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
     );
     ```
     
4. Configure las Credenciales de la Base de Datos:
   - Abra el archivo config.php.
   - Actualice las credenciales de conexión a la base de datos con tus propios datos:

     ```
      $servername = "localhost";           // Cambie si su servidor es diferente
      $username = "tu_usuario_mysql";      // Su usuario de MySQL
      $password = "tu_contraseña_mysql";   // Su contraseña de MySQL
      $dbname = "usuarios_db";             // Nombre de la base de datos creada
     ```
     
5. Instalar Dependencias:
   - Asegurese de tener Composer instalado y después ejecute el comando:

    ```
     composer install
    ```
6.  Configurar la Clave Secreta:
   Para que la API funcione correctamente, necesitas configurar una clave secreta para JWT.
   - Abra el archivo api.php.
   - Busque la siguiente línea y reemplace "tu_clave_secreta" por una clave segura:

   ```
   $secret_key = "tu_clave_secreta";
   ```

7. Ejecutar la Aplicación:
   - Coloque el proyecto en el directorio raíz de su servidor web (por ejemplo, htdocs para XAMPP).
   - Inicie su servidor web y acceda a la aplicación en su navegador: *http://localhost:8080/Prueba-IT-Junior/*
