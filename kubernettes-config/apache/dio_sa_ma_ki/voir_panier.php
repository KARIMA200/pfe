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
if (isset($_SESSION['email'])) {
    // Récupérer l'email de l'utilisateur
    $email = $_SESSION['email'];
    $total = 0;
    
    // Requête pour obtenir l'ID du client à partir de son email
    $sql_client_id = "SELECT * FROM clients WHERE email = ?";
    $stmt_client_id = $conn->prepare($sql_client_id);
    $stmt_client_id->bind_param("s", $email);
    $stmt_client_id->execute();
    $result_client_id = $stmt_client_id->get_result();

    if ($result_client_id->num_rows > 0) {
        // Récupération de l'ID du client
        $row = $result_client_id->fetch_assoc();
        $user_id = $row["id"];
        $user_nom = $row["nom"];
        $user_prenom = $row["prenom"];
        
        // Requête pour vérifier si le champ livreur_vendeur_email contient une valeur pour cet utilisateur
        $sql_check_livreur = "SELECT livreur_vendeur_email FROM clients WHERE email = ?";
        $stmt_check_livreur = $conn->prepare($sql_check_livreur);
        $stmt_check_livreur->bind_param("s", $email);
        $stmt_check_livreur->execute();
        $result_check_livreur = $stmt_check_livreur->get_result();
        
        if ($result_check_livreur->num_rows > 0) {
            // Le champ livreur_vendeur_email contient une valeur pour cet utilisateur
            $row_livreur = $result_check_livreur->fetch_assoc();
            $livreur_email = $row_livreur['livreur_vendeur_email'];
            
            // Afficher le lien "Voir Commande"
        }
        
        // Requête pour obtenir les produits du panier pour ce client
        $sql_panier_produits = "SELECT pa.product_id as product_id, pa.id as panier_id, p.stock, p.nom, p.prix, p.image, pv.vendeur_id FROM panier pa JOIN produits p ON pa.product_id = p.id LEFT JOIN produits_vendeurs pv ON p.id = pv.produit_id WHERE pa.user_id = ?";
        $stmt_panier_produits = $conn->prepare($sql_panier_produits);
        $stmt_panier_produits->bind_param("i", $user_id);
        $stmt_panier_produits->execute();
        $result_panier_produits = $stmt_panier_produits->get_result();

        if ($result_panier_produits->num_rows > 0) {
            $total = 0;
            // votre code pour afficher les produits
        } else {
            $message = "Panier vide.";
        }
    } else {
        $message = "Aucun client trouvé avec cet email : $email";
    }
} else {
    $message = "Aucun email d'utilisateur trouvé en session.";
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Profil</title>

    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <!------ Include the above in your HEAD tag ---------->
  <style>.container{
width: 100%;

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
  </style>
</head>
<body>
<div class="greeting">Bonjour <?php echo $user_nom . " " . $user_prenom; ?><p>voici votre panier:</p></div>
<div class="container">
<?php
        // Afficher le message ici
        if (!empty($message)) {
            echo '<div class="alert alert-info">' . $message . '</div>';
        }
        ?>
    <div class="row">
        <div class="col-sm-12 col-md-10 col-md-offset-1">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>description</th>
                        <th class="text-center">Prix</th>
                        <th class="text-center">Total</th>
                        <th> </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row_produit = $result_panier_produits->fetch_assoc()) {
                        $panier_id = $row_produit['panier_id'];
                        $product_id = $row_produit["product_id"];
                        $quantite = 1; // Vous pouvez ajuster la quantité selon vos besoins
                        $total_par_produit = $row_produit["prix"] * $quantite;
                        $total += $total_par_produit;
                        $stock = $row_produit['stock'];
                        $stock_status = $stock > 0 ? "En stock" : "Rupture de stock";
                        // Requête pour obtenir les informations du vendeur
                        $vendeur_id = $row_produit["vendeur_id"];
                        
                        // Vérifier si le vendeur_id n'est pas null
                        if ($vendeur_id !== null) {
                            $sql_vendeur_info = "SELECT nom, prenom, email FROM vendeurs WHERE id = ?";
                            $stmt_vendeur_info = $conn->prepare($sql_vendeur_info);
                            $stmt_vendeur_info->bind_param("i", $vendeur_id);
                            $stmt_vendeur_info->execute();
                            $result_vendeur_info = $stmt_vendeur_info->get_result();
                            $row_vendeur = $result_vendeur_info->fetch_assoc();
                            
                            // Vérifier si les informations du vendeur existent
                            $nom_vendeur = isset($row_vendeur["nom"]) ? $row_vendeur["nom"] : "N/A";
                            $prenom_vendeur = isset($row_vendeur["prenom"]) ? $row_vendeur["prenom"] : "N/A";
                            $email_vendeur = isset($row_vendeur["email"]) ? $row_vendeur["email"] : "N/A";
                        } else {
                            $nom_vendeur = "inconu";
                            $prenom_vendeur = "";
                            $email_vendeur = "inconu";
                        }
                    ?>
                    <tr>
                        <td class="col-sm-8 col-md-6">
                        <div class="media">
                            <a class="thumbnail pull-left" href="#"> <img class="media-object" src="image/<?php echo $row_produit['image']; ?>" style="width: 72px; height: 72px;"> </a>
                            <div class="media-body">
                                <h4 class="media-heading"><a href="#"><?php echo $row_produit["nom"]; ?></a></h4>
                                <h5 class="media-heading">Vendu par <a href="pro.php?email=<?php echo urlencode($email_vendeur); ?>"><?php echo $nom_vendeur . " " . $prenom_vendeur; ?></a></h5>

                                <span>Status: </span><span class="text-success"><strong><?php echo $stock_status; ?></strong></span>
                            </div>
                        </div></td>
                        <td class="col-sm-1 col-md-1" style="text-align: center">
                            <form action="quantite_panier.php" method="post">
                                <input type="number" class="form-control" name="quantite_<?php echo $product_id; ?>" id="quantite_<?php echo $product_id; ?>" value="<?php echo $quantite; ?>" min="1">
                        </td>  <td class="col-sm-1 col-md-1" style="text-align: center">
                            <form action="quantite_panier.php" method="post">
                            <textarea class="textarea-style" placeholder="votre description du produit..."></textarea>

                    </td>
                        <td class="col-sm-1 col-md-1 text-center"><strong><?php echo $row_produit["prix"]; ?> €</strong></td>
                        <td class="col-sm-1 col-md-1 text-center"><strong><?php echo $total_par_produit; ?> €</strong></td>
                        <td class="col-sm-1 col-md-1">
                            <input type="hidden" name="panier_id" value="<?php echo $panier_id; ?>">
                            <button type="submit" class="btn btn-successr">
                                <span class="glyphicon glyphicon-play"></span> valider
                            </button>
                            </form>
                            <form action="supprimer_produit_panier.php" method="post">
                                <input type="hidden" name="panier_id" value="<?php echo $panier_id; ?>">
                                <button type="submit" class="btn btn-danger">
                                    <span class="glyphicon glyphicon-remove"></span> Annuler
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td><h3>Total</h3></td>
                        <td class="text-right"><h3><strong><?php echo $total; ?> €</strong></h3></td>
                    </tr>
                    <tr>
                        <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td>
                            <button type="button" class="btn btn-default" onclick="window.location.href='uu.php'">
                                <span class="glyphicon glyphicon-shopping-cart"></span> Retourner à la page d'accueil
                            </button>
                        </td>
                        <td>
                            <form action="traiter_commande.php" method="post">
                                <input type="hidden" name="panier_id" value="<?php echo $panier_id; ?>">
                                <button type="submit" class="btn btn-success" id="valider_commande">
                                    Valider commande <span class="glyphicon glyphicon-play"></span>
                                </button>
                            </form>
                            <form action="vider_panier.php" method="post">
                                <input type="hidden" name="panier_id" value="<?php echo $panier_id; ?>">
                                <button type="submit" class="btn btn-danger">
                                    <span class="glyphicon glyphicon-remove"></span> vider panier
                                </button>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

    <!-- Insérez votre code PHP ici -->
    <?php
        // Insérez votre code PHP ici
       
            
            // Afficher le lien "Voir Commande" avec le paramètre email
            echo "<a href='voir_commandes.php?email=$livreur_email'>Voir Commande</a>";
         ?>

</body>
</html>
<?php

      

// Fermer la connexion à la base de données
$conn->close();
?>
