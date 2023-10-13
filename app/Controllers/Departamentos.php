<?php
namespace App\Controllers;
use App\Models\Departamento;
use CodeIgniter\RESTful\ResourceController;

class Departamentos extends ResourceController{
    
    public function __construct() { 
        $this->model = new Departamento();
    }
    
    public function index() {
        try {
            $deptos = $this->model->findAll();
            return $this->respuesta($deptos,'',200);
        } catch (\Exception $e){
            return $this->respuesta([],$e->getMessage(),500);
        }
    }

    public function show($id = null){
        try {
            if($id == null){
                return $this->respuesta([],'No se ha especificado el Id',400);
            }else{
                $depto = $this->model->find($id);
                if($depto == null){
                    return $this->respuesta([],'No se ha encontrado el Departamento',404);
                }else{
                    return $this->respuesta($depto, '', 200);
                }
            }
        } catch (\Exception $e) {
            return $this->respuesta([],$e->getMessage(),500);
        }
    }
    
    public function create() {
        try {
            $depto = $this->request->getJSON();
            $data =[
                'nombre' => $depto->nombre,
            ];
            if($this->model->insert($data)):
                $depto->id = $this->model->insertID();
                return $this->respuesta('Operacion Exitosa!','',200);
               // return $this->respondCreated($rol);
            else:
                // return $this->failValidationErrors($this->model->validation->listErrors());
                return $this->validacion($this->model->validation->listErrors());
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
                $departamentoVerificado = $this->model->find($id);
                if($departamentoVerificado == null){
                    return $this->respuesta([],'No se ha encontrado un departamento con el id: '.$id,404);
                }   
                $depto = $this->request->getJSON();
                $data =[
                    'nombre' => $depto->nombre,
                ];
                if($this->model->update($id, $data)):
                    $depto->id = $id;
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
            if($id == null)
                return $this->failValidationErrors('No se ha pasado un Id valido');
            $departamentoVerificado = $this->model->find($id);
            if($departamentoVerificado == null){
                // return $this->failNotFound('No se ha encontrado el departamento con el id:' . $id);
                // return $this->setResponseFormat('json')->fail('No se ha encontrado el departamento con el id:' . $id, 200);
                // return $this->fail('No se ha encontrado el departamento con el id: ' . $id, 404);
                return $this->error('No se ha encontrado el departamento con el id: ' . $id,404);
            }
            if($this->model->delete($id)):
                return $this->respuesta('Se ha eliminado Correctamente!','',200);
            else:
                // return $this->fail('No se ha podido eliminar el registro',404);
                return $this->respuesta([],'No se ha podido eliminar el registro', 404);
                //return $this->failServerError('No se ha podido eliminar el registro');
            endif;
        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
            // return $this->respuesta([],$e->getMessage(),500);
        } 
    }
    protected function respuesta($data, $mensaje, $codigo){
        if ($codigo == 200) {
            return $this->setResponseFormat('json')->respond(array(
                "status" => $codigo,
                "data" => $data
            ));
        } else {
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
