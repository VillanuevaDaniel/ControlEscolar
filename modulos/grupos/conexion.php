<?php
class Grupo
{
    private $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    public function getAll()
    {
        $st = $this->db->query("SELECT * FROM grupos ORDER BY ciclo_escolar DESC, grado, seccion");
        return $st->fetchAll();
    }

    public function getById($id)
    {
        $st = $this->db->prepare("SELECT * FROM grupos WHERE id_grupo = ?");
        $st->execute([$id]);
        return $st->fetch();
    }

    public function create($datos)
    {
        $st = $this->db->prepare(
            "INSERT INTO grupos (grado, seccion, ciclo_escolar, turno)
             VALUES (?,?,?,?)"
        );
        $st->execute(array(
            $datos['grado'],
            $datos['seccion'],
            $datos['ciclo_escolar'],
            $datos['turno']
        ));
        return $this->db->lastInsertId();
    }

    public function update($id, $datos)
    {
        $st = $this->db->prepare(
            "UPDATE grupos SET grado=?, seccion=?, ciclo_escolar=?, turno=?
             WHERE id_grupo=?"
        );
        return $st->execute(array(
            $datos['grado'],
            $datos['seccion'],
            $datos['ciclo_escolar'],
            $datos['turno'],
            $id
        ));
    }

    public function delete($id)
    {
        $st = $this->db->prepare("DELETE FROM grupos WHERE id_grupo = ?");
        return $st->execute([$id]);
    }
}
