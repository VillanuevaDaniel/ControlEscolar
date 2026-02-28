<?php
// app/Controllers/CalificacionesController.php

class CalificacionesController extends Controller
{
    private $calificacionModel;
    private $inscripcionModel;

    public function __construct()
    {
        require_auth();
        $this->calificacionModel = new Calificacion();
        $this->inscripcionModel = new Inscripcion();
    }

    public function index()
    {
        $calificaciones = $this->calificacionModel->getAll();
        $this->view('calificaciones/index', ['calificaciones' => $calificaciones]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'id_inscripcion' => $_POST['id_inscripcion'],
                'etiqueta_periodo' => trim($_POST['etiqueta_periodo']),
                'puntaje' => $_POST['puntaje'],
                'estado' => $_POST['estado'] ?? 'ACTIVO'
            ];
            $this->calificacionModel->create($datos);
            header('Location: ' . BASE_URL . 'calificaciones');
            exit;
        }

        $inscripciones = $this->inscripcionModel->getAll();
        $this->view('calificaciones/create', ['inscripciones' => $inscripciones]);
    }

    public function edit($id)
    {
        $calificacion = $this->calificacionModel->getById($id);
        if (!$calificacion) {
            header('Location: ' . BASE_URL . 'calificaciones');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'id_inscripcion' => $_POST['id_inscripcion'],
                'etiqueta_periodo' => trim($_POST['etiqueta_periodo']),
                'puntaje' => $_POST['puntaje'],
                'estado' => $_POST['estado'] ?? 'ACTIVO'
            ];
            $this->calificacionModel->update($id, $datos);
            header('Location: ' . BASE_URL . 'calificaciones');
            exit;
        }

        $inscripciones = $this->inscripcionModel->getAll();
        $this->view('calificaciones/edit', [
            'calificacion' => $calificacion,
            'inscripciones' => $inscripciones
        ]);
    }

    public function delete($id)
    {
        $this->calificacionModel->delete($id);
        header('Location: ' . BASE_URL . 'calificaciones');
        exit;
    }
}
