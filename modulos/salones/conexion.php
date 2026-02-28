<?php
// app/Models/Salon.php

class Salon
{
    private $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    public function getAll()
    {
        $st = $this->db->query("SELECT * FROM salones ORDER BY nombre");
        return $st->fetchAll();
    }

    public function getById($id)
    {
        $st = $this->db->prepare("SELECT * FROM salones WHERE id_salon = ?");
        $st->execute([$id]);
        return $st->fetch();
    }

    public function create($datos)
    {
        $st = $this->db->prepare(
            "INSERT INTO salones (nombre, capacidad)
             VALUES (?,?)"
        );
        return $st->execute(array(
            $datos['nombre'],
            $datos['capacidad']
        ));
    }

    public function update($id, $datos)
    {
        $st = $this->db->prepare(
            "UPDATE salones SET nombre=?, capacidad=?
             WHERE id_salon=?"
        );
        return $st->execute(array(
            $datos['nombre'],
            $datos['capacidad'],
            $id
        ));
    }

    public function delete($id)
    {
        $st = $this->db->prepare("DELETE FROM salones WHERE id_salon = ?");
        return $st->execute([$id]);
    }
}
