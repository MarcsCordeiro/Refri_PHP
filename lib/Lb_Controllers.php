<?php
/**
 * Classe de controllers
 * @author Lucas Brito <lucas@libertynet.com.br>
 */

class Lb_Controllers{
    var $__data;
    var $_layout = "index";
    var $_pdo = null;
    var $_print_layout = true;
    var $_head = null;
    var $log = array();
    
    
    public function __get($name){
        return $this->__data[$name];
    }
    public function __set($name,$value){
        $this->__data[$name] = $value;
    }

    /**
     * Retorna nome do controlador
     * @return String Nome do controlador
     */
    public function getController(){
        return $GLOBALS['_page_go'];
    }
    
    /**
     * Retorna nome da action
     * @return String Nome da Action
     */
    public function getAction(){
        return $GLOBALS['_page_action'];
    }
    

    
    /**
     * Retorna layout para página
     */
    public function __get_layout(){
        // Verifica se deseja imprimir layout
        if($this->_print_layout){
            require $GLOBALS['_path_layout']."/".$this->_layout.".phtml";
        }else{
            // Imprime view
            $this->content();
        }
    }
    
    /**
     * Não imprimir layout
     */
    public function no_layout(){
        $this->_print_layout = false;
    }
    
    /**
     * Inicia conteudo
     */
    public function content() {
        ob_start();
        require $GLOBALS['_path_view']."/".$GLOBALS['_page_go']."/".$GLOBALS['_page_action'].".phtml";
        ob_flush();
    }
    
    
   

    /**
     * Seta novo elementos no cabeçalho
     * @param String $tag Tipo do elemento do cabeçalho (script,style)
     * @param String $href Caminho do elemento do cabeçalho
     */
    public function setHeader($tag,$href){
        switch($tag){
            case 'script':
                $html = '<script type="text/javascript" src="'.$href.'"></script>';
            break;
            case 'style':
                $html = '<link rel="stylesheet" href="'.$href.'" type="text/css"/>';
        }
        $this->_head = $html."\n";
    }
    
    /**
     * Seta novo script no cabeçalho
     * @param String $href Caminho do elemento para cabeçalho
     */
    public function setScript($href){
        $this->setHeader('script', $href);
    }
    
    /**
     * Seta novo style no cabeçalho
     * @param String $href Caminho do elemento para cabeçalho
     */
    public function setStyle($href){
        $this->setHeader('style', $href);
    }
    
    /**
     * Retorna codigo do cabeçalho
     * @return String Cabeçalho
     */
    public function getHeader(){
        return $this->_head;
    }
    
    
    ## Integração com PHP ##
    
    public function url($url = array("controller"=>"index","action"=>"index")){
        //return "index.php?go=".$url["controller"]."&action=".$url["action"]
        $_get = null;
        if(isset($url["controller"])==false && isset($url["go"])==false){
            $url["controller"] = $this->getController();
        }
        if(isset($url["action"])==false){
            $url["action"] = "index";
        }
        foreach($url as $param => $value){
            $param = str_replace("controller","go",$param);
            $_get .= $param."=".$value."&";
        }
        return "index.php?".$_get;
    } 

    /**
     * Redireciona para uma URL especifica
     * @param String $url
     */
    public function redirect($url){
        print '<script type="text/javascript">location.href="'.$url.'"</script>';
        exit;
    }
    
    
    /**
     * Protege contra SQL injection
     * @param String str
     */
    public function iSafe($str){
        $str = str_replace("'","\'",$str);
        $str = str_replace("\"", '\"', $str);
        return $str;
    }
    
    /**
     * Retorna valores $_POST
     * @param String $name Nome do campo
     * @param Boolean $protect Se deseja proteger contra SQL Injection
     */
    public function _POST($name = '',$protect = true){
        if (isset($_POST["$name"])){
            // Se deseja proteger
            $valor = ($protect) ? $this->iSafe($_POST["$name"]) : $_POST["$name"];
            return $valor;
        }else{
            return false;
        }
    }
    /**
     * Retorna valores $_GET
     * @param String $name Nome do campo
     */
    public function _GET($name = '',$protect = true){
        if (isset($_GET["$name"])){
            // Se deseja proteger
            $valor = ($protect) ? $this->iSafe($_GET["$name"]) : $_GET["$name"];
            return $valor;
        }else{
            return false;
        }
    }
    
    /**
     * Seta novo valor em sessão
     * @param String $name
     * @param String $value
     */
    public function set_session($name,$value){
        $_SESSION["$name"] = $value;
    }
    
    /**
     * Retorna valor de sessão
     * @param String $name
     */
    public function get_session($name){
        if(isset($_SESSION["$name"])){
            return $_SESSION["$name"];
        }else{
            return false;
        }
    }

