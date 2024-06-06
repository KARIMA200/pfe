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

// Récupérer l'ID du produit via GET
$produit_id = isset($_GET['produit_id']) ? intval($_GET['produit_id']) : 0;

if ($produit_id > 0) {
    // Rechercher le vendeur_id dans la table produits_vendeurs
    $sql_produits_vendeurs = "SELECT vendeur_id FROM produits_vendeurs WHERE produit_id = ?";
    $stmt_produits_vendeurs = $conn->prepare($sql_produits_vendeurs);
    $stmt_produits_vendeurs->bind_param("i", $produit_id);
    $stmt_produits_vendeurs->execute();
    $result_produits_vendeurs = $stmt_produits_vendeurs->get_result();

    if ($result_produits_vendeurs->num_rows > 0) {
        $row = $result_produits_vendeurs->fetch_assoc();
        $vendeur_id = $row['vendeur_id'];

        // Rechercher l'email du vendeur dans la table vendeurs
        $sql_vendeur = "SELECT email FROM vendeurs WHERE id = ?";
        $stmt_vendeur = $conn->prepare($sql_vendeur);
        $stmt_vendeur->bind_param("i", $vendeur_id);
        $stmt_vendeur->execute();
        $result_vendeur = $stmt_vendeur->get_result();

        if ($result_vendeur->num_rows > 0) {
            $row_vendeur = $result_vendeur->fetch_assoc();
            $email_vendeur = $row_vendeur['email'];

            // Redirection vers pro.php avec l'email du vendeur en paramètre GET
            header("Location: pro.php?email=" . urlencode($email_vendeur));
            exit();
        } else {
            echo "Vendeur non trouvé.";
        }
    } else {
        echo "Produit non trouvé.";
    }
} else {
    echo "ID de produit invalide.";
}

$conn->close();
?>
