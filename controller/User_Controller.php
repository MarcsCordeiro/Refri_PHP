<?php

/**
 * Exemplo de código Index
 * @author Name  <email>
 */
class User_Controller extends Lb_Controllers {

    
    public function init() {
       
        $this->title = "User";
        $this->User = new User_Base($this->_pdo);
    }

    public function index() {
        
    }

    public function add(){
       
        $usuario = $this->_POST('usuario');
        $senha = $this->_POST('senha');
        
        $dados = [
            'usuario' => $usuario,
            'senha' => $senha
        ]; 

        $sql = "SELECT id FROM user WHERE usuario = '$usuario' ";
        $resultado = $this->_pdo->query($sql);
        $pesquisaUsuario = $resultado->rowCount();
        
            
        if($pesquisaUsuario > 0){  //Verifica se tem usuários cadastrados
            echo 1;
        }else{ 
            $this->User->insert($dados); 
            echo 0;         
        }
                
            
    }

    public function verificar(){

        $name = $this->_POST('name');
        $senha = $this->_POST('senha');
       
        $sql = "SELECT id FROM user WHERE usuario = '$usuario' AND senha = '$senha' ";
        $resultado = $this->_pdo->query($sql);
        $pesquisaLogin = $resultado->rowCount();

        if($pesquisaLogin > 0){  //O USUÁRIO ENCONTRADO,REALIZAR LOGIN

            if(!isset($_SESSION)){
                session_start();
            }
            $_SESSION['usuario'] = $usuario;
            echo 1;
        }else{          //USUÁRIO OU SENHA INVÁLIDA
            echo 0;  
        }
    }
}

?>