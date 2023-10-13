<?php
namespace App\Controllers;
use App\Models\ActividadComplementaria;
use CodeIgniter\RESTful\ResourceController;

class ActComplementarias extends ResourceController
{
    public function __construct() { 
        $this->model = new ActividadComplementaria();
    }
    
    public function index()
    {
        try {
            $acts = $this->model->findAll();
            return $this->respuesta($acts,'',200);
        } catch (\Exception $e){
            return $this->respuesta([],$e->getMessage(),500);
        }
    }

  
    public function show($id = null)
    {
        try {
            if($id == null){
                return $this->respuesta([],'No se ha especificado el Id',400);
            }else{
                $act = $this->model->find($id);
                if($act == null){
                    return $this->respuesta([],'No se ha encontrado la actividad complementaria',404);
                }else{
                    return $this->respuesta($act, '', 200);
                }
            }
        } catch (\Exception $e) {
            return $this->respuesta([],$e->getMessage(),500);
        }
    }

    
    public function create()
    {
        try {
            $act = $this->request->getJSON();
            $data =[
                'act_gral' => $act->act_gral,
                'act_especifica' => $act->act_especifica,
                'credito' => $act->credito,
                'lugar' => $act->lugar,
                'num_participantes' => $act->num_participantes,
                'tiempo' => $act->tiempo,
                'descripcion' => $act->descripcion,
                'tipo' => $act->tipo,

            ];
            if($this->model->insert($data)):
                $act->id = $this->model->insertID();
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
                $actividadVerificada = $this->model->find($id);
                if ($actividadVerificada == null) {
                    return $this->respuesta([], 'No se ha encontrado la actividad complementaria con el id: ' . $id, 404);
                }
                $act = $this->request->getJSON();
                $data = [
                    'act_gnral' => $act->act_gnral,
                    'act_especifica' => $act->act_especifica,
                    'credito' => $act->credito,
                    'lugar' => $act->lugar,
                    'num_participantes' => $act->num_participantes,
                    'tiempo' => $act->tiempo,
                    'descripcion' => $act->descripcion,
                    'tipo' => $act->tipo,

                ];
                if ($this->model->update($id, $data)) :
                    $act->id = $id;
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
            if($id == null)
                return $this->respuesta([],'No se ha pasado un Id valido',400);
            $actividadVerificada = $this->model->find($id);
            if($actividadVerificada == null){
                return $this->respuesta([],'No se ha encontrado la actividad complementaria con el id: ' . $id,404);
            }
            if($this->model->delete($id)):
                return $this->respuesta('Se ha eliminado Correctamente!','',200);
            else:
                return $this->respuesta([],'No se ha podido eliminar el registro',404);
            endif;

        } catch (\Exception $e) { 
            return $this->respuesta([],$e->getMessage(),500);
        } 
    }
    public function respuesta($data, $mensaje, $codigo) {
        if ($codigo==200) {
            return $this->respond(array(
                "status" => $codigo,
                "data" => $data
            ));
        }else{
            return $this->respond(array(
                "status" => $codigo,
                "data" => $mensaje
            ));
        }
    }
}

