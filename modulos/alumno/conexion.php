<?php
// app/Models/AlumnoPortal.php

class AlumnoPortal
{
    private $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    public function getGrupoId($alumno_id, $ciclo_id)
    {
        $st = $this->db->prepare("SELECT grupo_id FROM alumnos_grupos WHERE alumno_id = ? AND activo = 1 AND ciclo_id = ? LIMIT 1");
        $st->execute([$alumno_id, $ciclo_id]);
        $rel = $st->fetch();
        return $rel ? $rel['grupo_id'] : null;
    }

    public function getHorario($grupo_id, $ciclo_id)
    {
        $st = $this->db->prepare(
            "SELECT h.*, m.nombre AS materia, CONCAT(d.nombre, ' ', d.apellido_p) AS docente, s.nombre AS salon
               FROM horarios h
               JOIN materias m ON m.id = h.materia_id
               JOIN docentes d ON d.id = h.docente_id
               JOIN salones  s ON s.id = h.salon_id
              WHERE h.grupo_id = ? AND h.ciclo_id = ?
              ORDER BY FIELD(h.dia, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes'), h.hora_inicio"
        );
        $st->execute([$grupo_id, $ciclo_id]);
        return $st->fetchAll();
    }

    public function getCalificaciones($alumno_id, $ciclo_id)
    {
        $st = $this->db->prepare(
            "SELECT m.nombre AS materia, m.clave, c.calificacion, c.fecha_registro
               FROM calificaciones c
               JOIN materias m ON m.id = c.materia_id
              WHERE c.alumno_id = ? AND c.ciclo_id = ? AND c.estado = 'Activo'
              ORDER BY m.nombre"
        );
        $st->execute([$alumno_id, $ciclo_id]);
        return $st->fetchAll();
    }
}
