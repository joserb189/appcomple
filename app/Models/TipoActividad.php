<?php

namespace App\Models;

use CodeIgniter\Model;

class TipoActividad extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'tipos_actividades';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nombre', 'max_creditos'];

    // Validation
    protected $validationRules      = [
        'nombre' => 'required',
        'max_creditos' => 'required'
    ];
    protected $validationMessages   = [
        'nombre' => [
            'required' => 'EL campo {field} es obligatorio.'
        ],
        'max_creditos' => [
            'required' => 'EL campo {field} es obligatorio.'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
