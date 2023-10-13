<?php

namespace App\Models;

use CodeIgniter\Model;

class Periodo extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'periodos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['mes_ini','mes_fin','anio', 'status'];

    // Validacion
    protected $validationRules      = [
        'mes_ini' => 'required',
        'mes_fin' => 'required',
        'anio' => 'required|numeric',
        'status' => 'required|is_valid_status'
    ];
    //mensaje de validacion
    protected $validationMessages   = [
        'mes_ini' => [
            'required' => 'EL campo {field} es obligatorio.'
        ],
        'mes_fin' => [
            'required' => 'EL campo {field} es obligatorio.'
        ],
        'anio' => [
            'required' => 'EL campo {field} es obligatorio.',
            'numeric' => 'EL campo {field} debe de ser un numero entero.'
        ],
        'status' => [
            'required' => 'EL campo {field} es obligatorio.',
            'is_valid_status' => 'El campo {field} debe ser valido 0 o 1.'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    public function periodos()
    {
        $builder = $this->db->table($this->table);
        $builder->select('id, CONCAT(mes_ini," - ", mes_fin," ", anio) as periodo, status');
        $query = $builder->get();
        return $query->getResult();
    }
}
