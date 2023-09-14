
<?php 
session_start();
if(isset($_SESSION["User"])){

include '../includes/templates/header.php'; 
include '../includes/templates/navbar.php'; 
include '../includes/db/db.php'; 

$statement=$connect->prepare("SELECT * FROM users");
$statement->execute();
$results = $statement->fetchAll();
$userCount = $statement->rowCount();


$statement2=$connect->prepare("SELECT * FROM categories");
$statement2->execute();
$results2 = $statement2->fetchAll();
$catCount = $statement2->rowCount();

$statement3=$connect->prepare("SELECT * FROM products");
$statement3->execute();
$results3 = $statement3->fetchAll();
$productCount = $statement3->rowCount();



?>
<div class="container mt-5">
    <div class="row">
        <div class="col">
            <a href="users.php" class="text-decoration-none">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Users <span class="badge badge-secondary"><?php echo $userCount ?></span></h5>
                        <p class="card-text">View and manage users.</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="categories.php" class="text-decoration-none">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Categories <span class="badge badge-secondary"><?php echo $catCount ?></span></h5>
                        <p class="card-text">Browse and edit categories.</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="products.php" class="text-decoration-none">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">products <span class="badge badge-secondary"><?php echo $productCount ?></span></h5>
                        <p class="card-text">Browse and edit products.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<?php
include '../includes/templates/footer.php'; 
}else{
    echo "please login first";
    header("refresh:3;url=login.php");
    exit();
}
?>


