<?php 

class Response{

    public  $response = [
        'status' => "successfully",
        "result" => array()
    ];

    public function error200($msg = "Datos incorrectos"){
        $this->response['status'] = "error";
        $this->response['result'] = array(
            "status" => "200",
            "error_msg" => $msg
        );
        return json_encode($this->response);
    }

    public function successfully($msg = "Operacion realizada con exito"){
        $this->response['status'] = "successfully";
        $this->response['result'] = array(
            "status" => "200",
            "msg" => $msg
        );
        return json_encode($this->response);
    } 


    public function error400($msg = "Datos enviados incompletos o con formato incorrecto"){
        $this->response['status'] = "error";
        $this->response['result'] = array(
            "status" => "400",
            "error_msg" => $msg
        );
        return json_encode($this->response);
    }

    public function error401($msg = "No autorizado"){
        $this->response['status'] = "error";
        $this->response['result'] = array(
            "status" => "491",
            "error_msg" => $msg
        );
        return json_encode($this->response);
    }

    public function error405(){
        $this->response['status'] = "error";
        $this->response['result'] = array(
            "status" => "405",
            "error_msg" => "Metodo no autorizado"
        );
        return json_encode($this->response);
    }


    public function error500($msg = "Error interno del servidor"){
        $this->response['status'] = "error";
        $this->response['result'] = array(
            "status" => "500",
            "error_msg" => $msg
        );
        return json_encode($this->response);
    }
    
}

?>