<?php
// app/Models/Usuario.php
declare(strict_types=1);

namespace App\Models;

class Usuario {
    public int $id;
    public string $nombre_usuario;
    public string $email;
    public string $nombre_completo;  // o construye con nombre1 + apellido1 etc.
    public string $rol;              // 'cliente', 'admin', 'guia', etc.
    // otros campos...

    public function __construct(array $data) {
        $this->id              = (int) ($data['id'] ?? 0);
        $this->nombre_usuario  = $data['nombre_usuario'] ?? '';
        $this->email           = $data['email'] ?? '';
        $this->nombre_completo = $data['nombre_completo'] ?? $data['nombre_usuario'];
        $this->rol             = $data['rol'] ?? 'cliente';
    }

    public function getNombreParaMostrar(): string {
        return $this->nombre_completo ?: $this->nombre_usuario;
    }
}