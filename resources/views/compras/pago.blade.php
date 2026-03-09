{{-- resources/views/compras/pago.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pagar Compra - Zoo Wonderland</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); text-align: center; }
        h1 { color: #2d5016; }
        .qr { margin: 20px 0; }
        a.button { background: #2d5016; color: #fff; padding: 10px 20px; border: none; border-radius: 4px; text-decoration: none; display: inline-block; }
        a.button:hover { background: #234010; }
    </style>
</head>
<body>
<div class="container">
    <h1>Completa tu pago</h1>

    @if (!empty($datos))
        <p>Escanea el código QR para pagar <strong>Bs. {{ number_format($datos['monto_total'], 2) }}</strong></p>
        <div class="qr">
            <img src="{{ asset('https://media.informabtl.com/wp-content/uploads/2011/07/qrcode_generico.png') }}" alt="QR de pago" width="250">
        </div>
        <p>Recorrido: {{ $datos['recorrido'] }}</p>
        <p>Fecha: {{ $datos['fecha'] }} &nbsp; Hora: {{ $datos['hora'] }}</p>
        <a class="button" href="/compras/historial">Ver historial</a>
    @else
        <p>No hay datos de compra disponibles.</p>
        <a class="button" href="/">Volver al inicio</a>
    @endif
</div>
</body>
</html>