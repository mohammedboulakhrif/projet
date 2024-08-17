<?php
session_start();
include '../includes/db_connect.php';
include 'header.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        // Ajout d'un produit
        $name = $_POST['name'];
        $description = $_POST['description'];
        $image = $_FILES['image']['name'];
        $prix = $_POST['prix'];
        $category = $_POST['category'];
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($image);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $message = "File is not an image.";
            $message_type = 'danger';
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["image"]["size"] > 500000) { // 500KB
            $message = "Sorry, your file is too large.";
            $message_type = 'danger';
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $message_type = 'danger';
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $message = "Sorry, your file was not uploaded.";
            $message_type = 'danger';
        } else {
            // Check if the uploads directory exists and create it if it doesn't
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Insert product data into the database
                $query = "INSERT INTO products (name, description, image, prix, category) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("sssds", $name, $description, $target_file, $prix, $category);

                if ($stmt->execute()) {
                    $message = "The product has been added successfully.";
                    $message_type = 'success';
                } else {
                    $message = "Error: " . $stmt->error;
                    $message_type = 'danger';
                }
            } else {
                $message = "Sorry, there was an error uploading your file.";
                $message_type = 'danger';
            }
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'update') {
        // Modification d'un produit
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $prix = $_POST['prix'];
        $category = $_POST['category'];

        if (isset($_FILES['image']) && $_FILES['image']['name'] != '') {
            $image = $_FILES['image']['name'];
            $target_dir = "../uploads/";
            $target_file = $target_dir . basename($image);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $message = "File is not an image.";
                $message_type = 'danger';
                $uploadOk = 0;
            }

            // Check file size
            if ($_FILES["image"]["size"] > 500000) { // 500KB
                $message = "Sorry, your file is too large.";
                $message_type = 'danger';
                $uploadOk = 0;
            }

            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $message_type = 'danger';
                $uploadOk = 0;
            }

            if ($uploadOk == 0) {
                $message = "Sorry, your file was not uploaded.";
                $message_type = 'danger';
            } else {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $update_image = true;
                } else {
                    $message = "Sorry, there was an error uploading your file.";
                    $message_type = 'danger';
                    $update_image = false;
                }
            }
        } else {
            $update_image = false;
        }

        if ($update_image) {
            $update_result = updateProduct($id, $name, $description, $target_file, $prix, $category);
        } else {
            $update_result = updateProduct($id, $name, $description, $_POST['current_image'], $prix, $category);
        }

        if ($update_result === "Produit modifié avec succès.") {
            $message = $update_result;
            $message_type = 'success';
        } else {
            $message = $update_result;
            $message_type = 'danger';
        }
    }
}

// Suppression d'un produit
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $delete_result = deleteProduct($id);
    if ($delete_result === "Produit supprimé avec succès.") {
        $message = $delete_result;
        $message_type = 'success';
    } else {
        $message = $delete_result;
        $message_type = 'danger';
    }
}

function updateProduct($id, $name, $description, $image, $prix, $category) {
    global $conn;
    $query = "UPDATE products SET name = ?, description = ?, image = ?, prix = ?, category = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        return "Erreur lors de la préparation de la requête : " . $conn->error;
    }
    $stmt->bind_param("sssdsd", $name, $description, $image, $prix, $category, $id);
    if ($stmt->execute()) {
        return "Produit modifié avec succès.";
    } else {
        return "Erreur lors de la modification du produit : " . $stmt->error;
    }
}

function deleteProduct($id) {
    global $conn;
    $query = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        return "Erreur lors de la préparation de la requête : " . $conn->error;
    }
    $stmt->bind_param("d", $id);
    if ($stmt->execute()) {
        return "Produit supprimé avec succès.";
    } else {
        return "Erreur lors de la suppression du produit : " . $stmt->error;
    }
}
?>

<div class="custom-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center">Ajouter / Modifier un Produit</h2>
                <?php if ($message != ''): ?>
                    <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
                <?php endif; ?>
                <form action="add_product.php" method="POST" enctype="multipart/form-data">
                    <?php if (isset($_GET['edit_id'])): ?>
                        <?php
                        $id = $_GET['edit_id'];
                        $query = "SELECT * FROM products WHERE id = ?";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("d", $id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $product = $result->fetch_assoc();
                        ?>
                        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="current_image" value="<?php echo $product['image']; ?>">
                        <input type="hidden" name="action" value="update">
                    <?php else: ?>
                        <input type="hidden" name="action" value="add">
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="name">Nom du Produit:</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($product) ? $product['name'] : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea class="form-control" id="description" name="description" required><?php echo isset($product) ? $product['description'] : ''; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="prix">Prix:</label>
                        <input type="text" class="form-control" id="prix" name="prix" value="<?php echo isset($product) ? $product['prix'] : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="category">Catégorie:</label>
                        <input type="text" class="form-control" id="category" name="category" value="<?php echo isset($product) ? $product['category'] : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="image">Image:</label>
                        <input type="file" class="form-control" id="image" name="image">
                        <?php if (isset($product)): ?>
                            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" width="100">
                        <?php endif; ?>
                    </div>
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-danger"><?php echo isset($product) ? 'Modifier' : 'Ajouter'; ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="custom-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h2 class="text-center">Liste des Produits</h2>
                <?php
                $query = "SELECT * FROM products";
                $result = $conn->query($query);
                ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Prix</th>
                            <th>Catégorie</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                
                                <td><?php echo $row['name']; ?></td>
                                <td ><?php echo $row['description']; ?></td>
                                <td><?php echo $row['prix']; ?></td>
                                <td><?php echo $row['category']; ?></td>
                                <td><img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" width="100"></td>
                                <td>
                                     <div class="btn-group" role="group" aria-label="Basic example">
                                        <a href="add_product.php?edit_id=<?php echo $row['id']; ?>" class="btn btn-primary">Modifier</a>
                                        <a href="add_product.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">Supprimer</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Section des commandes -->
<div class="custom-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h2 class="text-center">Liste des Commandes</h2>
                <?php
                // Récupérer les commandes
                $query = "SELECT * FROM orders ORDER BY placed_on DESC";
                $result = $conn->query($query);
                ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date de Commande</th>
                            <th>Nom</th>
                            <th>Adresse</th>
                            <th>Ville</th>
                            <th>Pays</th>
                            <th>Code Postal</th>
                            <th>Status</th>
                            <th>Produit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($order = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $order['placed_on']; ?></td>
                                <td><?php echo $order['name']; ?></td>
                                <td><?php echo $order['address']; ?></td>
                                <td><?php echo $order['city']; ?></td>
                                <td><?php echo $order['country']; ?></td>
                                <td><?php echo $order['zip']; ?></td>
                                <td><?php echo $order['status']; ?></td>
                                <td>
                                    <a href="order_details.php?order_id=<?php echo $order['id']; ?>" class="btn btn-primary">Voir les détails</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<?php include 'footer.php'; ?>
</body>
</html>
