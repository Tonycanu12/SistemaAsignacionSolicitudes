# Proyecto API Laravel 

Este documento proporciona instrucciones sobre cómo configurar y ejecutar el api


# Requisitos Previos

- [PHP](https://www.php.net/downloads) (incluido en XAMPP)

- [Composer](https://getcomposer.org/download/)

- [MySQL](https://dev.mysql.com/downloads/) (incluido en XAMPP)

- [Git](https://git-scm.com/downloads)

- [XAMPP](https://www.apachefriends.org/index.html)

## Pasos para Ejecutar la API

  

### 1. Clonar el Repositorio

```bash
git  clone  https://github.com/usuario/nombre-del-repositorio.git
```
### 2. Instalar Dependencias
Navega al directorio del proyecto y utiliza Composer para instalar las dependencias del proyecto:
```bash
cd nombre-del-repositorio
composer install

```
## 3. Configurar el Archivo `.env`

Crea el archivo `.env ` luego copia el contenido de   `.env.example ` para crear tu archivo de configuración de entorno.

Edita el archivo `.env` para configurar tus credenciales de base de datos y otras variables de entorno. Asegúrate de establecer los valores correctos para tu entorno de XAMPP:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_base_de_datos
DB_USERNAME=root
DB_PASSWORD=
```
> **Note:  Crea primero la base de datos (con cualquier nombre) y luego modifica el .env, en XAMPP el usuario por defecto para MySQL es `root` y la contraseña suele estar vacía.
## 3.1  Generar la Clave de la Aplicación
Genera la clave de la aplicación de Laravel ejecutando el siguiente comando:
```bash
php artisan key:generate
```
al ejecutar el comando el key se asignara en tu archivo .env

## 4. Ejecutar Migraciones y Seeders

Ejecuta las migraciones para crear las tablas en tu base de datos:
```bash
php artisan migrate
```
Ejecutar 2 seeders: 
```bash
php artisan db:seed --class=users
```
```bash
php artisan db:seed --class=request
```
## 5.Ejecutar el Servidor de Desarrollo

Inicia el servidor de desarrollo de Laravel con el siguiente comando:
```bash
php artisan serve
```
## 6.Prueba en Postman 
Descarga el json  y importalo en Postman:

[Api _Laravel.postman_collection.json](Api _Laravel.postman_collection.json)


