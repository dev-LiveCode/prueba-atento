<?php
  class ConecDB {

    private $host = 'localhost:3306';
    private $dbname = 'atento';
    private $username = 'root';
    private $password = '';
    private $connection;

    public function __construct()
    {
      $this->conexion();
    }
    
    public function conexion(){
      // Intentar establecer la conexión utilizando PDO
      try {
          $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8';
          $this->connection = new PDO($dsn, $this->username, $this->password);
      
          // Configurar el modo de manejo de errores para lanzar excepciones
          $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      
          return $this->connection;
          
      } catch (PDOException $e) {
          die("Error al conectar a la base de datos: " . $e->getMessage());
      }
    }

    public function getConnection() {
      return $this->connection;
    }

    public function closeConnection() {
      $this->connection = null;
    }
  }

?>