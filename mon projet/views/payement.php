<?php
include '../config/config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit;
}

$payment_method = isset($_GET['method']) ? $_GET['method'] : '';

// Récupérer le montant total de la commande
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$total_amount = 0.00;

if ($order_id > 0) {
    $query = "SELECT SUM(price * quantity) AS total_amount FROM order_items WHERE order_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $total_amount = $row['total_amount'];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
    <!-- PayPal Checkout Script -->
    <script src="https://www.paypal.com/sdk/js?client-id=AURoClKTofgpvyisGertGKUDBCL8EeB_3z1ytZ3X6A9pndUHk9dKjIncdTTmL8-wn4XxW6hGxH3FRakG&currency=CAD"></script>
    <style>
        .heading {
            text-align: center;
            color: #ff69b4; /* Couleur rose pour le header */
            margin-bottom: 30px; /* Ajouter une marge en bas pour espacer le titre du reste du contenu */
        }
        .btn-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .text-center {
            text-align: center;
        }
        body {
            background-color: #ffe4e1; /* Couleur de fond rose clair */
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .btn-secondary {
            background-color: #ff69b4; /* Couleur de bouton rose */
            border-color: #ff69b4; /* Couleur de bordure rose */
        }
        .btn-secondary:hover {
            background-color: #ff1493; /* Couleur de bouton rose foncé au survol */
            border-color: #ff1493; /* Couleur de bordure rose foncé au survol */
        }
        .container {
            border: 2px solid #ff69b4; /* Bordure rose autour du conteneur */
            padding: 20px;
            border-radius: 10px;
            background-color: #ffffff; /* Couleur de fond blanche pour le conteneur */
            max-width: 600px;
            width: 100%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        #paypal-button-container {
            margin: 20px auto; /* Centrer le bouton PayPal */
        }
        .card-payment {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .design-element {
            width: 50px;
            height: 50px;
            background-color: #ff69b4;
            border-radius: 50%;
            position: absolute;
            top: -25px;
            left: -25px;
        }
        .design-element-right {
            width: 50px;
            height: 50px;
            background-color: #ff69b4;
            border-radius: 50%;
            position: absolute;
            bottom: -25px;
            right: -25px;
        }
    </style>
</head>

<body>

<div class="container mt-5 position-relative">
    <div class="design-element"></div>
    <div class="design-element-right"></div>
    <h1 class="heading">Paiement</h1>

    <?php if ($payment_method == 'paypal') { ?>
        <h3 class="text-center">Vous avez sélectionné PayPal</h3>
        <div id="paypal-button-container" class="text-center"></div>
        <script>
            paypal.Buttons({
                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: '<?php echo number_format($total_amount, 2); ?>' // Le montant total de la transaction
                            }
                        }]
                    });
                },
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(details) {
                        alert('Transaction completed by ' + details.payer.name.given_name);
                        // Mettre à jour l'état de la commande après paiement
                        var xhr = new XMLHttpRequest();
                        xhr.open("POST", "update_order_status.php", true);
                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        xhr.send("order_id=<?php echo $order_id; ?>&status=completed");

                        // Redirection après paiement
                        window.location.href = "success.php?orderID=" + data.orderID;
                    });
                }
            }).render('#paypal-button-container'); // Render le bouton PayPal dans le div
        </script>
    <?php } else { ?>
        <p class="text-center">Méthode de paiement sélectionnée invalide.</p>
    <?php } ?>

    <div class="btn-container">
        <a href="index2.php" class="btn btn-secondary">Retour</a>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
