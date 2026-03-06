<?php
// app/Models/Cliente.php
declare(strict_types=1);

namespace App\Models;

class Cliente extends Usuario
{
    public ?int    $id_cliente;
    public ?string $nit;
    public ?string $tipo_cuenta;

    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->id_cliente  = isset($data['id_cliente'])  ? (int)$data['id_cliente'] : null;
        $this->nit         = $data['nit']                ?? $data['cliente_nit']    ?? null;
        $this->tipo_cuenta = $data['tipo_cuenta']        ?? $data['cliente_tipo']   ?? null;
    }

    public function comprarEntrada(): string
    {
        return "Entrada comprada";
    }
}