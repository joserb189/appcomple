<?php

namespace App\Controllers;
use App\Models\JefeDepartamento;
use CodeIgniter\RESTful\ResourceController;

class JefesDepartamento extends ResourceController {
    //protected $format = 'json';
    public function __construct() {
        $this->model = new JefeDepartamento();
    }
    public function index(){
        try {
            // $jefes = $this->model->findAll();
            $jefes = $this->model->jefeDepartamento();
            return $this->respuesta($jefes,'',200);
        } catch (\Exception $e){
            return $this->respuesta([],$e->getMessage(),500);
        }
    }
    public function show($rfc = null){
        try {
            if($rfc == null){
                return $this->respuesta([],'No se ha especificado el RFC',400);
            }else{
                $jefe = $this->model->find($rfc);
                if($jefe == null){
                    return $this->respuesta([],'No se ha encontrado el RFC',404);
                }else{
                    return $this->respuesta($jefe, '', 200);
                }      
            }
        } catch (\Exception $e) {
            return $this->respuesta([],$e->getMessage(),500);
        }
    }

    public function create(){
        try {
            $jfdpto = $this->request->getJSON();
            $data =[
                'rfc'=> $jfdpto->rfc,
                'nombre'=>$jfdpto->nombre,
                'apellidos'=>$jfdpto->apellidos,
                'clave'=>$jfdpto->clave,
                'fecha_ingreso'=>$jfdpto->fecha_ingreso,
                'fecha_termina'=>$jfdpto->fecha_ingreso,
                'status'=>$jfdpto->status,
                'departamento'=>$jfdpto->departamento,
            ];
            if($this->model->insert($data)){
                $jfdpto->rfc = $this->model->insertID();
                return $this->respuesta('Operacion Exitosa!', '', 200);
            // return $this->respondCreated($jfdpto);
            }else{
                return $this->validacion($this->model->validation->listErrors());
            }
        } catch (\Exception $e) {
            return $this->respuesta([],$e->getMessage(),500);
        }
    }

    public function update($rfc = null){
        try {
            if ($rfc == null) {
                return $this->respuesta([], 'No se ha pasado un RFC valido', 400);
            } else {
                $jefeVerificado = $this->model->find($rfc);
                if ($jefeVerificado == null) {
                    return $this->respuesta([], 'No se ha encontrado un Jefe con el rfc: ' . $rfc, 404);
                }
                $jfdpto = $this->request->getJSON();
                $data = [
                    'nombre' => $jfdpto->nombre,
                    'apellidos' => $jfdpto->apellidos,
                    'clave' => $jfdpto->clave,
                    'fecha_ingreso' => $jfdpto->fecha_ingreso,
                    'fecha_termina' => $jfdpto->fecha_ingreso,
                    'status' => $jfdpto->status,
                    'departamento' => $jfdpto->departamento,
                ];
                if ($this->model->update($rfc, $data)){
                    $jfdpto->rfc = $rfc;
                    return $this->respuesta('Operacion Actualizada', '', 200);
                }  else{
                    return $this->failValidationErrors($this->model->validation->listErrors());
                }
            }
        } catch (\Exception $e) {
            return $this->respuesta([], $e->getMessage(), 500);
        }
    }
    
    public function delete($id = null){
        try {
            if ($id == null)
                return $this->respuesta([], 'No se ha pasado un RFC valido', 400);
            $jefedepaVerificado = $this->model->find($id);
            if (
                $jefedepaVerificado == null
            ) {
                return $this->respuesta([], 'No se ha encontrado el departamento con el RFC: ' . $id, 404);
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
    public function ValidaJfdpto($rfc = null)
    {
        try {
            if ($rfc == null) {
                return $this->respuesta([], 'No se ha especificado el RFC del Jefe Departamento.', 400);
            } else {
                $jdpto = $this->model->ValidaRFC($rfc);
                if ($jdpto == false) {
                    return $this->error('No se ha encontrado el Jefe Departamento.', 404);
                } else {
                    return $this->respuesta($jdpto, '', 200);
                }
            }
        } catch (\Exception $e) {
            return $this->respuesta([], $e->getMessage(), 500);
        }
    }
    public function login(){
        try {
            $credenciales = $this->request->getJSON();
            if (empty($credenciales->rfc) || empty($credenciales->clave)) {
                return $this->errores('El rfc y la clave son requeridos', 400);
            } else {
                $rfc = $credenciales->rfc;
                $clave = $credenciales->clave;
                $jdpto = $this->model->Acceso($rfc, $clave);
                if ($jdpto == false) {
                    return $this->error('Datos erroneos.', 404, 'true');
                } else {
                    return $this->show($rfc);
                }
            }
        } catch (\Exception $e) {
            return $this->respuesta([], $e->getMessage(), 500);
        }
    }
    protected function respuesta($data, $mensaje, $codigo) {
        if ($codigo==200) {
            return $this->setResponseFormat('json')->respond(array(
                "status" => $codigo,
                "data" => $data
            ));
        }else{
            return $this->setResponseFormat('json')->respond(array(
                "status" => $codigo,
                "data" => $mensaje
            ));
        }
    }
    protected function error($mensaje, int $status,  ?string $codigo = null){
        $response = [
            'status'   => $status,
            'error'    => $codigo ?? $status,
            'mensaje' => $mensaje,
        ];
        return $this->respond($response, $status);
    }
    protected function errores($mensaje, int $status = 400,  ?int $codigo = null, string $customMessage = ''){
        if (!is_array($mensaje)) {
            $mensaje = ['errores' => $mensaje];
        }
        $response = [
            'status'   => $status,
            'error'    => $codigo ?? $status,
            'mensaje' => $mensaje,
        ];
        return $this->respond($response, $status, $customMessage);
    }
    protected function validacion($error, $codigo = 400, string $mensaje = ''){
        return $this->errores($error,400, $codigo, $mensaje);
    }
}

