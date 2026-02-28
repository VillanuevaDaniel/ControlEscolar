<?php
// app/Controllers/MateriasController.php

class MateriasController extends Controller
{
    private $materiaModel;

    public function __construct()
    {
        require_auth();
        $this->materiaModel = new Materia();
    }

    public function index()
    {
        $materias = $this->materiaModel->getAll();
        $this->view('materias/index', ['materias' => $materias]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre' => trim($_POST['nombre']),
                'cupo_maximo' => (int) $_POST['cupo_maximo'],
                'vigencia_inicio' => $_POST['vigencia_inicio'] ?: null,
                'vigencia_fin' => $_POST['vigencia_fin'] ?: null
            ];
            $this->materiaModel->create($datos);
            // Redirigir a asignación de docentes u otra vista
            header('Location: ' . BASE_URL . 'materias');
            exit;
        }
        $this->view('materias/create');
    }

    public function edit($id)
    {
        $materia = $this->materiaModel->getById($id);
        if (!$materia) {
            header('Location: ' . BASE_URL . 'materias');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre' => trim($_POST['nombre']),
                'cupo_maximo' => (int) $_POST['cupo_maximo'],
                'vigencia_inicio' => $_POST['vigencia_inicio'] ?: null,
                'vigencia_fin' => $_POST['vigencia_fin'] ?: null
            ];
            $this->materiaModel->update($id, $datos);
            header('Location: ' . BASE_URL . 'materias');
            exit;
        }
        $this->view('materias/edit', ['materia' => $materia]);
    }

    public function delete($id)
    {
        $this->materiaModel->delete($id);
        header('Location: ' . BASE_URL . 'materias');
        exit;
    }
}
