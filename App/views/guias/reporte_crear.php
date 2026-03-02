<?php
/** @var \App\Models\Usuario $user */
/** @var int $id_gr */
/** @var string|null $mensaje */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Reporte - ZooWonderland</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --verde-selva:   #2e7d32;
            --verde-oscuro:  #1b5e20;
            --amarillo-sol:  #ffca28;
            --gris-claro:    #f8faf8;
            --oscuro:        #0d3a1f;
            --blanco:        #ffffff;
            --sombra:        0 10px 30px rgba(0,0,0,0.08);
        }
        body { font-family: 'Open Sans', sans-serif; background: var(--gris-claro); padding: 2rem; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 2.5rem; border-radius: 20px; box-shadow: var(--sombra); }
        h1 { font-family: 'Montserrat', sans-serif; color: var(--oscuro); margin-bottom: 2rem; }
        .form-group { margin-bottom: 1.5rem; }
        label { display: block; font-weight: 700; margin-bottom: 0.5rem; }
        textarea { width: 100%; padding: 1rem; border: 2px solid #eee; border-radius: 12px; font-family: inherit; font-size: 1rem; resize: vertical; min-height: 150px; }
        textarea:focus { border-color: var(--verde-selva); outline: none; }
        .btn { display: inline-block; padding: 1rem 2rem; background: var(--verde-selva); color: white; border: none; border-radius: 12px; font-weight: 700; cursor: pointer; text-decoration: none; }
        .btn:hover { background: var(--verde-oscuro); }
        .alert { padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; font-weight: 600; }
        .alert-error { background: #fee2e2; color: #d32f2f; }
        .info-box { background: #f0f4f0; padding: 1rem; border-radius: 12px; margin-bottom: 2rem; font-size: 0.9rem; border-left: 4px solid var(--verde-selva); }
    </style>
</head>
<body>

<div class="container">
    <a href="index.php?r=guias/dashboard" style="color: #666; text-decoration: none; display: inline-block; margin-bottom: 1rem;">
        <i class="fa-solid fa-arrow-left"></i> Volver al Panel
    </a>

    <h1>Registrar Reporte de Recorrido</h1>

    <div class="info-box">
        <i class="fa-solid fa-circle-info"></i>
        Documenta las actividades, incidentes o novedades ocurridas durante el recorrido.
        <strong>Este reporte no podrá ser editado una vez guardado.</strong>
    </div>

    <?php if (isset($mensaje)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <form method="POST" action="index.php?r=guias/reportes-guardar">
        <input type="hidden" name="id_guia_recorrido" value="<?= $id_gr ?>">
        
        <div class="form-group">
            <label for="observaciones">Observaciones (10 - 500 caracteres)</label>
            <textarea id="observaciones" name="observaciones" placeholder="Escribe aquí los detalles del recorrido concluido..." required><?= htmlspecialchars($_POST['observaciones'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn">
            <i class="fa-solid fa-cloud-arrow-up"></i> Guardar Reporte Final
        </button>
    </form>
</div>

</body>
</html>
