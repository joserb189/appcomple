<?php

namespace App\Models;

use CodeIgniter\Model;

class Carrera extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'carreras';//nombre de la table
    protected $primaryKey       = 'id';//llave primaria
    protected $useAutoIncrement = true;//autoincrementable
    protected $insertID         = 0;
    protected $returnType       = 'array';//tipo de dato que devuelve
    protected $useSoftDeletes   = false;//desabilita el delete_at en la base de datos, por lo regular se le entiende como una papelera de reciclaje
    protected $protectFields    = true;//activa la proteccion solo funciona en migraciones o pruebas o tambien los seeds 
    protected $allowedFields    = ['nombre','nombre_corto', 'jdepto'];

    // Validation
    protected $validationRules      = [
        'nombre' => 'required|min_length[5]|max_length[100]',
        'nombre_corto' => 'required|min_length[3]|max_length[30]',
        'jdepto' => 'required|is_valid_jfdpto',
    ];
    protected $validationMessages   = [
        'nombre' => [
            'required' => 'EL campo {field} es obligatorio.',
            'min_length' => 'El campo {field} debe tener al menos {param} caracteres.',
            'max_length' => 'El campo {field} debe tener como máximo {param} caracteres.'
        ],
        'nombre_corto' => [
            'required' => 'EL campo {field} es obligatorio.',
            'min_length' => 'El campo {field} debe tener al menos {param} caracteres.',
            'max_length' => 'El campo {field} debe tener como máximo {param} caracteres.'
        ],
        'jdepto' => [
            'required' => 'El campo {field} es obligatorio.',
            'is_valid_jfdpto' => 'El campo {field} no se encuentra con el id: {value}.'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
    public function carreras(){
        $carrera = $this->db->table($this->table);
        $carrera->select('carreras.id, carreras.nombre, carreras.nombre_corto, CONCAT(jdepto.nombre," ",jdepto.apellidos) as jefe');
        $carrera->join('jdepto', 'carreras.jdepto = jdepto.rfc');
        $query = $carrera->get();
        return $query->getResult();
    }
}