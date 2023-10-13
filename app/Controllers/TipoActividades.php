<?php

namespace App\Controllers;

use App\Models\TipoActividad;
use CodeIgniter\RESTful\ResourceController; 

class TipoActividades extends ResourceController
{
    public function __construct() { 
        $this->model = new TipoActividad();
    }

    public function index(){
        try {
            $tipoact = $this->model->findAll();
            return $this->respuesta($tipoact,'',200);
        } catch (\Exception $e){
            return $this->respuesta([],$e->getMessage(),500);
        }
    }

    public function show($id = null){
        try {
            if($id == null){
                return $this->respuesta([],'No se ha especificado el Id',400);
            }else{
                $tipoact = $this->model->find($id);
                if($tipoact == null){
                    return $this->respuesta([],'No se ha encontrado el tipo de actividad',404);
                }else{
                    return $this->respuesta($tipoact, '', 200);
                }      
            }
        } catch (\Exception $e) {
            return $this->respuesta([],$e->getMessage(),500);
        }
    }

    public function create(){
        try {
            $tipoact = $this->request->getJSON();
            $data =[
                'nombre' => $tipoact->nombre,
                'max_creditos' => $tipoact->max_creditos
            ];
            if($this->model->insert($data)):
                $tipoact->id = $this->model->insertID();
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
            if ($id == null) {
                return $this->respuesta([], 'No se ha pasado un Id valido', 400);
            } else {
                $tipoactVerificada = $this->model->find($id);
                if ($tipoactVerificada == null) {
                    return $this->respuesta([], 'No se ha encontrado una evidencia con el id: ' . $id, 404);
                }
                $tipoact = $this->request->getJSON();
                $data = [
                    'nombre' => $tipoact->nombre,
                    'max_creditos' => $tipoact->max_creditos
                ];
                if ($this->model->update($id, $data)) :
                    $tipoact->id = $id;
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
            $tipoactVerificado = $this->model->find($id);
            if(
                $tipoactVerificado == null
            ) {
                return $this->respuesta([], 'No se ha encontrado el departamento con el id: ' . $id, 404);
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
 