<?php
// Démarrez la session
session_start();

// Vérifiez si l'email est défini dans la session
if(isset($_SESSION['email'])) {
    // Récupérer l'email depuis la session
    $email = $_SESSION['email'];

    // Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ecommerce";
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérifiez la connexion à la base de données
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Requête pour récupérer l'ID du vendeur basé sur l'email
    $sql_id = "SELECT * FROM vendeurs WHERE email = '$email'";
    $result_id = $conn->query($sql_id);

    // Vérifier si la requête a renvoyé des résultats
    if ($result_id->num_rows > 0) {
        // Récupérer l'ID du vendeur
        $row_id = $result_id->fetch_assoc();
        $vendeur_id = $row_id['id'];
        $v_n  = $row_id['nom'];
        $v_p  = $row_id['prenom'];

        // Requête pour récupérer les produits du vendeur
        $sql = "SELECT 
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
        $result = $conn->query($sql);

        // Vérifier si la requête a renvoyé des résultats
    
        

    } else {
        echo "Aucun vendeur trouvé avec cet email.";
    }

    // Fermer la connexion à la base de données
    $conn->close();

} else {
    echo "Vous n'êtes pas connecté."; // Message à afficher si l'utilisateur n'est pas connecté
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu déroulant</title>
    <link rel="stylesheet" href="css/all.min.css">
    <style>.products-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px; /* Espacement entre les produits */
            padding: 20px;
            margin-top: 70px;
        }

        .product {
            width: calc(20% - 20px); /* Ajustez la largeur des produits pour qu'il y en ait 5 par ligne */
 
            
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            text-align: center;
            background-color: #fff;
            padding: 10px;
            box-sizing: border-box;
            margin-bottom: 20px; /* Espacement entre les lignes de produits */
        }

        .product img {
            max-width: 100%;
            height: 5cm;
            border-bottom: 1px solid #ddd;
            margin-bottom: 10px;
        }

        .product h3 {
            color: #333;
            margin: 0; /* Réinitialisez la marge pour le nom */
            font-size: 16px;
            line-height: 1.2; /* Assurez-vous qu'il y a un espacement entre chaque nom de produit */
        }

        .product p, .product .price {
            margin: 0;
        }

        .comment-forms-container {
            display: block;
            justify-content: space-between;
            gap: 5px;
            margin-top: 10px; 
            margin-right: -3.5cm; /* Réduisez la marge */
        }
        #comment{

            margin-right:-80px;
        }   .notification {
            position: relative;
            display: inline-block;
        }

        .notification .count {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 10px;
            line-height: 1;
            text-align: center;
            min-width: 6px;
            height: 6px;
        }/* Style pour le nombre de favoris */
.favoris-count {
    color: red; /* Rose */
    font-size: 0.8em; /* Taille de police plus petite */
    font-weight: bold; /* Texte en gras */
    margin-left: 5px; 

    /* Marge à gauche pour l'espacement */
}
.ratting{margin-right:-4cm;
    margin-top:-2cm;
    color:green;
    badding-right:3cm;


}img{
    width:100%;
}.product-info {
    flex-grow: 1;
    margin-right:1cm; /* Permettre au bloc de prendre autant d'espace que possible */
}

.icons {
    display: flex; /* Utiliser flexbox pour aligner les icônes verticalement */
    flex-direction: column;
    margin-left:4.4cm;
    margin-top:-2.9cm; /* Aligner les icônes verticalement */
}
.product-info h3 {
    font-size: 24px;
    color: #333;
    margin-bottom: 10px; /* Ajouter une marge en bas pour l'espace entre le titre et la description */
}

.product-info p {
    font-size: 16px;
    color: #666;
    line-height: 1.6; /* Espacement des lignes pour une meilleure lisibilité */
}

