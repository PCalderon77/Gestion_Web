# 🛡️ Panel de Administración - Laboratorio de Programación 2

Este proyecto es parte del **Proyecto final** de la materia *Laboratorio de Programación 2*. Se trata de un sistema web de gestión administrativa desarrollado en PHP puro, basado en la plantilla **AdminKit**, con autenticación segura, validaciones, roles y manejo de sesiones.
<img width="683" height="565" alt="image" src="https://github.com/user-attachments/assets/754545ca-7780-4fcc-be35-dcfd1a9774a1" />

<img width="1918" height="959" alt="image" src="https://github.com/user-attachments/assets/197f8933-89da-43ab-b752-ec1dfc1eaf22" />


---

## 📁 Estructura del Proyecto

├── assets/ # Imágenes y multimedia
├── css/ # Estilos del template AdminKit
├── js/ # Scripts JS del template
├── vistas/ # Páginas principales del panel (dashboard, login, etc.)
├── funciones/ # Archivos de conexión, login, validaciones, logout
├── includes/ # Componentes reutilizables: header.php, footer.php, etc.
└── sql/ # Script con estructura de la base de datos (opcional)

## 🔐 Funcionalidades

- Inicio de sesión con **verificación de contraseña encriptada** (`password_hash` y `password_verify`).
- Control de acceso por **roles**: solo usuarios autorizados pueden acceder al panel.
- Módulos dinámicos:
  - Listado de usuarios
  - Listado y carga de empresas
  - Listado de países (solo visual)
- Validaciones en cliente (HTML5) y servidor (PHP).
- Seguridad contra inyección SQL con **sentencias preparadas** (`prepare` + `bind_param`).
- Manejo de sesiones y logout.
- Notificaciones reutilizables y listas para ser dinámicas.
- Gestion de proyectos.
- Gestion clientes.

## 🗃️ Estructura de la Base de Datos

Nombre: `parcial_lp2`

### Tabla: `usuarios`
| Campo     | Tipo           | Descripción                        |
|-----------|----------------|------------------------------------|
| Id        | INT AUTO_INCREMENT PRIMARY KEY | Identificador único |
| Nombre    | VARCHAR(50)    | Nombre del usuario                 |
| Apellido  | VARCHAR(50)    | Apellido del usuario               |
| Email     | VARCHAR(100) UNIQUE | Correo electrónico        |
| Password  | VARCHAR(255)   | Contraseña encriptada              |
| Foto      | VARCHAR(255)   | Ruta o nombre de imagen            |
| IdRol     | INT            | Clave foránea a `roles(Id)`       |

---

### Tabla: `roles`
| Campo        | Tipo         | Descripción              |
|--------------|--------------|--------------------------|
| Id           | INT AUTO_INCREMENT PRIMARY KEY | ID único del rol |
| Denominacion | VARCHAR(50)  | Nombre del rol (Ej: Administrador,Analista funcional, Programador y lider) |

---

### Tabla: `proyectos`
| Campo        | Tipo         | Descripción                  |
|--------------|--------------|------------------------------|
| Id           | INT AUTO_INCREMENT PRIMARY KEY | ID del proyecto |
| Denominacion | VARCHAR(100) | Nombre del proyecto          |
| fechaCarga   | DATE        | Fecha en que se cargó el proyecto     |
| Id_Estado    | INT          | FK a `estados(Id)`           |
| Id_Empresa   | INT          | FK a `empresas(Id)`          |
| Id_Lider     | INT          | FK a `usuarios(Id)`          |
| Id_Pais      | INT          | FK a `paises(Id)`          |

---

### Tabla: `empresas`
| Campo        | Tipo         | Descripción           |
|--------------|--------------|-----------------------|
| Id           | INT AUTO_INCREMENT PRIMARY KEY | ID de la empresa |
| Denominacion | VARCHAR(100) | Nombre de la empresa  |
| fechaCarga   | DATE        | Fecha en que se cargó la empresa    |
| Id_Pais      | INT          | FK a `paises(Id)`     |

---

### Tabla: `estados`
| Campo        | Tipo         | Descripción               |
|--------------|--------------|---------------------------|
| Id           | INT AUTO_INCREMENT PRIMARY KEY | Estado ID  |
| Denominacion | VARCHAR(50)  | Nombre del estado (Ej: Activo, Finalizado) |

---

### Tabla: `paises`
| Campo        | Tipo         | Descripción               |
|--------------|--------------|---------------------------|
| Id           | INT AUTO_INCREMENT PRIMARY KEY | ID del país |
| Denominacion | VARCHAR(100) | Nombre del país           |
| CodigoImg    | VARCHAR(100) | codigo de la imagen(Ej: URU, ARG)          |

---

### 🔗 Relaciones

- `usuarios.IdRol` → `roles.Id`
- `proyectos.Estado` → `estados.Id`
- `proyectos.EmpresaId` → `empresas.Id`
- `empresas.PaisId` → `paises.Id`

---

¿Querés que te genere también el **script SQL completo (`.sql`)** para que puedas importar las tablas con estructura y relaciones directamente desde PHPMyAdmin o consola?

---

## 🚀 Instalación y Uso

1. Cloná el repositorio o descargalo como ZIP.
2. Copialo en tu carpeta de XAMPP/htdocs
3. Ejecuta Apache y Mysql en XAMP
