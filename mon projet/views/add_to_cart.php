<?php
session_start();
include '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $product_id = $_POST['product_id'];
        $product_name = $conn->real_escape_string($_POST['product_name']);
        $product_description = $conn->real_escape_string($_POST['product_description']);
        $product_image = $conn->real_escape_string($_POST['product_image']);
        $product_price = $conn->real_escape_string($_POST['product_price']);
        $product_category = $conn->real_escape_string($_POST['product_category']);
        $quantity = 1; // Par défaut, ajouter 1 produit

        // Vérifiez si le produit est déjà dans le panier
        $query = "SELECT * FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            // Si le produit est déjà dans le panier, mettez à jour la quantité
            $query = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = '$user_id' AND product_id = '$product_id'";
        } else {
            // Sinon, ajoutez un nouveau produit au panier
            $query = "INSERT INTO cart (user_id, product_id, product_name, product_description, product_image, product_price, product_category, quantity) VALUES ('$user_id', '$product_id', '$product_name', '$product_description', '$product_image', '$product_price', '$product_category', '$quantity')";
        }

        if ($conn->query($query) === TRUE) {
            // Récupérer les éléments mis à jour du panier
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
            echo json_encode(["status" => "success", "cart_html" => $cart_html]);
        } else {
            echo json_encode(["status" => "error", "message" => "Erreur : " . $conn->error]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Veuillez vous connecter pour ajouter des produits au panier."]);
    }

    $conn->close();
}
?>
