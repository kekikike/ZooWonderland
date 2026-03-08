<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Asignación - ZooWonderland</title>
    <!-- Premium Fonts & Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --selva-dark: #1B5E20;
            --selva-med: #2E7D32;
            --selva-light: #4CAF50;
            --jungle-gold: #FFD600;
            --error: #D32F2F;
            --success: #388E3C;
            --blanco: #ffffff;
            --glass: rgba(255, 255, 255, 0.9);
            --shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        body { 
            font-family: 'Outfit', sans-serif; 
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            margin: 0; 
            min-height: 100vh;
        }

        header { 
            background: var(--selva-dark); 
            color: white; 
            padding: 1.2rem 5%; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header .logo { 
            font-size: 1.8rem; 
            font-weight: 700; 
            text-decoration: none; 
            color: white; 
            display: flex;
            align-items: center;
            gap: 10px;
        }

        main { 
            max-width: 700px; 
            margin: 4rem auto; 
            padding: 0 20px;
        }

        .form-card { 
            background: var(--glass);
            backdrop-filter: blur(10px);
            padding: 3rem; 
            border-radius: 30px; 
            box-shadow: var(--shadow); 
            border: 1px solid rgba(255,255,255,0.3);
            position: relative;
            overflow: hidden;
        }

        /* Decorative Jungle Element */
        .form-card::before {
            content: "\f06c";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            top: -20px;
            right: -20px;
            font-size: 120px;
            color: rgba(46, 125, 50, 0.05);
            pointer-events: none;
        }

        h1 { 
            color: var(--selva-dark); 
            margin-bottom: 2.5rem; 
            text-align: center; 
            font-size: 2.2rem;
            letter-spacing: -1px;
        }

        .form-group { margin-bottom: 1.8rem; position: relative; }
        
        label { 
            display: block; 
            margin-bottom: 0.6rem; 
            color: #333; 
            font-weight: 600; 
            font-size: 0.95rem;
            transition: 0.3s;
        }

        input, select { 
            width: 100%; 
            padding: 1rem; 
            border: 2px solid #e0e0e0; 
            border-radius: 15px; 
            font-size: 1rem; 
            box-sizing: border-box; 
            font-family: inherit;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
        }

        input:focus, select:focus { 
            border-color: var(--selva-med); 
            outline: none; 
            box-shadow: 0 0 0 4px rgba(46, 125, 50, 0.15);
            transform: translateY(-2px);
        }

        /* Status Styles */
        .field-invalid { border-color: var(--error) !important; background-color: #fff8f8; }
        .field-valid { border-color: var(--success) !important; background-color: #f8fff8; }

        .validation-msg {
            font-size: 0.8rem;
            margin-top: 5px;
            display: none;
            align-items: center;
            gap: 5px;
        }
        .validation-msg.error { color: var(--error); display: flex; }
        .validation-msg.ok { color: var(--success); display: flex; }

        .btn-submit { 
            background: linear-gradient(135deg, var(--selva-med) 0%, var(--selva-dark) 100%);
            color: white; 
            border: none; 
            padding: 1.2rem; 
            width: 100%; 
            border-radius: 15px;
            font-size: 1.1rem; 
            font-weight: 700; 
            cursor: pointer; 
            transition: 0.4s;
            margin-top: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(27, 94, 32, 0.3);
        }

        .btn-submit:hover { 
            transform: translateY(-3px); 
            box-shadow: 0 8px 25px rgba(27, 94, 32, 0.4);
            filter: brightness(1.1);
        }

        .btn-submit:active { transform: translateY(0); }

        .btn-back { 
            display: block; 
            text-align: center; 
            margin-top: 2rem; 
            color: #666; 
            text-decoration: none; 
            font-size: 0.95rem; 
            font-weight: 600;
            transition: 0.3s;
        }
        .btn-back:hover { color: var(--selva-dark); }

        /* Guide Info Panel */
        .guide-status-panel {
            background: #f1f8e9;
            border-left: 4px solid var(--selva-med);
            padding: 1rem;
            border-radius: 10px;
            margin-top: 10px;
            display: none;
        }
        .guide-status-panel span { font-weight: 700; color: var(--selva-dark); }

        /* Real-time Toast Container Mockup */
        .live-check-box {
            background: #333;
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
            display: none;
            z-index: 1000;
            font-weight: 600;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <header>
        <a href="index.php?r=admin/dashboard" class="logo">
            <i class="fas fa-tree"></i> ZooWonderland
        </a>
    </header>

    <main>
        <div class="form-card">
            <h1><i class="fas fa-calendar-check"></i> Programar Recorrido</h1>

            <form action="index.php?r=admin/asignaciones/guardar" method="POST" id="assignForm">
                
                <div class="form-group">
                    <label for="id_guia"><i class="fas fa-user-tie"></i> Guía Especialista</label>
                    <select name="id_guia" id="id_guia" required>
                        <option value="">-- Selecciona un profesional --</option>
                        <?php foreach ($guias as $g): ?>
                            <option value="<?php echo $g['id_guia']; ?>" 
                                    data-horario="<?php echo htmlspecialchars($g['horarios']); ?>"
                                    data-dias="<?php echo htmlspecialchars($g['dias_trabajo']); ?>"
                                    <?php echo (isset($old['id_guia']) && $old['id_guia'] == $g['id_guia']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($g['nombre_completo']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div id="guide-panel" class="guide-status-panel">
                        <i class="fas fa-info-circle"></i> Disponibilidad: <span id="info-texto-js"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="id_recorrido"><i class="fas fa-compass"></i> Tipo de Recorrido</label>
                    <select name="id_recorrido" id="id_recorrido" required>
                        <option value="">-- Elige la experiencia --</option>
                        <?php foreach ($recorridos as $r): ?>
                            <option value="<?php echo $r['id_recorrido']; ?>" 
                                    data-duracion="<?php echo $r['duracion']; ?>"
                                    <?php echo (isset($old['id_recorrido']) && $old['id_recorrido'] == $r['id_recorrido']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($r['nombre']); ?> (<?php echo $r['duracion']; ?> min)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="fecha"><i class="fas fa-calendar-alt"></i> Fecha del Tour</label>
                    <input type="date" name="fecha" id="fecha" min="<?php echo date('Y-m-d'); ?>" 
                           value="<?php echo htmlspecialchars($old['fecha'] ?? ''); ?>" required>
                    <div id="msg-fecha" class="validation-msg"></div>
                </div>

                <div class="form-group">
                    <label for="hora"><i class="fas fa-clock"></i> Hora de Encuentro</label>
                    <input type="time" name="hora" id="hora" 
                           value="<?php echo htmlspecialchars($old['hora'] ?? ''); ?>" required>
                    <div id="msg-hora" class="validation-msg"></div>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-save"></i> Confirmar Asignación
                </button>
            </form>

            <a href="index.php?r=admin/asignaciones" class="btn-back">
                <i class="fas fa-chevron-left"></i> Regresar al panel de control
            </a>
        </div>
    </main>

    <div id="live-toast" class="live-check-box"></div>

    <script>
        const form = document.getElementById('assignForm');
        const selectGuia = document.getElementById('id_guia');
        const selectRecorrido = document.getElementById('id_recorrido');
        const inputFecha = document.getElementById('fecha');
        const inputHora = document.getElementById('hora');
        const toast = document.getElementById('live-toast');
        const btnSubmit = document.getElementById('submitBtn');

        const diasSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

        function showToast(msg, isError = true) {
            toast.textContent = msg;
            toast.style.display = 'block';
            toast.style.backgroundColor = isError ? '#D32F2F' : '#388E3C';
            setTimeout(() => { toast.style.display = 'none'; }, 3000);
        }

        function validateLive() {
            let isValid = true;
            let errors = [];

            // Reset styles
            [selectGuia, selectRecorrido, inputFecha, inputHora].forEach(el => el.classList.remove('field-invalid', 'field-valid'));
            document.querySelectorAll('.validation-msg').forEach(m => m.style.display = 'none');

            const guiaOpt = selectGuia.options[selectGuia.selectedIndex];
            const recOpt = selectRecorrido.options[selectRecorrido.selectedIndex];
            
            // 1. Check Guide Availability (Day)
            if (guiaOpt.value && inputFecha.value) {
                const dateObj = new Date(inputFecha.value + 'T00:00:00');
                const dayName = diasSemana[dateObj.getDay()];
                const allowedDays = guiaOpt.getAttribute('data-dias');

                let works = false;
                if (allowedDays.includes('Todos') || allowedDays.includes(dayName)) {
                    works = true;
                } else if (allowedDays.includes(' a ')) {
                    const daysArr = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                    const range = allowedDays.split(' a ');
                    const startIdx = daysArr.indexOf(range[0]);
                    const endIdx = daysArr.indexOf(range[1]);
                    const currentIdx = daysArr.indexOf(dayName);
                    
                    if (startIdx <= endIdx) {
                        works = (currentIdx >= startIdx && currentIdx <= endIdx);
                    } else {
                        works = (currentIdx >= startIdx || currentIdx <= endIdx);
                    }
                }

                if (!works) {
                    inputFecha.classList.add('field-invalid');
                    errors.push(`El guía no trabaja el día ${dayName}`);
                    isValid = false;
                } else {
                    inputFecha.classList.add('field-valid');
                }
            }

            // 2. Check Time Range
            if (guiaOpt.value && recOpt.value && inputHora.value) {
                const schedule = guiaOpt.getAttribute('data-horario'); // "09:00 - 15:00"
                const duration = parseInt(recOpt.getAttribute('data-duracion'));
                
                const matches = schedule.match(/(\d{1,2}:\d{2})\s*-\s*(\d{1,2}:\d{2})/);
                if (matches) {
                    const hEntrada = matches[1];
                    const hSalida = matches[2];
                    
                    // Calculation
                    const startTime = inputHora.value;
                    const startDate = new Date(`2000-01-01T${startTime}`);
                    const endDate = new Date(startDate.getTime() + duration * 60000);
                    const endTime = endDate.toTimeString().substring(0, 5);

                    if (startTime < hEntrada) {
                        inputHora.classList.add('field-invalid');
                        errors.push(`Inicia antes de la entrada del guía (${hEntrada})`);
                        isValid = false;
                    } else if (endTime > hSalida) {
                        inputHora.classList.add('field-invalid');
                        errors.push(`Termina a las ${endTime} (Guía sale a las ${hSalida})`);
                        isValid = false;
                    } else {
                        inputHora.classList.add('field-valid');
                    }
                }
            }

            if (errors.length > 0) {
                // We show the first error in the toast if it was triggered by a change
                // but we also have the visual feedback on inputs
                btnSubmit.style.opacity = '0.7';
            } else {
                btnSubmit.style.opacity = '1';
            }

            return { isValid, errors };
        }

        function updateGuideInfo() {
            const panel = document.getElementById('guide-panel');
            const infoTexto = document.getElementById('info-texto-js');
            const selectedOption = selectGuia.options[selectGuia.selectedIndex];
            
            if (selectedOption.value) {
                const horario = selectedOption.getAttribute('data-horario');
                const dias = selectedOption.getAttribute('data-dias');
                infoTexto.textContent = `${dias} (${horario})`;
                panel.style.display = 'block';
            } else {
                panel.style.display = 'none';
            }
            validateLive();
        }

        // Event Listeners
        selectGuia.addEventListener('change', updateGuideInfo);
        selectRecorrido.addEventListener('change', validateLive);
        inputFecha.addEventListener('change', validateLive);
        inputHora.addEventListener('input', validateLive);

        form.addEventListener('submit', function(e) {
            const result = validateLive();
            if (!result.isValid) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Datos Inválidos',
                    text: result.errors[0],
                    confirmButtonColor: '#1B5E20',
                    background: '#f1f8e9'
                });
            } else {
                // Show loading state
                btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
            }
        });

        // Initialize from session data / old data
        window.onload = function() {
            updateGuideInfo();
            
            // Server-side response handling
            <?php if (!empty($mensaje)): ?>
                Swal.fire({
                    icon: '<?php echo $tipoMsg === "ok" ? "success" : "error"; ?>',
                    title: '<?php echo $tipoMsg === "ok" ? "¡Éxito!" : "Aviso"; ?>',
                    text: '<?php echo $mensaje; ?>',
                    confirmButtonColor: '#1B5E20',
                    timer: 4000
                });
            <?php endif; ?>
        };
    </script>
</body>
</html>
