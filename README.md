# Ejercicios PHP
 - Crea un ejercicio de API REST en php y pon en práctica un proceso básico de autentificación con PHP y MySQL sin usar ningún framework ni librería.
 - Tener en cuenta el uso de POST, PUT, DELETE y GET.
 - Montar CRUD en plantilla básica de HTML y aplicar estilos (CSS)
 - Documentar
---------------------------------------------------------------------------
Dada la complejidad del ejercicio me he enfocado en intentar desarrollar mi propio framework
que proporcione una arquitectura en capas para las consultas. Modificando el código inicial por una aproximación OOP.

Por defecto está configurado para utilizar SQLITE por simplicidad pero también se agregó una interfaz que permitiria utilizar MySQL.

Simplemente instalando las dependencias de composer e iniciando el servidor desde la carpeta API.REST.PHP, recibe y retorna datos en formato JSON

```
composer install

./vendor/bin/openapi src -o openapi.yaml

php -S localhost:8000 index.php

```
