<?php


function registerUser($email, $password) {
    global $conn;

    // Vérifier si l'utilisateur existe déjà
    $query = "SELECT * FROM users WHERE email = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return "Un utilisateur avec cet e-mail existe déjà.";
        }
    }

    // Hacher le mot de passe
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insérer le nouvel utilisateur dans la base de données
    $query = "INSERT INTO users (email, password) VALUES (?, ?)";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('ss', $email, $hashed_password);
        if ($stmt->execute()) {
            return "Inscription réussie.";
        } else {
            return "Erreur lors de l'inscription : " . $stmt->error;
        }
    } else {
        return "Erreur lors de la préparation de la requête : " . $conn->error;
    }
}




function loginAdmin($email, $password) {
    global $conn;

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        return "Erreur lors de la préparation de la requête : " . $conn->error;
    }

    $stmt->bind_param("s", $email);
    if (!$stmt->execute()) {
        return "Erreur lors de l'exécution de la requête : " . $stmt->error;
    }

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            if ($user['role'] == 'admin') {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                return "Connexion réussie.";
            } else {
                return "Accès réservé aux administrateurs.";
            }
        } else {
            return "Mot de passe incorrect.";
        }
    } else {
        return "Aucun utilisateur trouvé avec cet e-mail.";
    }
}


function loginUser($email, $password) {
    global $conn;

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        return "Erreur lors de la préparation de la requête : " . $conn->error;
    }

    $stmt->bind_param("s", $email);
    if (!$stmt->execute()) {
        return "Erreur lors de l'exécution de la requête : " . $stmt->error;
    }

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            return "Connexion réussie.";
        } else {
            return "Mot de passe incorrect.";
        }
    } else {
        return "Aucun utilisateur trouvé avec cet e-mail.";
    }
}

function addProduct($name, $description, $image, $prix, $category) {
    global $conn;
    $query = "INSERT INTO products (name, description, image, prix, category) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        return "Erreur lors de la préparation de la requête : " . $conn->error;
    }

    $stmt->bind_param("sssds", $name, $description, $image, $prix, $category);
    if ($stmt->execute()) {
        return "Produit ajouté avec succès.";
    } else {
        return "Erreur lors de l'ajout du produit : " . $stmt->error;
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

// Supprimer un produit
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
