<?php

/**
 * Exemplo de código Index
 * @author Name  <email>
 */
class Cadastrar_Controller extends Lb_Controllers {

    protected $_db;
    public $_sql_resp = null;
    
    public function init() {
       
        $this->title = "Cadastrar";
        $this->Refri = new Refri_Base($this->_pdo);
    }

    public function index() {
                    
    }

    public function add(){

        try{
            $id = $this->_POST('id');
            $marca = $this->_POST('marca');
            $sabor = $this->_POST('sabor');
            $tipo = $this->_POST('tipo');
            $volume = $this->_POST('volume');
            $valor = $this->_POST('valor');
            $quantidade = $this->_POST('quantidade');


            $dados = [
                'marca' => $marca,
                'sabor' => $sabor,
                'tipo' => $tipo,
                'volume' => $volume,
                'valor' => $valor,
                'quantidade' => $quantidade
            ];

        
            if($id != '') {

                $this->Refri->update($dados, $id);

                $message = 'Dados atualizados com sucesso!'; 

            }else{

                $this->Refri->insert($dados);

                $message = 'Dados inseridos com sucesso!';   
            }


            }catch(\Exception $e){
                die($e);
            }

            $this->redirect($this->url(array("go"=>"cadastrar","action"=>"list")));
                  
    }

    public function list(){

        $search = $this->_GET('search');
        
        $filter = '';
        if($search != ''){
            $filter = " AND marca LIKE '%{$search}%' "; 
        }
        
        $sql = "SELECT * FROM refri WHERE 1 {$filter} ";
        
       
        $this->number_line = 10;
        $list = $this->_pdo->query($sql);
        $this->rows = $list->rowCount();
        $this->pagination = $this->paginacao($this->rows, $this->number_line);
        
        $this->list_paginate = $this->_pdo->query($sql.$this->pagination['limit'])->fetchAll(PDO::FETCH_ASSOC);
           
    }


    public function deletar(){

        $id = $this->_POST('id');

        if ($id) {
            $this->Refri->delete($id);
        }

        exit;

    }

    
}	


?>
