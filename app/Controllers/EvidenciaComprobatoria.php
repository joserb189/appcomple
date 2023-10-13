<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\EvidenciasComprobatorias;

class EvidenciaComprobatoria extends ResourceController{
    public function __construct()
    {
        // $this->model = new EvidenciasComprobatorias();
        $this->model = $this->setModel(new EvidenciasComprobatorias());
    }
    public function index(){
        try {
            $evd_comprobatorias = $this->model->findAll();
            return $this->respuesta($evd_comprobatorias, '', 200);
        } catch (\Exception $e) {
            // return $this->respuesta([],$e->getMessage(),500);
        }
    }
    public function show($id = null){
        try {
            if ($id == null) {
                return $this->respuesta([], 'No se ha especificado el Id', 400);
            } else {
                $evidenciac = $this->model->find($id);
                if ($evidenciac == null) {
                    return $this->respuesta([], 'No se ha encontrado el evidencia', 404);
                } else {
                    return $this->respuesta($evidenciac, '', 200);
                }
            }
        } catch (\Exception $e) {
            return $this->respuesta([], $e->getMessage(), 500);
        }
    }

    public function create(){
        try {
            $evidenciac = $this->request->getJSON();
            $data = [
                'evd_presentar' => $evidenciac->evd_presentar,
                'act_complementaria' => $evidenciac->act_complementaria,
            ];
            if ($this->model->insert($data)) :
                $evidenciac->id = $this->model->insertID();
                return $this->respuesta('Operacion Exitosa!', '', 200);
            // return $this->respondCreated($rol);
            else :
                return $this->failValidationErrors($this->model->validation->listErrors());
            endif;
        } catch (\Exception $e) {
            return $this->respuesta([], $e->getMessage(), 500);
        }
    }

    public function update($id = null){
        try {
            if ($id == null) {
                return $this->respuesta([], 'No se ha pasado un Id valido', 400);
            } else {
                $evidenciaVerificada = $this->model->find($id);
                if ($evidenciaVerificada == null) {
                    return $this->respuesta([], 'No se ha encontrado una evidencia con el id: ' . $id, 404);
                }
                $evidenciac = $this->request->getJSON();
                $data = [
                    'evd_presentar' => $evidenciac->evd_presentar,
                    'act_complementaria' => $evidenciac->act_complementaria,
                ];
                if ($this->model->update($id, $data)) :
                    $evidenciac->id = $id;
                    return $this->respuesta('Operacion Actualizada', '', 200);
                else :
                    return $this->failValidationErrors($this->model->validation->listErrors());
                endif;
            }
        } catch (\Exception $e) {
            return $this->respuesta([], $e->getMessage(), 500);
        }
    }

    public function delete($id = null){
        try {
            if ($id == null)
                return $this->respuesta([], 'No se ha pasado un Id valido', 400);
            $evidenciaVerificada = $this->model->find($id);
            if ($evidenciaVerificada == null) {
                return $this->respuesta([], 'No se ha encontrado la evidencia comprobatoria con el id: ' . $id, 404);
            }
            if ($this->model->delete($id)) :
                return $this->respuesta('Se ha eliminado Correctamente!', '', 200);
            else :
                return $this->respuesta([], 'No se ha podido eliminar el registro', 404);
            endif;
        } catch (\Exception $e) {
            return $this->respuesta([], $e->getMessage(), 500);
        } 
    }

    public function respuesta($data, $mensaje, $codigo)
    {
        if ($codigo == 200) {
            return $this->respond(array(
                "status" => $codigo,
                "data" => $data
            ));
        } else {
            return $this->respond(array(
                "status" => $codigo,
                "data" => $mensaje
            ));
        }
    }
}

