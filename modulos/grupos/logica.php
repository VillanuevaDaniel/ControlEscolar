<?php
// app/Controllers/GruposController.php

class GruposController extends Controller
{
    private $grupoModel;

    public function __construct()
    {
        require_auth();
        $this->grupoModel = new Grupo();
    }

    public function index()
    {
        $grupos = $this->grupoModel->getAll();
        $this->view('grupos/index', ['grupos' => $grupos]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'grado' => trim($_POST['grado']),
                'seccion' => trim($_POST['seccion']),
                'ciclo_escolar' => trim($_POST['ciclo_escolar']),
                'turno' => $_POST['turno']
            ];
            $this->grupoModel->create($datos);
            header('Location: ' . BASE_URL . 'grupos');
            exit;
        }
        $this->view('grupos/create');
    }

    public function edit($id)
    {
        $grupo = $this->grupoModel->getById($id);
        if (!$grupo) {
            header('Location: ' . BASE_URL . 'grupos');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'grado' => trim($_POST['grado']),
                'seccion' => trim($_POST['seccion']),
                'ciclo_escolar' => trim($_POST['ciclo_escolar']),
                'turno' => $_POST['turno']
            ];
            $this->grupoModel->update($id, $datos);
            header('Location: ' . BASE_URL . 'grupos');
            exit;
        }
        $this->view('grupos/edit', ['grupo' => $grupo]);
    }

    public function delete($id)
    {
        $this->grupoModel->delete($id);
        header('Location: ' . BASE_URL . 'grupos');
        exit;
    }
}
