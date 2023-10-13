<?php

namespace App\Controllers;

use App\Models\Carrera;
use CodeIgniter\RESTful\ResourceController; 

class Carreras extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function __construct() {
        $this->model = $this->setModel(new Carrera());
    }

    public function index()
    {
        try {
            // $carreras = $this->model->findAll();
            $carreras = $this->model->carreras();
            return $this->respuesta($carreras,'',200);
        } catch (\Exception $e){
            return $this->respuesta([],$e->getMessage(),500);
        }
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        try {
            if($id == null){
                return $this->respuesta([],'No se ha especificado el Id',400);
            }else{
                $carreras = $this->model->find($id);
                if($carreras == null){
                    return $this->respuesta([],'No se ha encontrado la carrera',404);
                }else{
                    return $this->respuesta($carreras, '', 200);
                }      
            }
        } catch (\Exception $e) {
            return $this->respuesta([],$e->getMessage(),500);
        }
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        try {
            $carreras = $this->request->getJSON();
            $data =[
                'nombre' => $carreras->nombre,
                'nombre_corto' => $carreras->nombre_corto,
                'jdepto' => $carreras->jdepto, 
            ];
            if($this->model->insert($data)):
                $carreras->id = $this->model->insertID();
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
                $carreraVerificada = $this->model->find($id);
                if($carreraVerificada == null){
                    return $this->respuesta([],'No se ha encontrado una carrera con el id: '.$id,404);
                }   
                $carreras = $this->request->getJSON();
                $data =[
                    'nombre' => $carreras->nombre,
                    'nombre_corto' => $carreras->nombre_corto,
                    'jdepto' => $carreras->jdepto, 
                ];
                if($this->model->update($id, $data)):
                    $carreras->id = $id;
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
                return $this->respuesta([],'No se ha pasado un Id valido',400);
            $carreraVerificada = $this->model->find($id);
            if($carreraVerificada == null){
                return $this->respuesta([],'No se ha encontrado la carrera con el id: ' . $id,404);
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
