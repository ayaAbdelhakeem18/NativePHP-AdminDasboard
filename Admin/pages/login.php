<?php
session_start();

if(isset($_SESSION["User"])){
    header("Location:dashboard.php");
}
include '../includes/templates/header.php'; 
include '../includes/db/db.php'; 

?>

<div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h4 class="text-center">Admin Login</h4>
          </div>
          <div class="card-body">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
              <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" required>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
              </div>
              <button type="submit" name="login_submit" class="btn btn-primary btn-block">Login</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if(isset($_POST["login_submit"])){
    $mail = $_POST["email"];
    $password = $_POST["password"];

    $statement = $connect->prepare("SELECT * FROM users Where `mail`=? and `password`=? limit 1");
    $statement->execute(array($mail,sha1( $password)));
    $count=$statement->rowCount();

    if($count>0){
        $results = $statement->fetch();
        if($results["Role"]==="admin"){   
         $_SESSION["User"]=$results["Name"];
         header("Location:dashboard.php");
         exit();
        }else{
        echo "<div class='alert_style alert alert-danger'>You Are user not admin</div>";
        }
    }else{
        echo "<div class='alert_style alert alert-danger'>Please create account, you are not registered</div>";
    }

}

}

?>
<?php    
include '../includes/templates/footer.php'; 
?>