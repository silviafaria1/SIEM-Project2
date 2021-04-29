
<?php
  class DataBase{
    // Properties
    private $servername = "db.fe.up.pt", $username = "siem2052", $password = "OnJcnSnC";
    private $conn;
    private $schema="pizzeria";

    //Methods
    function __construct(){//constructor
      $this->conn = pg_connect("host=$this->servername dbname=$this->username user=$this->username password=$this->password");

      if(!$this->conn){
        echo "Ligação não foi estabelecida";
      }
      else
        $this->setSchema();
      }

      private function setSchema(){
        $query = "SET SCHEMA '$this->schema'";
        return pg_exec($this->conn, $query); // return query result
      }

      function executeQuery($query){
        return pg_exec($this->conn, $query);//true if success
      }
  }
?>
