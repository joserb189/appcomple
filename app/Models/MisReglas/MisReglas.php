<?php

namespace App\Models\MisReglas;

use App\Models\Carrera;
use App\Models\JefeDepartamento;
use App\Models\Departamento;
class MisReglas{
    
    public function is_valid_dpto(int $id): bool {
        $model = new Departamento();
        $dpto = $model->find($id);
        return $dpto == null ? false : true;
    }

    public function is_valid_jfdpto(string $rfc): bool {
        $model = new JefeDepartamento();
        $jfdepa = $model->find($rfc);
        return $jfdepa == null ? false : true;
    }
    public function is_valid_carrera(string $id): bool {
        $model = new Carrera();
        $carrera = $model->find($id);
        return $carrera == null ? false : true;
    }

    public function is_valid_status(int $bandera): bool {
        return $bandera == 1 || $bandera == 0 ? true : false;
    }
}
