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
    $sql_vendeur = "SELECT id, nom, prenom FROM vendeurs WHERE email = '$email'";
    $result_vendeur = $conn->query($sql_vendeur);

    if ($result_vendeur->num_rows > 0) {
        $row_vendeur = $result_vendeur->fetch_assoc();
        $id_vendeur = $row_vendeur['id'];
        $v_n = $row_vendeur['nom'];
        $v_p = $row_vendeur['prenom'];
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Commandes du Vendeur</title>
            <style>
                /* Votre CSS ici */
                /* Style pour afficher chaque commande dans une seule ligne */
                .commande {
                    display: flex;
                    border: 1px solid #ccc;
                    padding: 10px;
                    margin-bottom: 10px;
                }

                .commande img {
                    width: 100px;
                    height: auto;
                    margin-right: 10px;
                    border-radius: 50%;
                }

                .commande p {
                    margin: 0;
                }

                .commande p:nth-child(odd) {
                    font-weight: bold;
                    margin-right: 10px;
                }

                .greeting {
                    font-size: 24px;
                    color: #ff7e5f; /* Couleur orange */
                    font-family: Arial, sans-serif; /* Police de caractères */
                    text-align: center; /* Centrer le texte */
                    margin-top: 50px; /* Espacement vers le haut */
                }

                /* Style pour le message de bienvenue */
                .greeting {
                    font-size: 24px;
                    color: #fff; /* Couleur du texte en blanc */
                    font-family: 'Arial', sans-serif; /* Police de caractères */
                    text-align: center; /* Centrer le texte */
                    padding: 20px 0; /* Espacement interne en haut et en bas */
                    background-color: #ff7e5f; /* Couleur de fond orange */
                    border-bottom: 4px solid #ff6b3c; /* Bordure orange en bas */
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Ombre légère */
                    margin-bottom: 30px; /* Marge en bas */
                }

                /* Style pour le paragraphe à l'intérieur du message de bienvenue */
                .greeting p {
                    font-size: 18px; /* Taille de police */
                    margin-top: 10px; /* Espacement vers le haut */
                }

                /* Style pour le texte en gras */
                .greeting strong {
                    font-weight: bold; /* Texte en gras */
                }

                /* Style pour la transition du message de bienvenue */
                .greeting:hover {
                    background-color: #ff934d; /* Changement de couleur de fond au survol */
                    border-bottom-color: #ff8133; /* Changement de couleur de la bordure au survol */
                    transition: background-color 0.3s, border-bottom-color 0.3s; /* Transition fluide */
                }

                .button-container {
                    display: inline-block;
                    justify-content: center;
                    margin-top: 30px;
                }

                .button {
                    background-color: #ff7e5f; /* Couleur de fond orange */
                    color: #fff; /* Couleur du texte en blanc */
                    font-size: 16px;
                    padding: 10px 20px;
                    border: none;
                    border-radius: 5px;
                    margin: 0 10px;
                    cursor: pointer;
                    transition: background-color 0.3s, color 0.3s;
                }

                .button:hover {
                    background-color: #ff934d; /* Changement de couleur de fond au survol */
                }

            </style>
        </head>
        <body>
        <div class="greeting">Bonjour <?php echo $v_n . " " . $v_p; ?><p>voici vos commandes:</p
        ></div>
        <?php

        // Requête pour récupérer les produits vendus par ce vendeur
        $sql_produits_vendeur = "SELECT produit_id FROM produits_vendeurs WHERE vendeur_id = '$id_vendeur'";
        $result_produits_vendeur = $conn->query($sql_produits_vendeur);

        if ($result_produits_vendeur->num_rows > 0) {
            while ($row_produit = $result_produits_vendeur->fetch_assoc()) {
                $produit_id = $row_produit['produit_id'];

                // Requête pour récupérer les détails de commande pour ce produit
                $sql_details_commande = "SELECT commande_details.id_commande, commande_details.quantite, commande_details.description_client, commande.date_commande
                                        FROM commande_details
                                        INNER JOIN commande ON commande_details.id_commande = commande.id_commande
                                        WHERE commande_details.id_produit = '$produit_id'";
                $result_details_commande = $conn->query($sql_details_commande);

                if ($result_details_commande->num_rows > 0) {
                    while ($row_detail = $result_details_commande->fetch_assoc()) {
                        $id_commande = $row_detail['id_commande'];
                        $quantite = $row_detail['quantite'];
                        $description = $row_detail['description_client'];
                        $date_commande = $row_detail['date_commande'];

                        // Requête pour récupérer les informations du client pour cette commande
                        $sql_client = "SELECT clients.nom, clients.prenom, clients.user_image 
                                       FROM clients 
                                       INNER JOIN commande ON clients.email = commande.email 
                                       WHERE id_commande= '$id_commande'";
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

                                echo "<div class='commande'>";
                                echo "<img src='image/$image_client' alt='Image Client' style='width: 1cm; height: 1cm; object-fit: cover; border-radius: 5px;'>";
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
</body>
</html>
