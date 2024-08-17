<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon site de e-commerce</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0; /* Ajout pour réinitialiser la marge par défaut */
        }
        header {
            background-color: #f8ebe6; /* Beige clair */
            padding: 10px 0;
        }
        .logo {
            font-size: 1.5em;
            font-weight: bold;
            color: #e91e63; /* Rose vif */
            text-decoration: none;
            margin-right: auto; /* Déplace le logo à gauche */
        }
        .navbar {
            display: flex;
            gap: 15px;
            flex-grow: 1; /* Permet à la barre de navigation de s'étendre */
            justify-content: center; /* Centre la barre de navigation */
        }
        .icons {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .icons a {
            color: #e91e63; /* Rose vif */
            font-size: 1.2em;
            text-decoration: none; /* Ajout pour éviter le soulignement par défaut */
        }
        .icons a:hover {
            color: #ad1457; /* Rose plus foncé */
        }
        .flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
    </style>
</head>
<body>
<header>
    <section class="flex">
        <a href="index.php" class="logo">Parfum</a>
        <nav class="navbar">
            <form action="index2.php" method="POST">
                <button type="submit" class="btn btn-danger">Retour</button>
            </form>
        </nav>
    </section>
</header>
</body>
</html>
