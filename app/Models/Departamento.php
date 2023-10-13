<?php

namespace App\Models;

use CodeIgniter\Model;

class Departamento extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'deptos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nombre'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'nombre' => 'required|min_length[5]|max_length[75]'
    ];
    protected $validationMessages   = [
        'nombre' => [
            'required' => 'EL campo {field} es obligatorio.',
            'min_length' => 'El campo {field} debe tener al menos {param} caracteres.',
            'max_length' => 'El campo {field} debe tener como máximo {param} caracteres.'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    /*protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];*/
}
