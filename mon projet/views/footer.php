<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FOOTER</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        /* Style pour la section de pied de page */
        .footer {
            background-color: #f8ebe6; /* Beige clair */
            padding: 20px;
            text-align: center;
            font-family: Arial, sans-serif;
        }
        

        /* Style pour les sous-titres */
        .sub-heading {
            text-transform: uppercase;
            font-size: 1.2em;
            margin-bottom: 15px;
            color: #e91e63; /* Rose vif */
        }

        /* Style pour les boîtes contenant les informations */
        .box-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        .box {
            flex: 1;
            max-width: 300px;
            margin: 10px;
            padding: 10px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: left;
        }

        .box h3 {
            font-size: 1.1em;
            color: #e91e63; /* Rose vif */
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }

        .box a {
            display: block;
            color: #777;
            text-decoration: none;
            margin-bottom: 5px;
        }

        .box a:hover {
            color: #e91e63; /* Rose vif */
        }

        /* Style pour le texte de crédit */
        .credit {
            color: #e91e63; /* Rose vif */
            font-size: 0.8em;
            margin-top: 20px;
        }

        .credit span {
            font-style: italic;
        }
    </style>
</head>
<body>
    <section class="footer">
        <h3 class="sub-heading">ABOUT-US</h3>
        <div class="box-container">
            <div class="box">
                <h3>locations</h3>
                <a href="#">Canada</a>
                <a href="#">USA</a>
                <a href="#">Morocco</a>
            </div>
            <div class="box">
                <h3>contact info</h3>
                <a href="#">+514-576-8756</a>
                <a href="#">styleHOMME@gmail.com</a>
                <a href="#">Canada, Montreal - J5L 0G5</a>
            </div>
            <div class="box">
                <h3>follow us</h3>
                <a href="#">Facebook</a>
                <a href="#">YouTube</a>
                <a href="#">Instagram</a>
            </div>
        </div>
        <div class="credit">copyright @2024 <span></span></div>
        <br>
    </section>
</body>
</html>
