USE zoowonderland;

-- Cambios solicitados por el usuario
ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS estado TINYINT DEFAULT 1;
ALTER TABLE guias ADD COLUMN IF NOT EXISTS estado TINYINT DEFAULT 1;
ALTER TABLE evento_actividades DROP COLUMN IF EXISTS hora_inicio;
ALTER TABLE evento_actividades DROP COLUMN IF EXISTS hora_fin;

-- Cambios necesarios para HU-07 (Asignación de guías con horario)
ALTER TABLE guia_recorrido ADD COLUMN IF NOT EXISTS hora_inicio TIME NOT NULL DEFAULT '09:00:00';
-- Intentar borrar el índice único restrictivo que impide asignar el mismo recorrido en diferentes días/horas
-- Agregar un índice que permita flexibilidad pero evite choques en el mismo momento
ALTER TABLE guia_recorrido ADD UNIQUE INDEX IF NOT EXISTS idx_guia_fecha_hora (id_guia, fecha_asignacion, hora_inicio);
