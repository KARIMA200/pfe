<?php
// Démarrez la session
session_start();

// Vérifiez si l'email est défini dans la session
if(isset($_SESSION['email'])) {
    // Récupérer l'email depuis la session
    $email = $_SESSION['email'];

    // Connexion à la base de données
    $servername = "localhost";
    $username = "votre_nom_utilisateur";
    $password = "votre_mot_de_passe";
    $dbname = "nom_de_votre_base_de_données";
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérifiez la connexion à la base de données
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Requête pour récupérer l'ID du vendeur basé sur l'email
    $sql_id = "SELECT id FROM vendeurs WHERE email = '$email'";
    $result_id = $conn->query($sql_id);

    // Vérifier si la requête a renvoyé des résultats
    if ($result_id->num_rows > 0) {
        // Récupérer l'ID du vendeur
        $row_id = $result_id->fetch_assoc();
        $vendeur_id = $row_id['id'];

        // Requête pour récupérer les produits du vendeur
        $sql_produits = "SELECT 
                            p.*,
                            (SELECT COUNT(*) FROM favoris WHERE favoris.product_id = p.id) AS favorie_count,
                            (SELECT COUNT(*) FROM commentaires WHERE commentaires.produit_id = p.id) AS commentaire_count,
                            v.nom AS nom_vendeur,
                            v.prenom AS prenom_vendeur
                        FROM 
                            produits p
                        LEFT JOIN 
                            produits_vendeurs pv ON p.id = pv.produit_id
                        LEFT JOIN 
                            vendeurs v ON pv.vendeur_id = v.id
                        WHERE 
                            pv.vendeur_id = '$vendeur_id'";
        $result_produits = $conn->query($sql_produits);

        // Vérifier si la requête a renvoyé des résultats
        if ($result_produits->num_rows > 0) {
            // Afficher les produits du vendeur
            while ($row_produit = $result_produits->fetch_assoc()) {
                // Afficher les détails du produit
                echo "Nom du produit: " . $row_produit['nom'] . "<br>";
                echo "Description: " . $row_produit['description'] . "<br>";
                // Afficher d'autres détails du produit selon vos besoins
                echo "<hr>"; // Ajouter une ligne horizontale entre chaque produit pour une meilleure lisibilité
            }
        } else {
            echo "Aucun produit trouvé pour ce vendeur.";
        }

    } else {
        echo "Aucun vendeur trouvé avec cet email.";
    }

    // Fermer la connexion à la base de données
    $conn->close();

} else {
    echo "Vous n'êtes pas connecté."; // Message à afficher si l'utilisateur n'est pas connecté
}
?>
