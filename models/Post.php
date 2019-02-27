<?php 
  class Post {
    // DB grejer
    private $conn;
    private $table = 'jos_shopper_products';

    // Skicka Properties
    public $id;
    public $name;
    public $price;
   
    public function __construct($db) {
      $this->conn = $db;
    }

    // Läsa grejer
    public function read() {
      // Sql frågan
      // SEELECT * FROM jos_shopper_products // Hämta allt som ligger i tabelen
      $query = 'SELECT id, name, price FROM jos_shopper_products';
      
   
      $stmt = $this->conn->prepare($query);

      // Kör sql frågan
      $stmt->execute();

      return $stmt;
    }

    
  }