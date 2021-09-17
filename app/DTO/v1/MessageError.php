<?php

namespace App\DTO\v1;


class MessageError {


public $error_code;
public $message;

public function set_error_code($code){
 $this->error_code=$code;
}

public function set_error_message($message){
    $this->message=$message;
   }

   public function get_error_message(){
       return $this->message;
   }

   public function get_error_code(){
    return $this->code;
}

}