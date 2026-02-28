<?php
// app/Controllers/SalonesController.php

class SalonesController extends Controller
{
    private $salonModel;

    public function __construct()
    {
        require_auth();
        $this->salonModel = new Salon();
    }

    public function index()
    {
        $salones = $this->salonModel->getAll();
        $this->view('salones/index', ['salones' => $salones]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre' => trim($_POST['nombre']),
                'capacidad' => (int) $_POST['capacidad']
            ];
            $this->salonModel->create($datos);
            header('Location: ' . BASE_URL . 'salones');
            exit;
        }
        $this->view('salones/create');
    }

    public function edit($id)
    {
        $salon = $this->salonModel->getById($id);
        if (!$salon) {
            header('Location: ' . BASE_URL . 'salones');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre' => trim($_POST['nombre']),
                'capacidad' => (int) $_POST['capacidad']
            ];
            $this->salonModel->update($id, $datos);
            header('Location: ' . BASE_URL . 'salones');
            exit;
        }
        $this->view('salones/edit', ['salon' => $salon]);
    }

    public function delete($id)
    {
        $this->salonModel->delete($id);
        header('Location: ' . BASE_URL . 'salones');
        exit;
    }
}
