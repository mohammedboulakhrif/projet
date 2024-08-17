<?php
session_start();
include '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    $action = $_POST['action'];

    if ($action == 'increase') {
        $query = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?";
    } elseif ($action == 'decrease') {
        $query = "UPDATE cart SET quantity = quantity - 1 WHERE user_id = ? AND product_id = ? AND quantity > 1";
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $user_id, $product_id);

    if ($stmt->execute()) {
        $query = "SELECT * FROM cart WHERE user_id = '$user_id'";
        $result = $conn->query($query);
        $cart_items = $result->fetch_all(MYSQLI_ASSOC);

        $cart_html = '';
        if (empty($cart_items)) {
            $cart_html = "<p>Votre panier est vide.</p>";
        } else {
            $cart_html .= '<table class="table table-striped">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Prix</th>
                        <th>Quantité</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>';
            foreach ($cart_items as $item) {
                $cart_html .= '<tr class="cart-item" data-product-id="' . $item['product_id'] . '">
                    <td>
                        <img src="' . htmlspecialchars($item['product_image']) . '" alt="' . htmlspecialchars($item['product_name']) . '" class="img-fluid" style="max-width: 50px; max-height: 50px;">
                        ' . htmlspecialchars($item['product_name']) . '
                    </td>
                    <td>' . htmlspecialchars($item['product_price']) . ' €</td>
                    <td>
                        <button class="btn btn-sm btn-outline-danger" onclick="updateQuantity(' . $item['product_id'] . ', \'decrease\')">-</button>
                        ' . htmlspecialchars($item['quantity']) . '
                        <button class="btn btn-sm btn-outline-danger" onclick="updateQuantity(' . $item['product_id'] . ', \'increase\')">+</button>
                    </td>
                    <td>' . htmlspecialchars($item['product_price'] * $item['quantity']) . ' €</td>
                    <td><button class="btn btn-danger btn-sm" onclick="removeFromCart(' . $item['product_id'] . ')">X</button></td>
                </tr>';
            }
            $cart_html .= '</tbody></table>';
        }
        echo $cart_html;
    } else {
        echo "Erreur lors de la mise à jour de la quantité du produit dans le panier.";
    }
}
?>
