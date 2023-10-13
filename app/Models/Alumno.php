<?php

namespace App\Models;

use CodeIgniter\Model;

class Alumno extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'alumnos';
     protected $primaryKey       = 'no_control';
    protected $useAutoIncrement = false;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['no_control','nombre', 'a_paterno','a_materno', 'nip','carrera'];

    // Dates
    protected $useTimestamps = false;
    // protected $dateFormat    = 'datetime';
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
    // protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'no_control' => 'required|alpha_numeric_space|is_unique[alumnos.no_control]',
        'nombre'=> 'required',
        'a_paterno' => 'required',
        'a_materno' => 'required',
        'nip' => 'required',
        'carrera'=> 'required|is_valid_carrera'
    ];
    protected $validationMessages = [
        'no_control'=> [
            'required' => 'EL campo {field} es obligatorio.',
            'alpha_numeric_space' => 'El campo {field} debe contener letras y números.',
            'is_unique' => 'El número control ({value}) ya se encuentra registrado.',
        ],
        'nombre'=> [
            'required' => 'EL campo {field} es obligatorio.'
        ],
        'a_paterno' => [
            'required' => 'EL campo {field} es obligatorio.'
        ],
        'a_materno' => [
            'required' => 'EL campo {field} es obligatorio.'
        ],
        'nip' => [
            'required' => 'EL campo {field} es obligatorio.'
        ],
        'carrera' => [
            'required' => 'EL campo {field} es obligatorio.',
            'is_valid_carrera' => 'El campo {field} no se encuentra, deberia revisar el listado de las carreras.'
        ]
    ];
    protected $skipValidation       = false; //indica que las validaciones no sean ignoradas
    protected $cleanValidationRules = true;
    public function alumnosCarreras(){
        $alumnos = $this->db->table($this->table);
        $alumnos->select('alumnos.no_control, CONCAT(alumnos.nombre," ", alumnos.a_paterno," ", alumnos.a_materno) as nombre_completo, alumnos.nip, carreras.nombre_corto as carrera ');
        // $alumnos->from('alumnos a, carreras c');
        $alumnos->join('carreras','alumnos.carrera = carreras.id');
        $query = $alumnos->get();
        return $query->getResult();
    }
    public function alumnoCarrera($no_control){
        $alumno = $this->db->table($this->table);
        $alumno->select('a.no_control, CONCAT(a.nombre," ", a.a_paterno," ", a.a_materno) as nombre_completo, a.nip, c.nombre_corto as carrera');
        $alumno->from('alumnos a, carreras c');
        $alumno->where('a.carrera = c.id');
        $alumno->where('a.no_control', $no_control);
        $query = $alumno->get();
        return $query->getRow();
    }
    public function alumnosCarrera($carrera){
        $alumnos = $this->db->table($this->table);
        $alumnos->select('no_control, CONCAT(nombre," ", a_paterno," ", a_materno) as nombre_completo, nip, carrera');
        $alumnos->where('carrera', $carrera);
        $query = $alumnos->get();
        return $query->getResult();
    }
    public function ValidaNoControl($no_control){
        $alumno = $this->db->table('alumnos')
                ->where('no_control', $no_control);
        if($alumno->countAllResults() > 0){
            return true;
        }else{
            return false;
        }
    }
    public function Acceso($no_control, $nip){
        $alumno = $this->db->table('alumnos')
            ->where('no_control', $no_control)
            ->where('nip', $nip);
        if ($alumno->countAllResults() > 0) {
            return true;
        } else {
            return false;
        }
    }
}
