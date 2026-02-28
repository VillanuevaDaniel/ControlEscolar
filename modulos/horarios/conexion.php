<?php
// app/Models/Horario.php

class Horario
{
    private $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    public function getAll()
    {
        $st = $this->db->query(
            "SELECT o.*, m.nombre AS materia, p.nombre_completo AS profesor, s.nombre AS salon, g.grado, g.seccion
             FROM oferta_horario o
             JOIN materias m ON o.id_materia = m.id_materia
             JOIN profesores p ON o.id_profesor = p.id_profesor
             JOIN salones s ON o.id_salon = s.id_salon
             LEFT JOIN grupos g ON o.id_grupo = g.id_grupo
             ORDER BY o.estado DESC, o.ciclo_escolar DESC, o.dia, o.hora_inicio"
        );
        return $st->fetchAll();
    }

    public function getById($id)
    {
        $st = $this->db->prepare("SELECT * FROM oferta_horario WHERE id_oferta = ?");
        $st->execute([$id]);
        return $st->fetch();
    }

    public function create($datos)
    {
        // Regla de Negocio: cupo_maximo el menor entre materia y salon
        $stCupo = $this->db->prepare(
            "SELECT LEAST((SELECT cupo_maximo FROM materias WHERE id_materia=?), (SELECT capacidad FROM salones WHERE id_salon=?)) AS cupo_maximo"
        );
        $stCupo->execute([$datos['id_materia'], $datos['id_salon']]);
        $cupo_maximo = $stCupo->fetchColumn();

        $st = $this->db->prepare(
            "INSERT INTO oferta_horario (id_materia, id_profesor, id_salon, id_grupo, dia, hora_inicio, hora_fin, cupo_maximo, ciclo_escolar, estado)
             VALUES (?,?,?,?,?,?,?,?,?,?)"
        );
        return $st->execute(array(
            $datos['id_materia'],
            $datos['id_profesor'],
            $datos['id_salon'],
            $datos['id_grupo'] ?: null,
            $datos['dia'],
            $datos['hora_inicio'],
            $datos['hora_fin'],
            $cupo_maximo,
            $datos['ciclo_escolar'],
            $datos['estado'] ?? 1
        ));
    }

    public function update($id, $datos)
    {
        // Regla de Negocio: cupo_maximo el menor entre materia y salon
        $stCupo = $this->db->prepare(
            "SELECT LEAST((SELECT cupo_maximo FROM materias WHERE id_materia=?), (SELECT capacidad FROM salones WHERE id_salon=?)) AS cupo_maximo"
        );
        $stCupo->execute([$datos['id_materia'], $datos['id_salon']]);
        $cupo_maximo = $stCupo->fetchColumn();

        $st = $this->db->prepare(
            "UPDATE oferta_horario SET id_materia=?, id_profesor=?, id_salon=?, id_grupo=?, dia=?, hora_inicio=?, hora_fin=?, cupo_maximo=?, ciclo_escolar=?, estado=?
             WHERE id_oferta=?"
        );
        return $st->execute(array(
            $datos['id_materia'],
            $datos['id_profesor'],
            $datos['id_salon'],
            $datos['id_grupo'] ?: null,
            $datos['dia'],
            $datos['hora_inicio'],
            $datos['hora_fin'],
            $cupo_maximo,
            $datos['ciclo_escolar'],
            $datos['estado'],
            $id
        ));
    }

    public function delete($id)
    {
        $st = $this->db->prepare("DELETE FROM oferta_horario WHERE id_oferta = ?");
        return $st->execute([$id]);
    }
}
