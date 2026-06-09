# CETI-Wallet

Sistema web de administración de gastos personales desarrollado como proyecto final para la materia de Despliegue de Aplicaciones y Servicios.

## Características

- Registro de usuarios
- Inicio de sesión seguro
- Gestión de ingresos
- Gestión de gastos
- Dashboard financiero
- Historial de movimientos
- Base de datos MySQL
- Despliegue en Hostinger
- Control de versiones con Git y GitHub

## Tecnologías Utilizadas

### Frontend

- HTML5
- CSS3
- JavaScript

### Backend

- PHP 8

### Base de Datos

- MySQL

### Infraestructura

- Hostinger
- Git
- GitHub

## Instalación

### Clonar repositorio

```bash
git clone https://github.com/usuario/finceti.git
```

### Crear base de datos

Importar:

```sql
schema.sql
```

### Configurar conexión

Editar:

```php
config/database.php
```

Con las credenciales correspondientes.

### Ejecutar

Copiar el proyecto dentro del servidor web.

Por ejemplo:

```text
htdocs/
```

o

```text
public_html/
```

## Arquitectura

Usuario → Navegador → Aplicación PHP → Base de Datos MySQL

## Seguridad

- Contraseñas cifradas mediante password_hash()
- Validación de sesiones
- Protección de rutas mediante autenticación

## Autor

Devin Alonso
Jose Antonio
Angel Vaca

## Licencia

Proyecto académico desarrollado para fines educativos.