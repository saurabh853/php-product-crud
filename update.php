<?php

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=product', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$p_no = $_GET['p_no'] ?? null;

if (!$p_no) {
    header('Location:product.php');
    exit;
}

$statement = $pdo->prepare('SELECT *FROM product_details WHERE p_no=:p_no');
$statement->bindValue(':p_no', $p_no);
$statement->execute();
$product = $statement->fetch(PDO::FETCH_ASSOC);




$p_name = $product['p_name'];
$p_category = $product['p_category'];
$price = $product['price'];
$qty = $product['qty'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p_name = $_POST['p_name'];
    $p_category = $_POST['p_category'];
    // $p_image = $_POST['p_image'];
    $price = $_POST['price'];
    $qty = $_POST['qty'];

    if (!is_dir('images')) {
        mkdir('images');
    }

    if (empty($errors)) {
        $p_image = $_FILES['p_image'] ?? null;
        $imagePath = $product['p_image'];



        if ($p_image && $p_image['tmp_name']) {

            if ($p_image) {
                if ($product['p_image']) {
                    unlink($product['p_image']);
                }
            }

            $imagePath = 'images/' . randomString(8) . '/' . $p_image['name'];
            mkdir(dirname($imagePath));

            move_uploaded_file($p_image['tmp_name'], $imagePath);
        }

        if (!$p_name) {
            $errors[] = 'Product name is required';
        }
        if (!$p_category) {
            $errors[] = 'Product category is required';
        }
        if (!$price) {
            $errors[] = 'Product price is required';
        }
        if (!$qty) {
            $errors[] = 'Quantity is required';
        }




        $statement = $pdo->prepare("UPDATE product_details   
   SET p_name = :p_name,
       p_category = :p_category,
       p_image = :p_image,
       price = :price,
       qty = :qty
 WHERE p_no = :p_no
 ");
        $statement->bindValue(':p_name', $p_name);
        $statement->bindValue(':p_category', $p_category);
        $statement->bindValue(':p_image', $imagePath);
        $statement->bindValue(':price', $price);
        $statement->bindValue(':qty', $qty);
        $statement->bindValue(':p_no', $p_no);
        $statement->execute();

        header('Location: product.php');
    }
}

function randomString($n)
{
    $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $str = "";
    for ($i = 0; $i <= $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $str .= $characters[$index];
    }
    return $str;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        h1 {
            text-align: center;
            margin-bottom: 50px;
        }

        body {
            margin: 20px;
        }

        .img {
            width: 120px;
        }
    </style>
</head>

<body>
    <p>
        <a href="product.php" class="btn btn-secondary">Go back to product</a>
    </p>

    <h1>Update Product <b><?php echo $product['p_name']; ?></b></h1>
    <?php if (!empty($errors)) : ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error) : ?>
                <div><?php echo $error  ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form action="" method="POST" enctype="multipart/form-data">
        <?php if ($product['p_image']) : ?>
            <img class="img" src="<?php echo $product['p_image']; ?>" alt="">
        <?php endif; ?>
        <div class="mb-3">
            <label for="p_name" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="p_name" name="p_name" value="<?php echo $p_name; ?>">
        </div>
        <div class="mb-3">
            <label for="p_category" class="form-label">Product Category</label>
            <input type="text" class="form-control" id="p_category" name="p_category" value="<?php echo $p_category; ?>">
        </div>
        <div class="mb-3">
            <label for="p_image" class="form-label">Product Image</label><br>
            <input type="file" class="" id="p_image" name="p_image">
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Product Price</label>
            <input type="number" step=".01" class="form-control" id="price" name="price" value="<?php echo $price; ?>">
        </div>
        <div class="mb-3">
            <label for="qty" class="form-label">Product Quantity</label>
            <input type="number" class="form-control" id="qty" name="qty" value="<?php echo $qty; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</body>

</html>