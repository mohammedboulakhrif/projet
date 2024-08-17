<?php
include '../config/config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit();
}

// Inclure le fichier headerConfirme.php
include 'headerConfirme.php';

// Récupérer les éléments du panier
$query = "SELECT * FROM cart WHERE user_id = '$user_id'";
$result = $conn->query($query);
$cart_items = $result->fetch_all(MYSQLI_ASSOC);

if (empty($cart_items)) {
    header("Location: index2.php");
    exit();
}

if (isset($_POST['place_order'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $zip = mysqli_real_escape_string($conn, $_POST['zip']);

    if (empty($name) || empty($phone) || empty($address) || empty($city) || empty($country) || empty($zip)) {
        $message[] = 'Veuillez remplir tous les champs';
    } else {
        // Insérer les détails de la commande dans la table orders avec l'état 'pending'
        $insert_order = "INSERT INTO orders (user_id, name, phone, address, city, country, zip, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')";
        $stmt = $conn->prepare($insert_order);
        $stmt->bind_param("issssss", $user_id, $name, $phone, $address, $city, $country, $zip);

        if ($stmt->execute()) {
            $order_id = $stmt->insert_id;

            // Préparer la requête pour récupérer les informations du produit
            $product_query = "SELECT name, image FROM products WHERE id = ?";
            $product_stmt = $conn->prepare($product_query);

            // Insérer les produits du panier dans la table order_items
            $select_cart = "SELECT * FROM cart WHERE user_id = '$user_id'";
            $cart_query = mysqli_query($conn, $select_cart);

            $insert_order_items = "INSERT INTO order_items (order_id, product_id, product_name, product_image, quantity, price) VALUES (?, ?, ?, ?, ?, ?)";
            $order_item_stmt = $conn->prepare($insert_order_items);

            while ($cart_item = mysqli_fetch_assoc($cart_query)) {
                $product_id = $cart_item['product_id'];
                $quantity = $cart_item['quantity'];
                $price = $cart_item['product_price'];

                // Récupérer les informations du produit
                $product_stmt->bind_param("i", $product_id);
                $product_stmt->execute();
                $product_result = $product_stmt->get_result();
                $product = $product_result->fetch_assoc();

                $product_name = $product['name'];
                $product_image = $product['image'];

                $order_item_stmt->bind_param("iissid", $order_id, $product_id, $product_name, $product_image, $quantity, $price);
                $order_item_stmt->execute();
            }

            // Vider le panier après avoir passé la commande
            $delete_cart = "DELETE FROM cart WHERE user_id = '$user_id'";
            mysqli_query($conn, $delete_cart);

            // Rediriger vers la page de paiement avec PayPal
            header('location:payement.php?method=paypal&order_id=' . $order_id);
            exit();
        } else {
            $message[] = 'Impossible de passer la commande. Veuillez réessayer.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        .heading {
            text-align: center;
            color: #ff0000; 
        }
        .form-group.text-center {
            margin-top: 20px;
        }
        .btn-container {
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1 class="heading">Commande</h1>
    <table class="table table-striped" style="margin-top: 40px;">
        <thead>
            <tr>
                <th>Produit</th>
                <th>Prix</th>
                <th>Quantité</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cart_items as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($item['product_price']); ?> €</td>
                    <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($item['product_price'] * $item['quantity']); ?> €</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <form action="" method="post">
    <h1 class="heading" style="margin-top: 40px;">Informations personnelles</h1>
        <div class="form-group">
            <label for="name">Nom Complet</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="phone">Téléphone</label>
            <input type="text" name="phone" id="phone" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="address">Adresse</label>
            <input type="text" name="address" id="address" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="city">Ville</label>
            <input type="text" name="city" id="city" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="country">Pays</label>
            <input type="text" name="country" id="country" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="zip">Code Postal</label>
            <input type="text" name="zip" id="zip" class="form-control" required>
        </div>
        <div class="btn-container">
            <button type="submit" name="place_order" class="btn btn-danger" style="margin-bottom: 40px;">Passer la commande</button>
        </div>
    </form>
    <?php
    if (isset($message)) {
        foreach ($message as $msg) {
            echo '<div class="alert alert-danger mt-3">' . $msg . '</div>';
        }
    }
    ?>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<?php include 'footer.php'; ?>
</body>
</html>
