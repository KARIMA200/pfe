<?php
session_start();

// Vérification de la session et récupération de l'email de l'utilisateur connecté
if(isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

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

    // Requête pour récupérer l'ID du vendeur en fonction de l'email
    $sql_vendeur = "SELECT id FROM vendeurs WHERE email = '$email'";
    $result_vendeur = $conn->query($sql_vendeur);

    if ($result_vendeur->num_rows > 0) {
        $row_vendeur = $result_vendeur->fetch_assoc();
        $id_vendeur = $row_vendeur['id'];

        // Requête pour récupérer les produits vendus par ce vendeur
        $sql_produits_vendeur = "SELECT produit_id FROM produits_vendeurs WHERE vendeur_id = '$id_vendeur'";
        $result_produits_vendeur = $conn->query($sql_produits_vendeur);

        if ($result_produits_vendeur->num_rows > 0) {
            while ($row_produit = $result_produits_vendeur->fetch_assoc()) {
                $produit_id = $row_produit['produit_id'];

                // Requête pour récupérer les détails de commande pour ce produit
                $sql_details_commande = "SELECT commande_details.id_commande, commande_details.quantite, commande_details.description, commande.date_commande
                                        FROM commande_details
                                        INNER JOIN commande ON commande_details.id_commande = commande.id
                                        WHERE commande_details.produit_id = '$produit_id'";
                $result_details_commande = $conn->query($sql_details_commande);

                if ($result_details_commande->num_rows > 0) {
                    while ($row_detail = $result_details_commande->fetch_assoc()) {
                        $id_commande = $row_detail['id_commande'];
                        $quantite = $row_detail['quantite'];
                        $description = $row_detail['description'];
                        $date_commande = $row_detail['date_commande'];

                        // Requête pour récupérer les informations du client pour cette commande
                        $sql_client = "SELECT clients.nom, clients.prenom, clients.user_image 
                                       FROM clients 
                                       INNER JOIN commande ON clients.email = commande.email 
                                       WHERE commande.id = '$id_commande'";
                        $result_client = $conn->query($sql_client);

                        if ($result_client->num_rows > 0) {
                            $row_client = $result_client->fetch_assoc();
                            $nom_client = $row_client['nom'];
                            $prenom_client = $row_client['prenom'];
                            $image_client = $row_client['user_image'];

                            // Requête pour récupérer le nom du produit
                            $sql_produit_nom = "SELECT nom FROM produits WHERE id = '$produit_id'";
                            $result_produit_nom = $conn->query($sql_produit_nom);

                            if ($result_produit_nom->num_rows > 0) {
                                $row_produit_nom = $result_produit_nom->fetch_assoc();
                                $nom_produit = $row_produit_nom['nom'];

                                // Affichage des informations
                                echo "<div>";
                                echo "<img src='$image_client' alt='Image Client'>";
                                echo "<p>Nom du client: $nom_client $prenom_client</p>";
                                echo "<p>Nom du produit: $nom_produit</p>";
                                echo "<p>Date de commande: $date_commande</p>";
                                echo "<p>Quantité: $quantite</p>";
                                echo "<p>Description: $description</p>";
                                echo "</div>";
                            }
                        }
                    }
                }
            }
        } else {
            echo "Aucun produit trouvé pour ce vendeur.";
        }
    } else {
        echo "Vendeur non trouvé pour cet email.";
    }

    $conn->close();
} else {
    echo "Veuillez vous connecter pour accéder à cette page.";
}
?>
