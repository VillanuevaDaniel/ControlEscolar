<?php
// app/Models/Inscripcion.php

class Inscripcion
{
    private $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    public function getAll()
    {
        $st = $this->db->query(
            "SELECT i.*, a.matricula, a.nombre, a.apellido_paterno, o.dia, o.hora_inicio, m.nombre AS materia
             FROM inscripciones i
             JOIN alumnos a ON i.id_alumno = a.id_alumno
             JOIN oferta_horario o ON i.id_oferta = o.id_oferta
             JOIN materias m ON o.id_materia = m.id_materia
             ORDER BY i.fecha_inscripcion DESC"
        );
        return $st->fetchAll();
    }

    public function getById($id)
    {
        $st = $this->db->prepare("SELECT * FROM inscripciones WHERE id_inscripcion = ?");
        $st->execute([$id]);
        return $st->fetch();
    }

    public function create($datos)
    {
        $st = $this->db->prepare(
            "INSERT INTO inscripciones (id_alumno, id_oferta, estado)
             VALUES (?,?,?)"
        );
        return $st->execute(array(
            $datos['id_alumno'],
            $datos['id_oferta'],
            $datos['estado'] ?? 1
        ));
    }

    public function update($id, $datos)
    {
        $st = $this->db->prepare(
            "UPDATE inscripciones SET id_alumno=?, id_oferta=?, estado=?
             WHERE id_inscripcion=?"
        );
        return $st->execute(array(
            $datos['id_alumno'],
            $datos['id_oferta'],
            $datos['estado'],
            $id
        ));
    }

    public function delete($id)
    {
        $st = $this->db->prepare("DELETE FROM inscripciones WHERE id_inscripcion = ?");
        return $st->execute([$id]);
    }

    public function getByAlumno($id_alumno)
    {
        $st = $this->db->prepare(
            "SELECT i.*, o.dia, o.hora_inicio, o.hora_fin, m.nombre AS materia, p.nombre_completo AS profesor, s.nombre AS salon
             FROM inscripciones i
             JOIN oferta_horario o ON i.id_oferta = o.id_oferta
             JOIN materias m ON o.id_materia = m.id_materia
             JOIN profesores p ON o.id_profesor = p.id_profesor
             JOIN salones s ON o.id_salon = s.id_salon
             WHERE i.id_alumno = ?
             ORDER BY o.ciclo_escolar DESC, o.dia, o.hora_inicio"
        );
        $st->execute([$id_alumno]);
        return $st->fetchAll();
    }
}
