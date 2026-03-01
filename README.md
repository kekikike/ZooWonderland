# ZooWonderland - Sistema de Gestión de Zoologico

Sistema desarrollado como proyecto del curso Tecnología Web II

## Requisitos

- PHP 8.2 o superior
- MySQL 8.0 o superior
- Composer

## Instalación

1. Clonar el repositorio
2. Ejecutar `composer install`
3. Configurar archivo `.env`
4. Importar base de datos
5. Acceder a `http://localhost/zoowonderland/public`

## Estructura del Proyecto
/
├── App/
│   ├── constrollers/
|   |   ├── AnimalController.php
|   |   ├── AreaController.php
|   |   ├── AuthController.php
|   |   ├── CompraController.php
|   |   ├── HomeController.php
|   |   ├── ReservaController.php
|   |   ├── TicketController.php
|   |   └── UsuarioController.php
│   ├── models/
|   |   ├── Administrador.php
│   │   ├── animal.php
│   │   ├── area.php
|   |   ├── Cliente.php
|   |   ├── Guia.php
│   │   ├── compra.php
|   |   ├── recorrido.php
|   |   ├── reserva.php
|   |   ├── ticket.php
│   │   └── usuario.php
│   ├── repositories/
│   │   ├── interfaces/
│   │   |   ├── AnimalRepositoryInterface.php
│   │   |   ├── AreaRepositoryInterface.php
│   │   |   ├── ReservaRepositoryInterface.php
│   │   |   └── UsuarioRepositoryInterface.php
│   │   ├── AnimalRepository.php
|   |   ├── AreaRepository.php
|   |   ├── CompraRepository.php
|   |   ├── RecorridoRepository.php
|   |   ├── ReservaRepository.php
│   │   ├── TicketRepository.php
│   │   └── UsuarioRepository.php
│   ├── services/
│   │   |── AuthService.php
|   |   ├── CompraService.php
|   |   ├── RegisterService.php
│   │   └── ReservasService.php
│   ├── views/
|   |   ├── animales/
|   |   ├── areas/
|   |   ├── auth/
|   |   ├── compras/
|   |   ├── guias/
|   |   ├── compras/
|   |   ├── guias/
|   |   ├── reservas/
|   |   ├── tickets/
|   |   └── home.php
├── config/
│   ├── constants.php
│   └── Database.php
├── core/
│   ├── config.php
│   ├── Logger.php
│   ├── session.php
│   └── Database.php
├── public/
│   ├── img/
│   ├── index.php
│   └── logout.php
├── rutas/
│   └── web.php
├── vendor/
├── .gitignore
├── composer.json
├── composer.lock
└── README.md

## Módulos

- Primera Iteración: Módulo cliente 
- Segunda Iteración: En desarrollo

## Autor

Antropomorfos - Tecnología Web II

