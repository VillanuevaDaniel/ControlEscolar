<?php
// app/Models/Docente.php

class Docente
{
    private $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    public function getAll()
    {
        $st = $this->db->query("SELECT id_profesor, numero_empleado, nombre_completo, curp, telefono, grado_academico, estado FROM profesores ORDER BY nombre_completo");
        return $st->fetchAll();
    }

    public function getById($id)
    {
        $st = $this->db->prepare("SELECT * FROM profesores WHERE id_profesor = ?");
        $st->execute([$id]);
        return $st->fetch();
    }

    public function create($datos, $id_usuario = null)
    {
        $st = $this->db->prepare(
            "INSERT INTO profesores (id_usuario, numero_empleado, nombre_completo, curp, telefono, domicilio, escuela_procedencia, grado_academico, estado)
             VALUES (?,?,?,?,?,?,?,?,?)"
        );
        return $st->execute(array(
            $id_usuario,
            $datos['numero_empleado'],
            $datos['nombre_completo'],
            $datos['curp'],
            $datos['telefono'],
            $datos['domicilio'],
            $datos['escuela_procedencia'],
            $datos['grado_academico'],
            $datos['estado'] ?? 1
        ));
    }

    public function update($id, $datos)
    {
        $st = $this->db->prepare(
            "UPDATE profesores SET numero_empleado=?, nombre_completo=?, curp=?, telefono=?, domicilio=?, escuela_procedencia=?, grado_academico=?, estado=?
             WHERE id_profesor=?"
        );
        return $st->execute(array(
            $datos['numero_empleado'],
            $datos['nombre_completo'],
            $datos['curp'],
            $datos['telefono'],
            $datos['domicilio'],
            $datos['escuela_procedencia'],
            $datos['grado_academico'],
            $datos['estado'],
            $id
        ));
    }

    public function delete($id)
    {
        $st = $this->db->prepare("DELETE FROM profesores WHERE id_profesor = ?");
        return $st->execute([$id]);
    }
}
