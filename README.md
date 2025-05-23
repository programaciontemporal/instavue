# **InstaVue: Tu Red Social de Fotos y Conexiones**

¡Bienvenido a InstaVue\! Esta es una aplicación web de red social construida con Laravel y Vue.js, diseñada para que los usuarios puedan compartir momentos a través de fotos, conectar con amigos y descubrir contenido nuevo. Inspirada en las plataformas de redes sociales populares, InstaVue ofrece una experiencia intuitiva para interactuar con el mundo digital.

## **Funcionalidades Principales**

InstaVue viene equipada con las siguientes características clave para una experiencia de red social completa:

* **Autenticación de Usuarios**: Regístrate, inicia y cierra sesión de forma segura.  
* **Perfiles de Usuario Personalizables**: Cada usuario tiene su propio perfil donde puede:  
  * Ver su información personal, biografía y foto de perfil (avatar).  
  * Actualizar su información de perfil y cambiar su avatar.  
  * Cambiar su contraseña.  
  * Eliminar su cuenta.  
* **Interacción Social**:  
  * **Seguir/Dejar de Seguir**: Conecta con otros usuarios siguiéndolos o dejando de seguirlos. Los contadores de seguidores y seguidos se actualizan en tiempo real.  
  * **Publicaciones (Posts)**:  
    * Crea y comparte tus propias publicaciones con imágenes y descripciones.  
    * Edita o elimina tus publicaciones existentes.  
    * Visualiza publicaciones en un feed principal personalizado (de los usuarios que sigues).  
    * Explora una gran variedad de publicaciones de todos los usuarios en la página de exploración.  
    * Accede a una vista detallada de cada publicación para ver todos sus comentarios y "Me Gusta".  
  * **"Me Gusta" (Likes)**: Indica que te gusta una publicación y ve cuántos "Me Gusta" tiene.  
  * **Comentarios**: Deja tus pensamientos y reacciones en las publicaciones de otros usuarios.  
  * **Publicaciones Guardadas**: Guarda tus publicaciones favoritas para verlas más tarde en una sección dedicada.  
* **Búsqueda Avanzada**: Encuentra fácilmente a otros usuarios o publicaciones específicas utilizando la función de búsqueda.  
* **Paginación**: Navega cómodamente por grandes volúmenes de contenido (publicaciones, resultados de búsqueda) gracias a la paginación integrada.

## **Cómo Empezar**

Sigue estos pasos para configurar y ejecutar el proyecto InstaVue en tu entorno local.

### **Requisitos Previos**

Asegúrate de tener instalado lo siguiente en tu sistema:

* **PHP** (versión 8.2 o superior)  
* **Composer**  
* **Node.js** (versión 18 o superior) y **npm**  
* **Base de Datos** (MySQL, PostgreSQL, SQLite, etc. \- MySQL es lo más común para Laravel)  
* **Git**

### **Pasos de Instalación**

1. Clonar el Repositorio:  
   Abre tu terminal y clona el repositorio de GitHub:  
   git clone \<URL\_DEL\_REPOSITORIO\>  
   cd instavue

   *(Reemplaza \<URL\_DEL\_REPOSITORIO\> con la URL real de tu repositorio GitHub)*  
2. Instalar Dependencias de Composer:  
   Instala las dependencias de PHP:  
   composer install

3. Configurar el Archivo .env:  
   Copia el archivo de configuración de ejemplo y crea tu propio archivo .env:  
   cp .env.example .env

   Abre el archivo .env con tu editor de texto y configura las variables de entorno, especialmente las de la base de datos y la APP\_URL:  
   APP\_NAME="InstaVue"  
   APP\_ENV=local  
   APP\_KEY=  
   APP\_DEBUG=true  
   APP\_URL=http://instavue.test \# O la URL de tu entorno local (ej. http://localhost:8000)

   DB\_CONNECTION=mysql  
   DB\_HOST=127.0.0.1  
   DB\_PORT=3306  
   DB\_DATABASE=instavue\_db \# Tu nombre de base de datos  
   DB\_USERNAME=root \# Tu usuario de base de datos  
   DB\_PASSWORD= \# Tu contraseña de base de datos

   *(Reemplaza los valores de la base de datos con los tuyos.)*  
4. Generar la Clave de Aplicación:  
   Laravel necesita una clave de aplicación para la seguridad:  
   php artisan key:generate

5. Configurar el Enlace Simbólico de Almacenamiento:  
   Para que las imágenes (avatares, publicaciones) sean accesibles públicamente, crea un enlace simbólico:  
   php artisan storage:link

6. Ejecutar Migraciones de Base de Datos:  
   Crea las tablas de la base de datos y, opcionalmente, rellénalas con datos de prueba:  
   php artisan migrate  
   \# Si quieres datos de prueba (seeders):  
   \# php artisan migrate \--seed

7. Instalar Dependencias de Node.js:  
   Instala las dependencias de JavaScript:  
   npm install

8. Compilar los Assets de Frontend:  
   Compila los archivos de JavaScript y CSS de Vue.js:  
   npm run build

   *(Para desarrollo, puedes usar npm run dev para que se recompilen automáticamente los cambios.)*  
9. Iniciar el Servidor de Desarrollo de Laravel:  
   Inicia el servidor local de Laravel:  
   php artisan serve

   Tu aplicación debería estar ahora accesible en la APP\_URL que configuraste en tu archivo .env (ej. http://instavue.test o http://localhost:8000).

¡Listo\! Ya puedes empezar a usar InstaVue.