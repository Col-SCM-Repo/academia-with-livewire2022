# Aplicación web *"Academia"*

Aplicación web  desarrollada en Laravel 8 con livewire y alpine js 


## Requisitos
* Php v7.4.26
* Composer ~v2.4.0
* Mysql v5.7.36
* Npm

## Instalación
```bash
# Limpiar memoria caché
$ php artisan cache:clear  
$ php artisan config:clear  
$ php artisan config:cache

# Correr migraciones
$ php artisan migrate:fresh

# Cargar data inicial de la DB
# Ejecutar archivo "DB INITIAL INSERTS.sql"

# Correr la app (vs code)
$ php artisan server

# Correr la app (apache)
# Subir el proyecto en (c/wamp64/www/el_proyecto)
```
