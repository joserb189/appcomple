<?php
namespace App\Controllers;
use App\Models\Periodo;
use CodeIgniter\RESTful\ResourceController; 

class Periodos extends ResourceController{
    public function __construct() { 
        $this->model = new Periodo();
    }
    public function index(){
        try {
            // $periodo = $this->model->findAll();
            $periodo = $this->model->periodos();
            return $this->respuesta($periodo,'',200);
        } catch (\Exception $e){
            return $this->respuesta([],$e->getMessage(),500);
        }
    } 
    public function show($id = null){
        try {
            if($id == null){
                return $this->respuesta([],'No se ha especificado el Id',400);
            }else{
                $periodo = $this->model->find($id);
                if($periodo == null){
                    return $this->respuesta([],'No se ha encontrado el periodo',404);
                }else{
                    return $this->respuesta($periodo, '', 200);
                }      
            }
        } catch (\Exception $e) {
            return $this->respuesta([],$e->getMessage(),500);
        }
    }
    public function create(){
        try {
            $periodo = $this->request->getJSON();
            $data =[
                'mes_ini' => $periodo->mes_ini,
                'mes_fin' => $periodo->mes_fin,
                'anio' => $periodo->anio,
                'status' => $periodo->status
            ];
            if($this->model->insert($data)):
                $periodo->id = $this->model->insertID();
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
                $periodoVerificada = $this->model->find($id);
                if ($periodoVerificada == null) {
                    return $this->respuesta([], 'No se ha encontrado una evidencia con el id: ' . $id, 404);
                }
                $periodo = $this->request->getJSON();
                $data = [
                    'mes_ini' => $periodo->mes_ini,
                    'mes_fin' => $periodo->mes_fin,
                    'anio' => $periodo->anio,
                    'status' => $periodo->status,
                ];
                if ($this->model->update($id, $data)) :
                    $periodo->id = $id;
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
            $periodoVerificado = $this->model->find($id);
            if (
                $periodoVerificado == null
            ) {
                return $this->respuesta([], 'No se ha encontrado el perido con el id: ' . $id, 404);
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
 
