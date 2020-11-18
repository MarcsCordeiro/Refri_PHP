<?php

/**
 * Exemplo de código Index
 * @author Name  <email>
 */
class User_Controller extends Lb_Controllers {

    
    public function init() {
       
        $this->title = "Usuarios";
        $this->User = new User_Base($this->_pdo);
    }

    public function index() {
        
    }

    public function add(){
        try{

            $name = $this->_POST('name');
            $email = $this->_POST('email');

            $dados = [
                'name' => $name,
                'email' => $email
            ];

            $this->User->insert($dados);

            

        }catch(\Exception $e){
            die($e);
        }
    }

    


	
}

?>