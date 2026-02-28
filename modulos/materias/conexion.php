<?php
// app/Models/Materia.php

class Materia
{
    private $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    public function getAll()
    {
        $st = $this->db->query("SELECT * FROM materias ORDER BY nombre");
        return $st->fetchAll();
    }

    public function getById($id)
    {
        $st = $this->db->prepare("SELECT * FROM materias WHERE id_materia = ?");
        $st->execute([$id]);
        return $st->fetch();
    }

    public function create($datos)
    {
        $st = $this->db->prepare(
            "INSERT INTO materias (nombre, cupo_maximo, vigencia_inicio, vigencia_fin)
             VALUES (?,?,?,?)"
        );
        $st->execute(array(
            $datos['nombre'],
            $datos['cupo_maximo'],
            $datos['vigencia_inicio'],
            $datos['vigencia_fin']
        ));
        return $this->db->lastInsertId();
    }

    public function update($id, $datos)
    {
        $st = $this->db->prepare(
            "UPDATE materias SET nombre=?, cupo_maximo=?, vigencia_inicio=?, vigencia_fin=?
             WHERE id_materia=?"
        );
        return $st->execute(array(
            $datos['nombre'],
            $datos['cupo_maximo'],
            $datos['vigencia_inicio'],
            $datos['vigencia_fin'],
            $id
        ));
    }

    public function delete($id)
    {
        $st = $this->db->prepare("DELETE FROM materias WHERE id_materia = ?");
        return $st->execute([$id]);
    }

    // Relación con profesores
    public function getProfesores($id_materia)
    {
        $st = $this->db->prepare(
            "SELECT p.id_profesor, p.nombre_completo 
             FROM profesor_materia pm 
             JOIN profesores p ON p.id_profesor = pm.id_profesor 
             WHERE pm.id_materia = ?"
        );
        $st->execute([$id_materia]);
        return $st->fetchAll();
    }

    public function addProfesor($id_materia, $id_profesor)
    {
        $st = $this->db->prepare("INSERT IGNORE INTO profesor_materia (id_profesor, id_materia) VALUES (?, ?)");
        return $st->execute([$id_profesor, $id_materia]);
    }

    public function clearProfesores($id_materia)
    {
        $st = $this->db->prepare("DELETE FROM profesor_materia WHERE id_materia = ?");
        return $st->execute([$id_materia]);
    }
}
