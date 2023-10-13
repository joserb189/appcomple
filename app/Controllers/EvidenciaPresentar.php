<?php

namespace App\Controllers;
use App\Models\EvidenciasPresentar;
use CodeIgniter\RESTful\ResourceController;

class EvidenciaPresentar extends ResourceController
{
    public function __construct() { 
        $this->model = new EvidenciasPresentar();
    }
    
    public function index(){
        try {
            $evidencia = $this->model->findAll();
            return $this->respuesta($evidencia,'',200);
        } catch (\Exception $e){
            return $this->respuesta([],$e->getMessage(),500);
        }
    }

    public function show($id = null){
        try {
            if($id == null){
                return $this->respuesta([],'No se ha especificado el Id',400);
            }else{
                $evidencia = $this->model->find($id);
                if($evidencia == null){
                    return $this->respuesta([],'No se ha encontrado el evidencia',404);
                }else{
                    return $this->respuesta($evidencia, '', 200);
                }      
            }
        } catch (\Exception $e) {
            return $this->respuesta([],$e->getMessage(),500);
        }
    }

    public function create(){
        try {
            $evidencia = $this->request->getJSON();
            $data =[
                'descripcion' => $evidencia->descripcion,
            ];
            if($this->model->insert($data)):
                $evidencia->id = $this->model->insertID();
                return $this->respuesta('Operacion Exitosa!','',200);
               // return $this->respondCreated($rol);
            else:
                return $this->failValidationErrors($this->model->validation->listErrors());
            endif;
        } catch (\Exception $e) {
            return $this->respuesta([],$e->getMessage(),500);
        }
    }

    public function update($id = null){
        try {
            if($id == null){
                return $this->respuesta([],'No se ha pasado un Id valido',400);
            }else{
                $evidenciaVerificada = $this->model->find($id);
                if($evidenciaVerificada == null){
                    return $this->respuesta([],'No se ha encontrado una evidencia con el id: '.$id,404);
                }   
                $evidencia = $this->request->getJSON();
                $data =[
                    'descripcion' => $evidencia->descripcion,
                ];
                if($this->model->update($id, $data)):
                    $evidencia->id = $id;
                    return $this->respuesta('Operacion Actualizada','',200);
                else:
                    return $this->failValidationErrors($this->model->validation->listErrors());
                endif;
            }
        } catch (\Exception $e) {
            return $this->respuesta([],$e->getMessage(),500);
        }
    }

    public function delete($id = null){
        try {
            if ($id == null)
                return $this->respuesta([], 'No se ha pasado un Id valido', 400);
            $evidenciaVerificado = $this->model->find($id);
            if ($evidenciaVerificado == null) {
                return $this->respuesta([], 'No se ha encontrado Evidencias a presemtar con el id: ' . $id, 404);
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

    public function respuesta($data, $mensaje, $codigo){
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
 