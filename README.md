<h1><strong>Instalación y uso</strong></h1>

1. Clona el repositorio desde GitHub
2. Accede al directorio del proyecto: cd nombre-del-proyecto
3. Copia el archivo .env.example a .env y configura las variables de entorno, como la conexión a la base de datos y la clave de la aplicación
4. Instala las dependencias PHP mediante Composer: composer install
5. Ejecuta las migraciones de la base de datos para crear las tablas necesarias: php artisan migrate
6. Inicia el servidor de desarrollo: php artisan serve

<h3><strong>Uso</strong></h3>
<h5><strong>Básico</strong></h5>

1. Para realizar importaciones en lote basado en el archivo excel proporcionado, ejecutar comando: php artisan dispatch:imports

Para más información consultar archivo de la carpeta /docs que se encuentra en la raiz del proyecto



