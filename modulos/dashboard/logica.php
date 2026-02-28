<?php
// app/Controllers/DashboardController.php

class DashboardController extends Controller
{

    public function index()
    {
        require_once 'config/init.php';
        require_auth();

        $u   = session_user();
        $pdo = db_connect();

        // ── Si es profesor: sus horarios de hoy ──────────────────
        $mis_horarios_hoy = array();
        if ($u['tipo'] === 'docente' || $u['rol'] === 'profesor') {
            $dias_es = array(
                'Monday'    => 'LUNES',
                'Tuesday'   => 'MARTES',
                'Wednesday' => 'MIERCOLES',
                'Thursday'  => 'JUEVES',
                'Friday'    => 'VIERNES',
            );
            $hoy = $dias_es[date('l')] ?? '';
            // Get id_profesor based on user id_usuario
            $stProf = $pdo->prepare("SELECT id_profesor FROM profesores WHERE id_usuario = ?");
            $stProf->execute([$u['id']]);
            $profesor = $stProf->fetchColumn();

            if ($hoy && $profesor) {
                $st = $pdo->prepare(
                    "SELECT h.hora_inicio, h.hora_fin, m.nombre AS materia, g.grado, g.seccion, s.nombre AS salon
                           FROM oferta_horario h
                           JOIN materias m ON m.id_materia = h.id_materia
                           LEFT JOIN grupos g ON g.id_grupo = h.id_grupo
                           JOIN salones  s ON s.id_salon = h.id_salon
                          WHERE h.id_profesor = ? AND h.dia = ? AND h.estado = 1
                          ORDER BY h.hora_inicio"
                );
                $st->execute(array($profesor, $hoy));
                $mis_horarios_hoy = $st->fetchAll();
            }
        }

        $this->view('dashboard/index', [
            'u' => $u,
            'mis_horarios_hoy' => $mis_horarios_hoy
        ]);
    }
}
