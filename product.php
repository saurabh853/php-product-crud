<?php

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=product', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$search = $_GET['search'] ?? '';
if ($search) {
    $statement = $pdo->prepare('SELECT *FROM product_details WHERE p_name LIKE :p_name ORDER BY p_no ASC');
    $statement->bindValue(':p_name', "%$search%");
} else {
    $statement = $pdo->prepare('SELECT *FROM product_details ORDER BY p_no ASC');
}

$statement->execute();
$products = $statement->fetchAll(PDO::FETCH_ASSOC);

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

        .thumb-image {
            width: 50px;
        }
    </style>
</head>

<body>
    <h1>Product Management</h1>
    <p>
        <a href="insert.php" class="btn btn-success">Insert Product</a>
    </p>
    <form action="">
        <div class="input-group mb-3">
            <input type="text" class="form-control" value="<?php echo $search; ?>" placeholder="Search for products" name="search">
            <button class="btn btn-outline-secondary" type="submit">Search</button>
        </div>
    </form>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">P_No</th>
                <th scope="col">P_Name</th>
                <th scope="col">P_Category</th>
                <th scope="col">P_Image</th>
                <th scope="col">Price</th>
                <th scope="col">Qty</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product) : ?>
                <tr>
                    <th scope="row"><?php echo $product['p_no']; ?></th>
                    <td><?php echo $product['p_name']; ?></td>
                    <td><?php echo $product['p_category']; ?></td>
                    <td><img src="<?php echo $product['p_image']; ?>" class="thumb-image" alt=""></td>
                    <td><?php echo $product['price']; ?></td>
                    <td><?php echo $product['qty']; ?></td>
                    <td>
                        <a href="update.php?p_no=<?php echo $product['p_no']; ?>" type="button" class="btn btn-sm btn-outline-primary">Update</a>
                        <form style="display:inline-block" action="delete.php" method="POST">
                            <input type="hidden" name="p_no" value="<?php echo $product['p_no']; ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>