    /**
     * Renderiza views
     * @param String $_page Página para renderiza
     */
    public function render($_page = null){
        
        // Diretorio de libs
        $_dir = dirname(__FILE__);
        // Retorna ao diretorio principal e entra nas views
        $_views = $_dir."/../views";
        
        // Verifica se arquivo existe
        if(file_exists($_views."/".$_page)){
            require $_views."/".$_page;
        }else{ // Mostra mensagem de erro na página
            print "A página '$_views/$_page' não existe";
        }
       
    }
    
    /**
     * Mensagem de alerta Javascript
     * @param type $text Descrição da mensagem
     */
    public function getMessage($text = null){
        print '<script type="text/javascript">alert("'.$text.'")</script>';
    }
    
      /**
     * Criptografado
     * @param type $value
     * @return type
     */
    public  function setCrypty($value){
        
        $str = "liberty@$@".substr(md5($value),0,5)."@$@$value@$@".rand(0,100)."@$@".substr(md5(rand(0,30)),0,5);
        
        return base64_encode($str);
        
    }
    
    /**
     * Retorna descriptografado
     * @param type $value
     * @return type
     */
    public  function getCrypty($value){
        $str = base64_decode($value);
        
        $str_explode = explode("@$@",$str);
        $value = $str_explode[2];
        return $value;
    }

    public function paginacao_range($pagina,$paginas_por_bloco = 10){
        if($pagina <= $paginas_por_bloco){
            return [1,$paginas_por_bloco];
        }
        else{
            for($i= $pagina-1;$i>=$paginas_por_bloco;$i--){
                if($i % $paginas_por_bloco == 0){
                    $inicio = $i+1;
                    break;
                }
            }
            $i = $pagina +1;
            while(true){
                if($i % $paginas_por_bloco == 0){
                    $fim = $i;
                    break;
                }
                $i++;
            }
            return [$inicio,$fim];
        }
    }


    /**
    Retorna paginação 
    */
    public  function paginacao($total,$linhas_por_pagina = 30,$paginas_por_bloco = 10){

        

        

        // Total de paginas possíveis
        $total_paginas  = ceil($total / $linhas_por_pagina);
        // Total de blocos possíveis
        $total_blocos = ceil($total_paginas / $paginas_por_bloco);
        // Página atual
        $pagina = $this->_GET("lispg") ? $this->_GET("lispg") : 1;



        list($inicio,$fim) = $this->paginacao_range($pagina,$paginas_por_bloco);
        $pagination = null;

        $pagination.= '<ul class="pagination">';
        $pagination.= '<li class="page-item">';
        $_GET['lispg'] = 1;
        $pagination.='<a class="page-link " href="'.$this->url($_GET).'" aria-label="Anterior">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Anterior</span> 
                     </a>';
        $pagination.= '</li>';
        if($pagina!=1):
      
        if($inicio != 1){  
        $pagination.= '<li class="page-item">';
        $_GET['lispg'] = $inicio -1;
        $pagination.= '<a class="page-link" href="'.$this->url($_GET).'"><i class="fa fa-angle-double-left"></i></a>';
        $pagination.= '</li>';  
        }
        
            $pagination.= '<li class="page-item">';
            $_GET['lispg'] = $pagina-1;
            $pagination.= '<a class="page-link" href="'.$this->url($_GET).'"><i class="fa fa-angle-left"></i></a>';
            $pagination.= '</li>';  
        
        endif;


        for($i=$inicio;$i<=$fim;$i++){
            
            $active = $pagina == $i  ? 'active' : null;
            if($i <= $total_paginas){
                $pagination.= '<li class="page-item" class="'.$active.'">';
                $_GET['lispg'] = $i;
                $pagination.= '<a class="page-link" href="'.$this->url($_GET).'">'.$i.'</a>';
                $pagination.= '</li>';  
            }else{
                $fim = $i - 1;
                break;
            }
        }


        if($pagina!=$total_paginas):
        $pagination.= '<li class="page-item">';
        $_GET['lispg'] = $pagina+1;
        $pagination.= '<a class="page-link" href="'.$this->url($_GET).'"><i class="fa fa-angle-right"></i></a>';
        $pagination.= '</li>';  
        
        if($fim != $total_paginas){
            $pagination.= '<li class="page-item">';
            $_GET['lispg'] = $i;
            $pagination.= '<a class="page-link" href="'.$this->url($_GET).'"><i class="fa fa-angle-double-right"></i></a>';
            $pagination.= '</li>';    
        }
        
        $pagination.= '<li>';
        $_GET['lispg'] = $total_paginas;
        $pagination.='<a class="page-link" href="'.$this->url($_GET).'" aria-label="Próximo">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Próximo</span>
                      </a>';
        $pagination.= '</li>';               
        
        endif;

        $pagination.= '</ul>';


        $inicio_limit = "LIMIT ".( ($pagina * $linhas_por_pagina) - $linhas_por_pagina).",".$linhas_por_pagina;

        return [
        "limit"=>$inicio_limit,
        "html"=>$pagination
        ];


    }
    
}

