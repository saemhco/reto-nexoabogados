<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

## Dependencias y Requisitos

| Apps      | Description                 | Installs                                               | Guide |
| --------- | --------------------------- | ------------------------------------------------------ | ----- |
| Laravel 9 | web application framework   | [Install](https://laravel.com/docs/9.x)                | -     |
| php       | required version ^**8.015** | [Install](https://www.apachefriends.org/download.html) | -     |
| sympfony  | required to load app images | [Install](https://symfony.com/download)                | -     |
| composer  | required to access to aws   | [Install](https://getcomposer.org/download/)           | -     |

## Empezando

1. crear el archivo file .env
   Ejecute el siguiente comando

```
cp .env.example .env
```

2. Instalar dependencias. Ejecute el siguiente comando

```
composer install
```

3. Crear Base de Dato llamada

```
reto_nexoabogados
```

configurar los valores de la base de datos en el archivo .env
ejemplo:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=reto_nexoabogados
DB_USERNAME=root
DB_PASSWORD=
```

4. generate key
   Ejecute el comando:

```
php artisan key:generate
```

5. Instalar Laravel Passport
   Ejecute el comando:

```
php artisan passport:install
```

6. Migraciones y Seeders
   Ejecute el comando

```
php artisan migrate:fresh --seed
```

7. Limpiar cache

```
php artisan optimize:clear
```

7. Configuar email en .env, ejemplo

```
MAIL_MAILER=smtp
MAIL_HOST=dev.autoamaz.com
MAIL_PORT=465
MAIL_USERNAME=user@mail.com
MAIL_PASSWORD=2012110690
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="noreply@nexoabogados.net"
MAIL_FROM_NAME="${APP_NAME}"
```

8. Ejecutar

```
php artisan queue:work --tries=4
```
