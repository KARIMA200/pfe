<?php
// Placez ce code au début du fichier PHP pour initialiser la connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer l'identifiant du produit à modifier
    $product_id = $_POST['product_id'];

    // Récupérer les détails du produit depuis la base de données
    $sql = "SELECT * FROM produits WHERE id = $product_id";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Afficher un formulaire pré-rempli avec les détails du produit
        ?>
        <!DOCTYPE html>

        <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Produit</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            background-color: #ff7e5f;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #ff934d;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Modifier Produit</h2>
    <form action="traiter_modification.php" method="post">
        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
        <div>
            <label for="nom">Nom:</label>
            <input type="text" id="nom" name="nom" value="<?php echo $row['nom']; ?>">
        </div>
        <div>
            <label for="description">Description:</label>
            <input type="text" id="description" name="description" value="<?php echo $row['description']; ?>">
        </div>
        <div>
            <label for="prix">Prix:</label>
            <input type="text" id="prix" name="prix" value="<?php echo $row['prix']; ?>">
        </div>
        <div>
            <button type="submit">Valider</button>
        </div>
    </form>
</div>
</body>
</html>


        <?php
    } else {
        echo "Produit non trouvé.";
    }
} else {
    echo "Méthode non autorisée.";
}
$conn->close();
?>
