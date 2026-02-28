<?php
// core/Controller.php

class Controller
{
    public function view($view, $data = [])
    {
        // Extrae los datos para que estén disponibles como variables en la vista
        extract($data);

        // $view usually comes as 'modulo/nombre_vista' (e.g. 'alumnos/index')
        $parts = explode('/', $view);
        if (count($parts) >= 2) {
            $modulo = $parts[0];
            $nombre_vista = implode('_', array_slice($parts, 1));
            $viewFile = 'modulos/' . $modulo . '/vista_' . $nombre_vista . '.html';
        } else {
            // Fallback for simple names
            $viewFile = 'modulos/comun/vista_' . $view . '.html';
        }

        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("Vista $view (buscada en $viewFile) no encontrada.");
        }
    }

    public function model($model)
    {
        // The file is automatically loaded by spl_autoload_register in index.php
        return new $model();
    }
}
