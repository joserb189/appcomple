<?php

namespace App\Controllers;
use App\Models\Alumno;
use CodeIgniter\RESTful\ResourceController;

class Alumnos extends ResourceController
{
    public function __construct() {
        $this->model = $this->setModel(new Alumno());
    }
    public function index(){
        try {
            // $alumnos = $this->model->findAll();
            $alumnos = $this->model->alumnosCarreras();
            return $this->respuesta($alumnos,'',200);
        } catch (\Exception $e){
            return $this->respuesta([], $e->getMessage(), 500);
        }
    }

    public function show($no_control = null){
        try {
            if($no_control == null){
                return $this->respuesta([],'No se ha especificado el número de control',400);
            }else{
               $alumno = $this->model->find($no_control);
                if($alumno == null){
                    return $this->respuesta([],'No se ha encontrado el número de control',404);
                }else{
                    return $this->respuesta($alumno, '', 200);
                }      
            }
        } catch (\Exception $e) {
            return $this->respuesta([],$e->getMessage(),500);
        } 
    }
    public function alumno($no_control = null)
    {
        try {
            if ($no_control == null) {
                return $this->respuesta([], 'No se ha especificado el número de control', 400);
            } else {
                // $alumnos = $this->model->find($no_control);
                $alumno = $this->model->alumnoCarrera($no_control);
                if ($alumno == null) {
                    return $this->respuesta([], 'No se ha encontrado el número de control', 404);
                } else {
                    return $this->respuesta($alumno, '', 200);
                }
            }
        } catch (\Exception $e) {
            return $this->respuesta([], $e->getMessage(), 500);
        }
    }
  
    public function create(){
        try {            
            $alumno = $this->request->getJSON();
            $data =[
                'no_control'=>$alumno->no_control,
                'nombre' => $alumno->nombre,
                'a_paterno' => $alumno->a_paterno,
                'a_materno' => $alumno->a_materno,
                'nip'=>$alumno->nip,
                'carrera' => $alumno->carrera
            ];
            if ($this->model->insert($data)) {
                $alumno->no_control = $this->model->insertID();
                return $this->respuesta('Operacion Exitosa!', '', 200);
            } else {
                return $this->validacion($this->model->validation->listErrors());
            }
        } catch (\Exception $e) {
            return $this->respuesta([],$e->getMessage(),500);
        } 
    }
    public function update($id = null){
        try {
            if ($id == null) {
                return $this->respuesta([], 'No se ha pasado un número control valido', 400);
            } else {
                $alumnoVerificado = $this->model->find($id);
                if ($alumnoVerificado == null) {
                    return $this->respuesta([], 'No se ha encontrado un alumno con el número de control: ' . $id, 404);
                }
                $alumno = $this->request->getJSON();
                $data = [
                    'nombre' => $alumno->nombre,
                    'a_paterno' => $alumno->a_paterno,
                    'a_materno' => $alumno->a_materno,
                    'nip' => $alumno->nip,
                    'carrera' => $alumno->carrera
                ];
                if ($this->model->update($id, $data)) :
                    $alumno->no_control = $id;
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
                return $this->respuesta([], 'No se ha pasado un numero de control valido', 400);
            $alumnoVerificada = $this->model->find($id);
            if ($alumnoVerificada == null) {
                return $this->respuesta([], 'No se ha encontrado el alumno con el numero control: ' . $id, 404);
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
    public function alumnoCarrera($carrera = null){
        try {
            if ($carrera == null) {
                return $this->respuesta([], 'No se ha especificado la carrera', 400);
            } else {
                $alumnos = $this->model->alumnosCarrera($carrera);
                if ($alumnos == null) {
                    return $this->error('No se ha encontrado la carrera.', 404);
                } else {
                    return $this->respuesta($alumnos, '', 200);
                }
            }
        } catch (\Exception $e) {
            return $this->respuesta([], $e->getMessage(), 500);
        } 
    }
    
    public function ValidaNoControl($no_control = null){
        try{
            if($no_control == null){
                return $this->respuesta([], 'No se ha especificado el numero control.', 400);
            }else{
                $alumno = $this->model->ValidaNoControl($no_control);
                if($alumno == false){
                    return $this->error('No se ha encontrado el alumno.', 404);
                }else{
                    return $this->respuesta($alumno, '', 200);
                }
            }
        }catch(\Exception $e){
            return $this->respuesta([], $e->getMessage(), 500);
        }
    }
    public function login(){
        try {
            $credenciales = $this->request->getJSON();
          
            if (empty($credenciales->no_control) || empty($credenciales->nip)) {
                return $this->errores('El numero control y el nip son requeridos', 400);
            }else{
                $no_control = $credenciales->no_control;
                $nip = $credenciales->nip;
                // $alumno = new Alumno();
                // $validateUsuario = $alumno->where('no_control', $no_control)->first();
                $alumno = $this->model->Acceso($no_control, $nip);
                if ($alumno == false) {
                    return $this->error('Datos erroneos.', 404, 'true');
                } else {
                    $data = $this->model->alumnoCarrera($no_control);
                    return $this->respuesta($data, '', 200);
                }
            }
        } catch (\Exception $e) {
            return $this->respuesta([], $e->getMessage(), 500);
        }
    }
    public function respuesta($data, $mensaje, $codigo){
        if ($codigo == 200) {
            return $this->respond(array(
                "status" => $codigo,
                "error" => false,
                "data" => $data
            ));
        } else {
            return $this->respond(array(
                "status" => $codigo,
                "error" => true,
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
        return $this->errores($error, 400, $codigo, $mensaje);
    }
}
