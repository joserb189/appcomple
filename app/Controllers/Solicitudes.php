<?php

namespace App\Controllers;
use App\Models\Solicitud;
use CodeIgniter\RESTful\ResourceController;

class Solicitudes extends ResourceController{
    public function __construct() {
        $this->model = new Solicitud();
    } 
    public function index(){
        try {
            $solicitud = $this->model->findAll();
            return $this->respuesta($solicitud, '', 200);
        } catch (\Exception $e) {
            return $this->respuesta([], $e->getMessage(), 500);
        }
    }
    public function show($id = null){
        try {
            if ($id == null) {
                return $this->respuesta([], 'No se ha especificado el Id', 400);
            } else {
                $solicitud = $this->model->find($id);
                if ($solicitud == null) {
                    return $this->respuesta([], 'No se ha encontrado la solicitud', 404);
                } else {
                    return $this->respuesta($solicitud, '', 200);
                }
            }
        } catch (\Exception $e) {
            return $this->respuesta([], $e->getMessage(), 500);
        }
    }
    public function create(){
        try {
            $solicitud = $this->request->getJSON();
            $data = [
                'status' => $solicitud->status,
                'observacion' => $solicitud->observacion,
                'created_at' => $solicitud->created_at,
                'valor_numerico' => $solicitud->valor_numerico,
                'periodo' => $solicitud->periodo,
                'jdepto' => $solicitud->jdepto,
                'alumno' => $solicitud->alumno,
                'act_complementaria' => $solicitud->act_complementaria
            ];
            if ($this->model->insert($data)) :
                $solicitud->id = $this->model->insertID();
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
                $solicitudVerificada = $this->model->find($id);
                if ($solicitudVerificada == null) {
                    return $this->respuesta([], 'No se ha encontrado una solicitud con el id: ' . $id, 404);
                }
                $solicitud = $this->request->getJSON();
                $data = [
                    'status' => $solicitud->status,
                    'observacion' => $solicitud->observacion,
                    'created_at' => $solicitud->created_at,
                    'valor_numerico' => $solicitud->valor_numerico,
                    'periodo' => $solicitud->periodo,
                    'jdepto' => $solicitud->jdepto,
                    'alumno' => $solicitud->alumno,
                    'act_complementaria' => $solicitud->act_complementaria
                ];
                if ($this->model->update($id, $data)) :
                    $solicitud->id = $id;
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
            $solicitudVerificada = $this->model->find($id);
            if ($solicitudVerificada == null) {
                return $this->respuesta([], 'No se ha encontrado la solicitud con el id: ' . $id, 404);
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
