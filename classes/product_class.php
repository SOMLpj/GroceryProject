<?php

require_once 'database_class.php';

class Product {
    private $conn;

    // Constructor
    public function __construct(){
      $database = new Database();
      $db = $database->dbConnection();
      $this->conn = $db;
    }


    // Execute queries SQL
    public function runQuery($sql){
      $stmt = $this->conn->prepare($sql);
      return $stmt;
    }

    // Insert
    public function insert($product_name, $price, $discount, $unit, $weight_per_item, $item_per_pack, $shipping_weight, $description, $image, $stock, $FK_category_id){
      try{
        $stmt = $this->conn->prepare("INSERT INTO product (product_name, price, discount, unit, weight_per_item, item_per_pack, shipping_weight, description, image, stock, FK_category_id) VALUES(:product_name, :price, :discount, :unit, :weight_per_item, :item_per_pack, :shipping_weight,  :description, :image, :stock, :FK_category_id)");
        $stmt->bindparam(":product_name", $product_name);
        $stmt->bindparam(":price", $price);
        $stmt->bindparam(":discount", $discount);
        $stmt->bindparam(":unit", $unit);
        $stmt->bindparam(":weight_per_item", $weight_per_item);
        $stmt->bindparam(":item_per_pack", $item_per_pack);
        $stmt->bindparam(":shipping_weight", $shipping_weight);
        $stmt->bindparam(":description", $description);
        $stmt->bindparam(":image", $image);
        $stmt->bindparam(":stock", $stock);
        $stmt->bindparam(":FK_category_id", $FK_category_id);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
        echo $e->getMessage();
      }
    }


    // Update
    public function update($product_name, $price, $discount, $unit, $weight_per_item, $item_per_pack, $shipping_weight, $description, $image, $stock, $FK_category_id, $product_id){
      try {
        $stmt = $this->conn->prepare("UPDATE product SET product_name = :product_name, price = :price, discount = :discount, unit = :unit, weight_per_item = :weight_per_item, item_per_pack = :item_per_pack, shipping_weight = :shipping_weight, description = :description, image = :image, stock = :stock, FK_category_id = :FK_category_id WHERE product_id = :product_id");
        $stmt->bindparam(":product_name", $product_name);
        $stmt->bindparam(":price", $price);
        $stmt->bindparam(":discount", $discount);
        $stmt->bindparam(":unit", $unit);
        $stmt->bindparam(":weight_per_item", $weight_per_item);
        $stmt->bindparam(":item_per_pack", $item_per_pack);
        $stmt->bindparam(":shipping_weight", $shipping_weight);
        $stmt->bindparam(":description", $description);
        $stmt->bindparam(":image", $image);
        $stmt->bindparam(":stock", $stock);
        $stmt->bindparam(":FK_category_id", $FK_category_id);
        $stmt->bindparam(":product_id", $product_id);
        $stmt->execute();
        return $stmt;
      } catch (PDOException $e) {
        echo $e->getMessage();
      }
    }


    // Delete
    public function delete($product_id){
      try {
        $stmt = $this->conn->prepare("DELETE FROM product WHERE product_id = :product_id");
        $stmt->bindparam(":product_id", $product_id);
        $stmt->execute();
        return $stmt;
      } catch (PDOException $e) {
        echo $e->getMessage();
      }
    }


    // Redirect URL method
    public function redirect($url){
      header("Location: $url");
    }

}
?>