.product-info .price {
    font-size: 18px;
    color: #ff4500; /* Couleur de prix */
    margin-top: 10px; /* Espacement en haut pour l'espace entre la description et le prix */
}.yellow {
    color: gold;
}.filled-heart {
    color: black; /* Changer la couleur de l'icône en noir */
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
<body>
<div class="greeting">Bonjour <?php echo $v_n . " " . $v_p; ?><p>voici votre produits:</p></div>
<span class="products-container" >

<div id="mot-content"></div>
<?php if (isset($success_message)): ?>
    <!-- Div carrée pour afficher le message de succès -->
    <div class="succe ss-message">
    <input type="submit" class="close-button" onclick="closeMessage()" value="X">
        <?php echo $success_message; ?>
    </div>
<?php endif; ?>
    <?php
    // Vérifier s'il y a des produits à afficher
    if ($result->num_rows > 0) {
        // Parcourir chaque produit
        while($row = $result->fetch_assoc()) {
            ?>
            <div class="product">
                <?php
                // Chemin de l'image du produit
                $image_path = "image/" . $row['image']; 
                // Vérifier si le fichier image existe
                if (file_exists($image_path)) {
                    // Vérifier si le fichier est lisible
                    if (!is_readable($image_path)) {
                        // Ajouter les droits de lecture
                        chmod($image_path, 0644);
                    }
                    // Afficher l'image du produit
                    echo '<img src="' . $image_path . '" alt="' . $row['nom'] . '">';
                } else {
                    // Si le fichier n'existe pas, afficher une image par défaut
                    echo '<img src="chemin_vers_image_par_defaut/default_image.jpg" alt="Image par défaut">';
                }
                $nom_vendeur = $row['nom_vendeur'];
                $prenom_vendeur = $row['prenom_vendeur'];
        
?>
                
             
                

                <span class="product-info">
<h3><?php echo $row['nom']; ?></h3>
<p><?php echo $row['description']; ?></p>
<p class="price">Prix: <?php echo $row['prix']; ?>€</p>



</span>

<span class="icons">
<form action="envoyer_message.php" method="POST">
    <input type="hidden" name="produit_id" value="<?php echo $row['id']; ?>">
    <button type="submit" class="comment-button">
        <i class="fa-brands fa-facebook-messenger"></i>
        <span><?php echo $commentaire_count = isset($row['commentaire_count']) ? $row['commentaire_count'] : 0;?></span>
    </button>
</form>
<form action="commenter.php" method="POST" class="comment-form">
    <input type="hidden" name="produit_id" value="<?php echo $row['id']; ?>">
    <button type="submit" class="comment-button">
        <i class="fa-regular fa-comment"></i>
        <span><?php echo $commentaire_count = isset($row['commentaire_count']) ? $row['commentaire_count'] : 0;?></span>
    </button>
</form>
<form action="ajouter_favorie.php" method="post">
    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
    <button type="submit" class="comment-button">
    <i class="fa-regular fa-heart filled-heart"></i>

        <span><?php echo isset($row['favorie_count']) ? $row['favorie_count'] : 0; ?></span>
    </button>
</form>


                  
                    <div class="comment-page" id="comment-page"></div>
            </span>
                <!-- commentaire.php -->
            
              
                
                <?php
// Supposons que $favoris_count est obtenu à partir de votre base de données
$favoris_count = $row['favorie_count'];

// Calculer le pourcentage (5 étoiles représentent 100%)
$pourcentage = ($favoris_count / 5) * 100;

// Déterminer le nombre d'étoiles pleines, demi-étoiles, et étoiles vides
$full_stars = floor($pourcentage / 20); // Chaque étoile représente 20%
$half_star = ($pourcentage % 20) >= 10 ? 1 : 0; // Si le reste est supérieur ou égal à 10, ajouter une demi-étoile
$empty_stars = 5 - ($full_stars + $half_star);

// Construire le HTML pour les étoiles
$etoiles_html = '';
for ($i = 0; $i < $full_stars; $i++) {
$etoiles_html .= '<li class="fa fa-star yellow"></li>'; // Ajoute la classe "yellow" pour rendre l'étoile jaune
}
if ($half_star) {
$etoiles_html .= '<li class="fa fa-star-half-alt yellow"></li>'; // Ajoute la classe "yellow" pour rendre l'étoile jaune
}
for ($i = 0; $i < $empty_stars; $i++) {
$etoiles_html .= '<li class="far fa-star yellow"></li>'; // Ajoute la classe "yellow" pour rendre l'étoile jaune
}

echo '<ul class="rating">' . $etoiles_html . '</ul>';


?>


<h3>by: <a href="page_vendeur.php?produit_id=<?php echo $row['id']; ?>" class="vendor-link"><?php echo $nom_vendeur . " " . $prenom_vendeur; ?></a></h3>


<!-- Votre formulaire de commentaire ici -->
            </span>
            <div class="button-container">
    <form action="modifier_produit.php" method="post">
        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
        <button type="submit" class="button">Modifier</button>
    </form>
    <form action="supprimer_produit.php" method="post">
        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
        <button type="submit" class="button">Supprimer</button>
    </form>
    <form action="details_produit.php" method="post">
        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
        <button type="submit" class="button">Voir Détails</button>
    </form>
</div>

            </div>
            <?php
        }
    } else {
        echo "Aucun produit trouvé.";
    }
    ?>
</div>
</body>
</html>