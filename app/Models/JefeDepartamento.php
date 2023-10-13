<?php

namespace App\Models;

use CodeIgniter\Model;

class JefeDepartamento extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'jdepto';
    protected $primaryKey       = 'rfc';
    protected $useAutoIncrement = false;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['rfc','nombre', 'apellidos', 'clave','fecha_ingreso', 'fecha_termina', 'status', 'departamento'];

    // Dates
    protected $useTimestamps = false;
    // Validation
    protected $validationRules  = [
        'rfc' => 'required|alpha_numeric_space|is_unique[jdepto.rfc]',
        'nombre'=>'required',
        'apellidos' => 'required',
        'clave' => 'required|min_length[8]|max_length[20]',
        'fecha_ingreso' => 'required',
        'fecha_termina' => 'required',
        'status' => 'required|is_valid_status',
        'departamento' => 'required|numeric|is_valid_dpto',
    ];
    protected $validationMessages = [
        'rfc' => [
            'required' => 'EL campo {field} es obligatorio.',
            'alpha_numeric_space' => 'El campo {field} debe contener letras y números.',
            'is_unique' => 'El rfc ({value}) ya se encuentra registrado.',
        ],
        'nombre' => [
            'required' => 'EL campo {field} es obligatorio.'
        ],
        'apellidos' => [
            'required' => 'EL campo {field} es obligatorio.',
        ],
        'clave' => [
            'required' => 'EL campo {field} es obligatorio.',
            'min_length' => 'El campo {field} debe tener al menos {param} caracteres.',
            'max_length' => 'El campo {field} debe tener como máximo {param} caracteres.'
        ],
        'fecha_ingreso' => [
            'required' => 'El campo {field} es obligatorio.',
            // 'alpha_space' => 'El campo nombre solo puede contener caracteres alfabéticos y espacios.',
        ],
        'fecha_termina' => [
            'required' => 'El campo {field} es obligatorio.',
            // 'alpha_space' => 'El campo nombre solo puede contener caracteres alfabéticos y espacios.',
        ],
        'status' => [
            'required' => 'El campo {field} es obligatorio.',
            'is_valid_status' => 'El campo {field} debe ser valido 0 o 1.'
            // 'alpha_space' => 'El campo nombre solo puede contener caracteres alfabéticos y espacios.',
        ],
        'departamento' => [
            'required' => 'El campo {field} es obligatorio.',
            'numeric' => 'El campo {field} debe de contener solo números.',
            'is_valid_dpto'=> 'El campo {field} no es valido.',
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    public function jefeDepartamento(){
        $jdepto = $this->db->table($this->table);
        $jdepto->select('jdepto.rfc, CONCAT(jdepto.nombre," ", jdepto.apellidos) as nombre_completo, jdepto.clave, jdepto.fecha_ingreso, jdepto.fecha_termina, jdepto.status, deptos.nombre as departamento');
        // $builder->from('jdepto j, deptos d');//no sirve esto
        $jdepto->join('deptos', 'jdepto.departamento = deptos.id');
        // $jdepto->where('j.departamento = d.id');
        $query = $jdepto->get();
        return $query->getResult();
    }
    public function ValidaRFC($rfc)
    {
        $jdpto = $this->db->table('jdepto')
        ->where('rfc', $rfc);
        if ($jdpto->countAllResults() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function Acceso($rfc, $clave){
        $alumno = $this->db->table('jdepto')
        ->where('rfc', $rfc)
            ->where('clave', $clave);
        if ($alumno->countAllResults() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // public function buscarJfDpto($rfc){
    //     $alumno = $this->db->table($this->table);
    //     $alumno->select('a.no_control, CONCAT(a.nombre," ", a.a_paterno," ", a.a_materno) as nombre_completo, a.nip, c.nombre_corto as carrera');
    //     $alumno->from('alumnos a, carreras c');
    //     $alumno->where('a.carrera = c.id');
    //     $alumno->where('a.no_control', $no_control);
    //     $query = $alumno->get();
    //     return $query->getRow();
    // }

}