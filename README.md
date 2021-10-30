# hielo uno

Este es un sistema informático en cual permite automatizar  los procesos de la empresa hielo uno.

## Requisitos
- PHP 7.4
- MySQL
- Apache
- Composer
- git

## Instalación

1. clonar el repositorio del proyecto

```bash
git clone https://github.com/nottechguy/hielo-uno.git

```

2. Ejecutar el archivo `install.sh` que se encuentra en el directorio bin/

```bash
cd hielo-uno/ && sh bin/install.sh
```

## Estructura

El proyecto está basado en una versión modificada del framework codeigniter, este divide en múltiples directorios.

`app/` Este directorio contiene la aplicación. Albergará el modelo MVC, así como las configuraciones, los servicios utilizados.

- `config/` Contiene la configuración general del proyecto.
- `controllers/` Contiene los controladores.
- `models/` Contiene los modelos.
- `views/` Contiene las vistas.

`bin/` Contiene scripts los cuales automatizan ciertas tareas.

`cache/` Contiene la versión en caché de la configuración y la versión en caché de las acciones y plantillas del proyecto. 

`data/` Este directorio proporciona un lugar para almacenar datos de aplicaciones que son volátiles y posiblemente temporales. La alteración de los datos en este directorio puede hacer que la aplicación falle. Además, la información de este directorio puede o no estar comprometida con un repositorio de subversión. Ejemplos de cosas en este directorio son archivos de sesión, archivos de caché, bases de datos sqlite, registros e índices

`doc/` Incluye toda la documentación del proyecto.

`log/` Contiene archivos de registro que son producidos por el sistema.

`public/` Es el directorio raíz del servidor web. Los únicos archivos accesibles desde Internet están en este directorio.

`src/` Contiene el código fuente modificado del framework.

`test/` Este directorio contiene pruebas de aplicaciones. Estos podrían ser pruebas PHPUnit escritas a mano o pruebas basadas en algún otro framework.

`vendor` Contiene librerías de terceros.


## Documentación

- [Guía de código](doc/CODE_GUIDE.md)