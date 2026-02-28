<?php
// app/Controllers/HorariosController.php
// (Maneja las Ofertas de Horario)

class HorariosController extends Controller
{
    private $horarioModel;
    private $materiaModel;
    private $docenteModel;
    private $salonModel;
    private $grupoModel;

    public function __construct()
    {
        require_auth();
        $this->horarioModel = new Horario();
        $this->materiaModel = new Materia();
        $this->docenteModel = new Docente();
        $this->salonModel = new Salon();
        $this->grupoModel = new Grupo();
    }

    public function index()
    {
        $ofertas = $this->horarioModel->getAll();
        $this->view('horarios/index', ['ofertas' => $ofertas]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'id_materia' => $_POST['id_materia'],
                'id_profesor' => $_POST['id_profesor'],
                'id_salon' => $_POST['id_salon'],
                'id_grupo' => $_POST['id_grupo'] ?: null,
                'dia' => $_POST['dia'],
                'hora_inicio' => $_POST['hora_inicio'],
                'hora_fin' => $_POST['hora_fin'],
                'ciclo_escolar' => trim($_POST['ciclo_escolar']),
                'estado' => isset($_POST['estado']) ? 1 : 0
            ];
            $this->horarioModel->create($datos);
            header('Location: ' . BASE_URL . 'horarios');
            exit;
        }

        $materias = $this->materiaModel->getAll();
        $docentes = $this->docenteModel->getAll();
        $salones = $this->salonModel->getAll();
        $grupos = $this->grupoModel->getAll();

        $this->view('horarios/create', [
            'materias' => $materias,
            'docentes' => $docentes,
            'salones' => $salones,
            'grupos' => $grupos
        ]);
    }

    public function edit($id)
    {
        $oferta = $this->horarioModel->getById($id);
        if (!$oferta) {
            header('Location: ' . BASE_URL . 'horarios');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'id_materia' => $_POST['id_materia'],
                'id_profesor' => $_POST['id_profesor'],
                'id_salon' => $_POST['id_salon'],
                'id_grupo' => $_POST['id_grupo'] ?: null,
                'dia' => $_POST['dia'],
                'hora_inicio' => $_POST['hora_inicio'],
                'hora_fin' => $_POST['hora_fin'],
                'ciclo_escolar' => trim($_POST['ciclo_escolar']),
                'estado' => isset($_POST['estado']) ? 1 : 0
            ];
            $this->horarioModel->update($id, $datos);
            header('Location: ' . BASE_URL . 'horarios');
            exit;
        }

        $materias = $this->materiaModel->getAll();
        $docentes = $this->docenteModel->getAll();
        $salones = $this->salonModel->getAll();
        $grupos = $this->grupoModel->getAll();

        $this->view('horarios/edit', [
            'oferta' => $oferta,
            'materias' => $materias,
            'docentes' => $docentes,
            'salones' => $salones,
            'grupos' => $grupos
        ]);
    }

    public function delete($id)
    {
        $this->horarioModel->delete($id);
        header('Location: ' . BASE_URL . 'horarios');
        exit;
    }
}
