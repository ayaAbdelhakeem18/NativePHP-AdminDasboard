<?php 
include '../includes/templates/header.php'; 
include '../includes/templates/navbar.php'; 
include '../includes/db/db.php'; 

$statement=$connect->prepare("SELECT * FROM products");
$statement->execute();
$results = $statement->fetchAll();
$productCount = $statement->rowCount();

$page="all";

if(isset($_GET["page"])){
    $page=$_GET["page"];
}

if($page==="all"){
?>
<div id="deleteModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <!-- Modal content will be added here -->
</div>

<div class="container mt-5">
    <div class="card bg-dark text-white">
        <div class="card-header d-flex justify-content-between">
            <h5 class="card-title">Products <?php echo $productCount?></h5>
            <a href="?page=create" class="btn btn-success ">Create Product</a>
        </div>
        <div class="card-body">
            <table class="table table-dark table-hover table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Product name</th>
                        <th scope="col">Categorie Id</th>
                        <th scope="col">Creation date</th>
                        <th scope="col">Stock</th>
                        <th scope="col">Operations</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
              if($productCount>0){
                foreach($results as $row){

                $id=$row["product_id"];

                $stock="";

                if($row["stock"]==='0'){
                 $stock="Out of stock";
                }else{
                  $stock="In stock";
                }

            ?>

                    <tr>
                        <td><?php echo $row["product_id"]?></td>
                        <td><?php echo $row["Name"]?></td>
                        <td><?php echo $row["categorie_id"]?></td>
                        <td><?php echo $row["creation_date"]?></td>
                        <td><?php echo $stock?></td>

                        <td class="d-flex justify-content-between">
                            <a href="?page=show&id=<?php echo $row["product_id"];?>"> <i
                                    class="fa-solid fa-eye btn btn-primary"></i></a>
                            <a href="#" onclick="showDeleteModal(<?php echo $id; ?>)"> <i
                                    class="fa-solid fa-trash-can btn btn-danger"></i></a>
                        </td>
                    </tr>

                    <?php
              }
            }
            ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
}else if($page==="delete"){
    if(isset($_GET['id'])){

        $id = $_GET['id']; 
        
        $delete = $connect->prepare("DELETE FROM products WHERE product_id = :id");
        $delete->bindParam(':id', $id, PDO::PARAM_INT);
    
        $delete->execute();
    
        header("Location: products.php");
        exit;
    }
}else if($page==="show"){

    if(isset($_GET['id'])){

        $id = $_GET['id']; 
        $data="";

        foreach ($results as $row) {
            if ($row['product_id'] == $id) {
                $data = $row;
                break; 
            }
        }
    };
?>
<div class="container mt-5">
    <form method="post" action="?page=update&key=<?php echo $data["product_id"];?>">
        <div class="mb-3">
            <label for="id" class="form-label">Product ID</label>
            <input type="number" class="form-control" id="id" name="id" value=<?php echo $data["product_id"];?>>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="name" name="name" value=<?php echo $data["Name"];?>>
        </div>
        <div class="mb-3">
       <label for="categorie_id" class="form-label">Product categorie ID</label>
       <select class="form-control" id="cid" name="cid">
        <?php
        $query = "SELECT categorie_id FROM categories";
        $result = $connect->query($query);
        
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value=\"" . $row['categorie_id'] . "\">" . $row['categorie_id'] . "</option>";
        }
        ?>
    </select>
      </div>
        <div class="mb-3">
            <label for="creation_date" class="form-label">Creation date</label>
            <input type="date" class="form-control" id="creation_date" name="creation_date"
                value=<?php echo $data["creation_date"];?>>
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <select class="form-select" id="stock" name="stock">

                <option value="1" <?php if ($data["stock"] === "1") echo "selected"; ?>>In Stock</option>
                <option value="0" <?php if ($data["stock"] === "0") echo "selected"; ?>>Out of Stock</option>
            </select>
        <button name="save_data" type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
<?php
}else if($page==="update"){

    if(isset($_GET["key"])){
     if($_SERVER["REQUEST_METHOD"] === "POST"){
       if(isset($_POST["save_data"])){
           $key=$_GET["key"];
           $product_id=$_POST["id"];
           $product_name=$_POST["name"];
           $cat_id=$_POST["cid"];
           $stock=$_POST["stock"];
        
   
           $statement=$connect->prepare("UPDATE products SET `product_id`=? ,`Name`=?, `categorie_id`=? ,`creation_date`= NOW(),`stock`=?   WHERE product_id=? ");
           $statement->execute(array($product_id,$product_name,$cat_id,$stock,$key));
           
           echo "<h2 class='update alert-success'>Your data Got updated succefully</h2>";
           header("refresh:3;url=products.php");
           
   
       }
     } 
      }
   }else if($page==="create"){
   ?>
<div class="container mt-5">
    <h2 class="mb-3 text-center"> CREATE PRODUCT</h2>
    <form method="post" action="?page=created">
        <div class="mb-3">
            <label for="id" class="form-label">Product ID</label>
            <input type="text" class="form-control" id="id" name="id">
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="name" name="name">
        </div>
        <div class="mb-3">
    <label for="categorie_id" class="form-label">Product categorie ID</label>
    <select class="form-control" id="categorie_id" name="categorie_id">
        <?php
        $query = "SELECT categorie_id FROM categories";
        $result = $connect->query($query);
        
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value=\"" . $row['categorie_id'] . "\">" . $row['categorie_id'] . "</option>";
        }
        ?>
    </select>
</div>
        <div class="mb-3">
            <label for="date" class="form-label">Creation date</label>
            <input type="date" class="form-control" id="creation_date" name="creation_date">
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <select class="form-select" id="stock" name="stock">
                <option value="1">In Stock</option>
                <option value="0">Out of Stock</option>
            </select>

        </div>
        <button name="save_data" type="submit" class="btn btn-primary btn-block">Save</button>
    </form>
</div>
<?php
   }else if($page=="created"){
       if($_SERVER["REQUEST_METHOD"] === "POST"){
           if(isset($_POST["save_data"])){

            $product_id=$_POST["id"];
            $product_name=$_POST["name"];
            $cat_id=$_POST["categorie_id"];
            $stock=$_POST["stock"];
               
               $statement=$connect->prepare("INSERT INTO products (`product_id`,`Name`,`categorie_id`,`creation_date`,`stock`) VALUES (?, ?,?, Now(),?)" );

               $statement->execute(array($product_id,$product_name,$cat_id,$stock));
               
               echo "<h2 class='update alert-success'>You have created Product succefully</h2>";
               header("refresh:3;url=products.php");
               
       
           }
         } 
   }
include '../includes/templates/footer.php'; 
?>