<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Démarrer la session pour récupérer l'email de l'utilisateur
session_start();

// Vérifier si l'email de l'utilisateur est stocké en session
if(isset($_SESSION['email'])) {
    // Récupérer l'email de l'utilisateur
    $email = $_SESSION['email'];

    // Requête pour obtenir l'ID du client à partir de son email
    $sql_client_id = "SELECT id FROM clients WHERE email = ?";
    $stmt_client_id = $conn->prepare($sql_client_id);
    $stmt_client_id->bind_param("s", $email);
    $stmt_client_id->execute();
    $result_client_id = $stmt_client_id->get_result();

    if ($result_client_id->num_rows > 0) {
        // Récupération de l'ID du client
        $row = $result_client_id->fetch_assoc();
        $user_id = $row["id"];

        // Requête pour obtenir les produits du panier pour ce client
        $sql_panier_produits = "SELECT pa.product_id as product_id, pa.id as panier_id, p.nom FROM panier pa JOIN produits p ON pa.product_id = p.id WHERE pa.user_id = ?";
        $stmt_panier_produits = $conn->prepare($sql_panier_produits);
        $stmt_panier_produits->bind_param("i", $user_id);
        $stmt_panier_produits->execute();
        $result_panier_produits = $stmt_panier_produits->get_result();

        if ($result_panier_produits->num_rows > 0) {
            // Afficher les produits du panier pour ce client avec les inputs pour la quantité et bouton pour supprimer
            ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
    <!-- Inclure la bibliothèque Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .produit {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .produit h3 {
            margin: 0;
            flex-grow: 1;
        }
        .produit h3.valide {
            text-decoration: line-through;
        }
        .icon {
            font-size: 20px;
            color: #999;
            cursor: pointer;
        }
        .icon:hover {
            color: #555;
        }
        .icon.delete:hover {
            color: #f44336;
        }
        .icon.check:hover {
            color: #4caf50;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Votre panier</h2>
        <?php
        while($row_produit = $result_panier_produits->fetch_assoc()) {
            $panier_id = $row_produit['panier_id'];
            $product_id = $row_produit["product_id"];
        ?> <form action="supprimer_produit_panier.php" method="post" >
            <input type="hidden" name="panier_id" value="<?php echo $panier_id; ?>">
                <h3><?php echo $row_produit["nom"]; ?></h3>
                <button type="submit" id="deleteButton">
        <i class="fas fa-trash icon"></i>
    </button>

        </form>
        <?php
        }
        ?>
    </div>
  
</body>
</html>
            <?php
        } else {
            echo "Panier vide.";
        }
    } else {
        echo "Aucun client trouvé avec cet email : $email";
    }
} else {
    echo "Aucun email d'utilisateur trouvé en session.";
}

// Fermer la connexion à la base de données
$conn->close();
?>
