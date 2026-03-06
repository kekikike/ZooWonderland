<?php
// app/Models/Guia.php
declare(strict_types=1);

namespace App\Models;

class Guia extends Usuario
{
    public ?int    $id_guia;
    public ?string $horarios;
    public ?string $dias_trabajo;

    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->id_guia      = isset($data['id_guia'])      ? (int)$data['id_guia'] : null;
        $this->horarios     = $data['horarios']             ?? null;
        $this->dias_trabajo = $data['dias_trabajo']         ?? $data['guia_dias']   ?? null;
    }

    /**
     * Devuelve el horario de entrada (primera parte antes del ' - ')
     */
    public function getHoraEntrada(): ?string
    {
        if (!$this->horarios) return null;
        return trim(explode('-', $this->horarios)[0] ?? '');
    }

    /**
     * Devuelve el horario de salida (segunda parte después del ' - ')
     */
    public function getHoraSalida(): ?string
    {
        if (!$this->horarios) return null;
        $partes = explode('-', $this->horarios);
        return trim($partes[1] ?? '');
    }
}