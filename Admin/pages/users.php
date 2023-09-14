<?php 
include '../includes/templates/header.php'; 
include '../includes/templates/navbar.php'; 
include '../includes/db/db.php'; 

$statement=$connect->prepare("SELECT * FROM users");
$statement->execute();
$results = $statement->fetchAll();
$userCount = $statement->rowCount();


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
            <h5 class="card-title">User <?php echo $userCount?></h5>
            <a href="?page=create" class="btn btn-success " >Create User</a>
        </div>

        <div class="card-body">
            <table class="table table-dark table-hover table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Role</th>
                        <th scope="col">Operation</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
              $tb="users";
              if($userCount>0){
                foreach($results as $row){
                 $id=$row["id"];
            ?>

                    <tr>
                        <td><?php echo $row["id"]?></td>
                        <td><?php echo $row["Name"]?></td>
                        <td><?php echo $row["mail"]?></td>
                        <td><?php echo $row["Role"]?></td>

                        <td class="d-flex justify-content-between">
                            <a href="?page=show&id=<?php echo $row["id"];?>"> <i
                                    class="fa-solid fa-eye btn btn-primary"></i></a>
                            <a href="#" onclick="showDeleteModal(<?php echo $id;?>)"> <i
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
        
        $delete = $connect->prepare("DELETE FROM users WHERE id = :id");
        $delete->bindParam(':id', $id, PDO::PARAM_INT);
    
        $delete->execute();
    
        header("Location: users.php");
        exit;
    }
}else if($page==="show"){

    if(isset($_GET['id'])){

        $id = $_GET['id']; 
        $data="";

        foreach ($results as $row) {
            if ($row['id'] == $id) {
                $data = $row;
                break; 
            }
        }
    };
?>
<div class="container mt-5">
    <h2 class="mb-3 text-center"> Show & Edit Data</h2>
    <form method="post" action="?page=update&key=<?php echo $data["id"];?>">
        <div class="mb-3">
            <label for="id" class="form-label">ID</label>
            <input type="text" class="form-control" id="id" name="id" value=<?php echo $data["id"];?>>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value=<?php echo $data["Name"];?>>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value=<?php echo $data["mail"];?>>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password"
                value=<?php echo $data["password"];?>>
        </div>
        <div class="mb-3">

            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" name="role">
                <option value="admin" <?php if ($data["Role"] === "admin") echo "selected"; ?>>Admin</option>
                <option value="user" <?php if ($data["Role"] === "user") echo "selected"; ?>>User</option>
            </select>

        </div>
        <button name="save_data" type="submit" class="btn btn-primary btn-block">Save</button>
    </form>
</div>
<?php
}else if($page==="update"){

 if(isset($_GET["key"])){
  if($_SERVER["REQUEST_METHOD"] === "POST"){
    if(isset($_POST["save_data"])){
        $key=$_GET["key"];
        $user_id=$_POST["id"];
        $user_name=$_POST["name"];
        $user_mail=$_POST["email"];
        $user_password=$_POST["password"];
        $role=$_POST["role"];

        $statement=$connect->prepare("UPDATE users SET `id`=? ,`Name`=?, `mail`=?, `password`=? ,`Role`=?   WHERE id=? ");
        $statement->execute(array($user_id,$user_name,$user_mail,$user_password,$role,$key));
        
        echo "<h2 class='update alert-success'>Your data Got updated succefully</h2>";
        header("refresh:3;url=users.php");
        

    }
  } 
   }
}else if($page==="create"){
?>    
    <div class="container mt-5">
    <h2 class="mb-3 text-center"> CREATE USER</h2>
    <form method="post" action="?page=created">
        <div class="mb-3">
            <label for="id" class="form-label">ID</label>
            <input type="text" class="form-control" id="id" name="id" >
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" >
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" >
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <div class="mb-3">

            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" name="role">
                <option value="admin">Admin</option>
                <option value="user" >User</option>
            </select>

        </div>
        <button name="save_data" type="submit" class="btn btn-primary btn-block">Save</button>
    </form>
</div>
<?php
}else if($page=="created"){
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        if(isset($_POST["save_data"])){
            $user_id=$_POST["id"];
            $user_name=$_POST["name"];
            $user_mail=$_POST["email"];
            $user_password=$_POST["password"];
            $role=$_POST["role"];
    
            $statement=$connect->prepare("INSERT INTO users (`id`, `Name`,`mail`,`password`,`Role`) VALUES (?, ?, ?, ?, ?)");
            $statement->execute(array($user_id,$user_name,$user_mail,$user_password,$role));
            
            echo "<h2 class='update alert-success'>You have created user succefully</h2>";
            header("refresh:3;url=users.php");
            
    
        }
      } 
}

include '../includes/templates/footer.php'; 
?>