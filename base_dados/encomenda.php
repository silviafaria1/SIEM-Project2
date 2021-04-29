<?php
  //includes
  include_once 'includes/db_connection.php';
  include_once 'includes/generic_querys.php';
  include_once 'utilizador.php';

  class Encomenda{
    private $dataBase=''; // data base connection and query execution
    private $encomenda="encomenda"; //table name

    //colunas
    private $id_utilizador='id_utilizador';
    private $data_hora='data_hora';
    private $metodo_pagamento='metodo_pagamento';
    private $id='id';
    private $total='total';

    function __construct(){
      $this->dataBase=new DataBase();
    }

    function createEncomenda($id_utilizador,$data_hora,$metodo_pagamento, $total){
      //make sure id_utilizador exists
      if(! (new Utilizador() )->validUserID($id_utilizador))
        return;

      $query = "INSERT INTO $this->encomenda
                ($this->id_utilizador, $this->data_hora, $this->metodo_pagamento, $this->total)
                values ('$id_utilizador', '$data_hora', '$metodo_pagamento', '$total')";

      //Insert encomenda into database
      if ( $this->dataBase->executeQuery($query) )
      //Fetch encomenda id from metodo_pagamento
        return true;
      else
        return false;
    }

    private function createUpdateQuery($id_utilizador,$data_hora,$metodo_pagamento, $total){
      $first=true;
      $query = "UPDATE $this->encomenda SET";

      if(isset($id_utilizador)){
        $query=$query."$this->id_utilizador='$id_utilizador'";
        $first=false;
      }

      if (isset($data_hora)){
        if($first){
          $query=$query."$this->data_hora='$data_hora'";
          $first=false;
        }
        else
          $query=$query.", $this->data_hora='$data_hora'";
      }

      if (isset($metodo_pagamento)){
        if($first){
          $query=$query."$this->metodo_pagamento='$metodo_pagamento'";
          $first=false;
        }
        else
          $query=$query.", $this->metodo_pagamento='$metodo_pagamento'";
      }

      if (isset($total)){
        if($first){
          $query=$query."$this->total='$total'";
          $first=false;
        }
        else
          $query=$query.", $this->total='$total'";
      }

      return $query;
    }

    function updateEncomendaByID($id,$id_utilizador,$data_hora,$metodo_pagamento, $total){
      // can't find / update  the id
      if(!$this->getEncomendaById($id))
        return false;
      else if ($total==null && $id_utilizador==null && $id_utilizador==null && $metodo_pagamento==null && $data_hora== null  )
        return false;

      //make sure id_utilizador exists
      if(! (new Utilizador() )->validUserID($id_utilizador))
        return false;

      $query= $this->createUpdateQuery($id_utilizador,$data_hora,$metodo_pagamento, $total);

      return $this->dataBase->executeQuery($query." WHERE $this->id=$id");
    }

    function deleteEncomenda($id){
      $query = "DELETE FROM $this->encomenda
                WHERE $this->id=$id";

      return $this->dataBase->executeQuery($query);// true on success
    }

    function getAllEncomendas(){
      $query = getAllRowsQuery($this->encomenda);
      $result =  $this->dataBase->executeQuery($query);

      if(isset($result))
        return getElements($result, $this->id);
      else
        return false;
    }

    function getEncomendasByUser1($id_utilizador){
      //make sure id_utilizador exists
      $this->utilizador= new Utilizador();
      if(! $this->utilizador->validUserID($id_utilizador))
        return;

      $query = " SELECT *
                 FROM $this->encomenda
                 WHERE $this->id_utilizador=$id_utilizador";

      $result =  $this->dataBase->executeQuery($query);
      // encomendas efetuadas pelo utilizador de id id_utilizador
      if(isset($result))
        return getElements($result, $this->id);
      else
        return false;
    }

    function getEncomendaById($id){
      $query = " SELECT *
                 FROM $this->encomenda
                 WHERE $this->id=$id";

      $row = pg_fetch_assoc ($this->dataBase->executeQuery($query) );

      if (isset($row[$this->id]))
        return $row[$this->id];
      else{
        return false;
      }
    }

    private function getEncomendasByUser($id_utilizador){
      //make sure id_utilizador exists
      $this->utilizador= new Utilizador();
      if(! $this->utilizador->validUserID($id_utilizador))
        return;

      $query = " SELECT *
                 FROM $this->encomenda
                 WHERE $this->id_utilizador=$id_utilizador";

      // encomendas efetuadas pelo utilizador de id id_utilizador
      return $this->dataBase->executeQuery($query) ;
    }

    function getNumberEncomendas($id_utilizador){
      return getArrayLength($this->getEncomendasByUser($id_utilizador));
    }

    function getEncomendasIds($id_utilizador){
      return getElements( $this->getEncomendasByUser($id_utilizador), 'id' );
    }


    private function getEncomendaDetails($id_encomenda, $id_utilizador){
      //make sure id_utilizador exists
      if(! (new Utilizador() )->validUserID($id_utilizador))
        return;

      $query = " SELECT *
                 FROM $this->encomenda
                 WHERE $this->id_utilizador=$id_utilizador and $this->id=$id_encomenda ";

      //detalhes de encomenda
      return $this->dataBase->executeQuery($query);
    }

    private function getEncomendaDetails1($id_encomenda){
      $query = " SELECT *
                 FROM $this->encomenda
                 WHERE $this->id=$id_encomenda ";

      //detalhes de encomenda
      return $this->dataBase->executeQuery($query);
    }


    function getEncomendaData($id_encomenda, $id_utilizador){
      //make sure id_utilizador exists
      if(! (new Utilizador() )->validUserID($id_utilizador))
        return;

      $row = pg_fetch_assoc ($this->getEncomendaDetails($id_encomenda,$id_utilizador));

      if (isset($row[$this->data_hora]))
        return $row[$this->data_hora];
      else
        return false;
    }

    function getEncomendaData1($id_encomenda){
      $row = pg_fetch_assoc ( $this->getEncomendaDetails1($id_encomenda));

      if (isset($row[$this->data_hora]))
        return $row[$this->data_hora];
      else
        return false;
    }

    function getEncomendaTotal1($id_encomenda){
      $row = pg_fetch_assoc($this->getEncomendaDetails1($id_encomenda));

      if (isset($row[$this->total]))
        return $row[$this->total];
      else
        return false;
    }

    function getEncomendaUserID($id_encomenda){
      $query = " SELECT *
                 FROM $this->encomenda
                 WHERE $this->id=$id_encomenda";

      $row =  pg_fetch_assoc ($this->dataBase->executeQuery($query) );

      if (isset($row[$this->id_utilizador]))
        return $row[$this->id_utilizador];
      else
        return false;
    }

    function getEncomendaMetodoPagamento($id_encomenda, $id_utilizador){
      //make sure id_utilizador exists
      if(! (new Utilizador() )->validUserID($id_utilizador))
        return false;

      $row= pg_fetch_assoc ( $this->getEncomendaDetails($id_encomenda,$id_utilizador));

      if (isset($row[$this->metodo_pagamento]))
        return $row[$this->metodo_pagamento];
      else
        return false;
    }

    function getEncomendaTotal($id_encomenda, $id_utilizador){
      //make sure id_utilizador exists
      if(! (new Utilizador() )->validUserID($id_utilizador))
        return false;

      $row= pg_fetch_assoc ( $this->getEncomendaDetails($id_encomenda,$id_utilizador));

      if (isset($row[$this->total]))
        return $row[$this->total];
      else
        return false;
    }

    private function filterEncomendaByMetodoPagamento($metodo_pagamento){
      $query = filterQuery($this->encomenda, $this->metodo_pagamento)." '$metodo_pagamento' ";
      return $this->dataBase->executeQuery($query);
    }

    private function filterEncomendaByDataHora($data_hora){
      $query= filterQuery($this->encomenda, $this->data_hora)." '$data_hora' ";
      return $this->dataBase->executeQuery($query);
    }

    private function filterEncomendaByUtilizador($username){
      $utilizador= new Utilizador();
      $id_user = $utilizador->getUserID($username);
      $query= "SELECT *
               FROM $this->encomenda
               WHERE $this->id_utilizador= $id_user";

      return $this->dataBase->executeQuery($query);
    }

    function getNumberEncomendasByFilter($data_hora,$metodo_pagamento, $username){
      if($data_hora!=null){
        return getArrayLength($this->filterEncomendaByDataHora($data_hora));
      }
      else if($metodo_pagamento!=null){
        return getArrayLength($this->filterEncomendaByMetodoPagamento($metodo_pagamento));
      }
      else if($username!=null)
        return getArrayLength($this->filterEncomendaByUtilizador($username));
      else
        return null;
    }

    function getIdEncomendasByFilter($data_hora,$metodo_pagamento, $username){
      if($data_hora!=null){
        return getElements($this->filterEncomendaByDataHora($data_hora),$this->id);
      }
      else if($metodo_pagamento!=null){
        return getElements($this->filterEncomendaByMetodoPagamento($metodo_pagamento), $this->id);
      }
      else if($username!=null){
        return getElements($this->filterEncomendaByUtilizador($username), $this->id);
      }
    }

    function filterBy($field){
      $query=orderByQuery($this->encomenda,$field,false,false);

      $result =  $this->dataBase->executeQuery($query)  ;

      if(null!=($result))
        return getElements($result, $this->id);//get ids
      else
        return false;
    }

    function getEncomendasPorDiaMedia(){
      $query = " SELECT SUM(id)/COUNT(id) as resultado
                FROM (
                  SELECT COUNT( CAST($this->data_hora as DATE)) as id
                            FROM encomenda  
                            group by CAST($this->data_hora as DATE)
                ) as AUX";

     
      $row =  $this->dataBase->executeQuery($query)  ;
      $row= pg_fetch_assoc ($row);
      if (isset($row['resultado']))
        return $row['resultado'];
      else
        return false;
    }

    function getTotalVendas(){
      $query="SELECT sum(total) as total FROM encomenda ";
      $row =  $this->dataBase->executeQuery($query)  ;
      $row= pg_fetch_assoc ($row);
      if (isset($row['total']))
        return $row['total'];
      else
        return false;
    }
    function getEncomendasPorDia_Data(){
      $query = "SELECT COUNT( CAST($this->data_hora as DATE)) as resultado, CAST(data_hora as DATE)
                FROM encomenda  
                group by CAST($this->data_hora as DATE)";

      $row =  $this->dataBase->executeQuery($query)  ;
      if(null!=($row))
      return getElements($row, 'data_hora');//get ids
      else
        return false;

    }
    function getEncomendasPorDia_Count(){
      $query = "SELECT COUNT( CAST($this->data_hora as DATE)) as resultado, CAST(data_hora as DATE)
                FROM encomenda  
                group by CAST($this->data_hora as DATE)";
  
      $row =  $this->dataBase->executeQuery($query)  ;
      if(null!=($row))
        return getElements($row, 'resultado');//get ids
      else
        return false;
    }
    function getEncomendasPorDia_Total(){
      $query = "SELECT SUM(total) as total, CAST(data_hora as DATE) FROM (
                SELECT COUNT( CAST($this->data_hora as DATE)) as resultado, CAST(data_hora as DATE), total
                FROM encomenda  
                group by CAST($this->data_hora as DATE), total
                ) as aux
                group by CAST(data_hora as DATE)";
      $row =  $this->dataBase->executeQuery($query)  ;
      if(null!=($row))
        return getElements($row, 'total');//get ids
      else
        return false;
    }

    function getPizzaDoDiaID($date){

      $query= "SELECT SUM(quantidade) as quantidade, id_pizza from (
                    SELECT COUNT( id ) as count, id_pizza, quantidade 
                    from (SELECT * from encomenda inner join pertence on id_encomenda=encomenda.id) as pizzas 
                    where CAST(data_hora as DATE)='$date' group by id_pizza, quantidade order by count desc  )
                    as aux 
                    group by id_pizza
                    order by quantidade DESC
                    LIMIT 1
              ";

      $result =  $this->dataBase->executeQuery($query)  ;

      $row= pg_fetch_assoc ($result);

      if (isset($row['id_pizza']))
        return $row['id_pizza'];
      else
        return false;
    }
   
    function getEncomendaPorUtilizador_ID(){
      $query = "SELECT COUNT(distinct  id_encomenda) as total, id_utilizador from
                (
                SELECT * from encomenda
                
                inner join pertence
                
                on id_encomenda=encomenda.id
                
                ) as AUX
                
                inner join utilizador
                on id_utilizador=utilizador.id
                group by id_utilizador
                ORDER BY total DESC";
    
        $result =  $this->dataBase->executeQuery($query)  ;
                
        if(null!=($result))
          return getElements($result, 'id_utilizador');
        else
          return false;
    }
    

    function getEncomendaPorUtilizador_Total(){
      $query = "SELECT COUNT(distinct  id_encomenda) as total, id_utilizador from
                (
                SELECT * from encomenda
                
                inner join pertence
                
                on id_encomenda=encomenda.id
                
                ) as AUX
                
                inner join utilizador
                on id_utilizador=utilizador.id
                group by id_utilizador
                ORDER BY total DESC";
        
        $result =  $this->dataBase->executeQuery($query)  ;

        if(null!=($result))
          return getElements($result, 'total');
        else
          return false;
    }

    function getEncomendaPorUtilizador_Venda(){
      $query= "SELECT count(distinct id_encomenda ) count, id_utilizador, sum(distinct total) 
              from 
                    ( SELECT encomenda.id, encomenda.total, encomenda.id_utilizador, pertence.id_encomenda 
                    from encomenda inner join pertence on id_encomenda=encomenda.id ) 
              as AUX 
              inner join utilizador on id_utilizador=utilizador.id  
              group by id_utilizador
              order by count DESC";

      $result =  $this->dataBase->executeQuery($query)  ;

      if(null!=($result))
        return getElements($result, 'sum');
      else
        return false;
    }

  }
?>
