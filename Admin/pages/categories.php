<?php 
include '../includes/templates/header.php'; 
include '../includes/templates/navbar.php'; 
include '../includes/db/db.php'; 

$statement=$connect->prepare("SELECT * FROM categories");
$statement->execute();
$results = $statement->fetchAll();
$catCount = $statement->rowCount();

$page="all";

if(isset($_GET["page"])){
    $page=$_GET["page"];
}

if($page==="all"){
?>
<div id="deleteModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <!-- Modal content will be added here -->
</div>

<div class="container mt-5">
    <div class="card bg-dark text-white">
        <div class="card-header d-flex justify-content-between">
            <h5 class="card-title">Categories <?php echo $catCount?></h5>
            <a href="?page=create" class="btn btn-success " >Create Categorie</a>
        </div>
        <div class="card-body">
            <table class="table table-dark table-hover table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Title</th>
                        <th scope="col">Creation date</th>
                        <th scope="col">Operations</th>
                    </tr>
                </thead>
                <tbody>
            <?php
              if($catCount>0){
                foreach($results as $row){
                    $id=$row["categorie_id"];

            ?>   

           <tr>
               <td><?php echo $row["categorie_id"]?></td>
               <td><?php echo $row["categorie_name"]?></td>
               <td><?php echo $row["creation_date"]?></td>

               <td class="d-flex justify-content-between">
               <a href="?page=show&id=<?php echo $row["categorie_id"];?>"> <i class="fa-solid fa-eye btn btn-primary"></i></a>
                <a href="#" onclick="showDeleteModal(<?php echo $id; ?>)"> <i class="fa-solid fa-trash-can btn btn-danger"></i></a>
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
        
        $delete = $connect->prepare("DELETE FROM categories WHERE categorie_id = :id");
        $delete->bindParam(':id', $id, PDO::PARAM_INT);
    
        $delete->execute();
    
        header("Location: categories.php");
        exit;
    }
}else if($page==="show"){

    if(isset($_GET['id'])){

        $id = $_GET['id']; 
        $data="";

        foreach ($results as $row) {
            if ($row['categorie_id'] == $id) {
                $data = $row;
                break; 
            }
        }
    };
?>    
    <div class="container mt-5">
    <form method="post" action="?page=update&key=<?php echo $data["categorie_id"];?>">
        <div class="mb-3">
            <label for="id" class="form-label">Categorie ID</label>
            <input type="text" class="form-control" id="id" name="id" value=<?php echo $data["categorie_id"];?> >
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Categorie Name</label>
            <input type="text" class="form-control" id="name" name="name" value=<?php echo $data["categorie_name"];?> >
        </div>
        <div class="mb-3">
            <label for="creation_date" class="form-label">Creation date</label>
            <input type="date" class="form-control" id="creation_date" name="creation_date" value=<?php echo $data["creation_date"];?> >
        </div>
        <button name="save_data" type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
<?php
}else if($page==="update"){

    if(isset($_GET["key"])){
     if($_SERVER["REQUEST_METHOD"] === "POST"){
       if(isset($_POST["save_data"])){
           $key=$_GET["key"];
           $cat_id=$_POST["id"];
           $cat_name=$_POST["name"];
        
   
           $statement=$connect->prepare("UPDATE categories SET `categorie_id`=? ,`categorie_name`=?, `creation_date`= NOW()   WHERE categorie_id=? ");
           $statement->execute(array($cat_id,$cat_name,$key));
           
           echo "<h2 class='update alert-success'>Your data Got updated succefully</h2>";
           header("refresh:3;url=categories.php");
           
   
       }
     } 
      }
   }else if($page==="create"){
   ?>    
       <div class="container mt-5">
       <h2 class="mb-3 text-center"> CREATE Categorie</h2>
       <form method="post" action="?page=created">
           <div class="mb-3">
               <label for="id" class="form-label">Categorie ID</label>
               <input type="text" class="form-control" id="id" name="id" >
           </div>
           <div class="mb-3">
               <label for="name" class="form-label">Categorie Name</label>
               <input type="text" class="form-control" id="name" name="name" >
           </div>
           <div class="mb-3">
               <label for="date" class="form-label">Creation date</label>
               <input type="date" class="form-control" id="date" name="date" >
           </div>
           <button name="save_data" type="submit" class="btn btn-primary btn-block">Save</button>
       </form>
   </div>
   <?php
   }else if($page=="created"){
       if($_SERVER["REQUEST_METHOD"] === "POST"){
           if(isset($_POST["save_data"])){
               $cat_id=$_POST["id"];
               $cat_name=$_POST["name"];
               
               $statement=$connect->prepare("INSERT INTO categories (`categorie_id`, `categorie_name`,`creation_date`) VALUES (?, ?, Now())" );

               $statement->execute(array($cat_id,$cat_name));
               
               echo "<h2 class='update alert-success'>You have created Category succefully</h2>";
               header("refresh:3;url=categories.php");
               
       
           }
         } 
   }
include '../includes/templates/footer.php'; 
?>