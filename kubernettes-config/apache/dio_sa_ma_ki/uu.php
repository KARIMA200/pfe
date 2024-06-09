<?php
// Placez ce code au début du fichier PHP pour initialiser la connexion à la base de données

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

session_start();
$email = $_SESSION['email'];

// Récupérer les informations du vendeur
$sql_vendeur = "SELECT * FROM clients WHERE email = ?";
$stmt_vendeur = $conn->prepare($sql_vendeur);
$stmt_vendeur->bind_param("s", $email);
$stmt_vendeur->execute();
$result_vendeur = $stmt_vendeur->get_result();
$vendeur = $result_vendeur->fetch_assoc();

// Rechercher dans la table clients si pas trouvé dans vendeurs



// Construction de la requête SQL pour les produits
$sql = "SELECT p.*, 
(SELECT COUNT(*) FROM favoris WHERE favoris.product_id = p.id) AS favorie_count,
(SELECT COUNT(*) FROM commentaires WHERE commentaires.produit_id = p.id) AS commentaire_count,
v.nom AS nom_vendeur,
v.prenom AS prenom_vendeur
FROM produits p
LEFT JOIN produits_vendeurs pv ON p.id = pv.produit_id
LEFT JOIN vendeurs v ON pv.vendeur_id = v.id";

// Initialiser un tableau pour les conditions
$conditions = [];
$params = [];
$types = "";

// Vérifier si une catégorie est spécifiée
if (isset($_GET['category'])) {
    $category = $_GET['category'];
    $conditions[] = "categorie = ?";
    $params[] = $category;
    $types .= "s";
}

// Vérifier si des paramètres de prix sont spécifiés
if (isset($_GET['min_price']) && isset($_GET['max_price'])) {
    $min_price = $_GET['min_price'];
    $max_price = $_GET['max_price'];
    $conditions[] = "prix BETWEEN ? AND ?";
    $params[] = $min_price;
    $params[] = $max_price;
    $types .= "dd";
} elseif (isset($_GET['min_price'])) {
    $min_price = $_GET['min_price'];
    $conditions[] = "prix >= ?";
    $params[] = $min_price;
    $types .= "d";
}

// Vérifier si un paramètre de recherche est spécifié
if (isset($_POST['search'])) {
    $search_term = '%' . $_POST['search'] . '%';
    $conditions[] = "p.nom LIKE ?";
    $params[] = $search_term;
    $types .= "s";
}

// Ajouter les conditions à la requête SQL
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

// Préparer et exécuter la requête SQL
$stmt = $conn->prepare($sql);
if ($types) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Compter les messages non lus
$unreadSql = "SELECT COUNT(*) as unread_count FROM messages WHERE destinataire = ? AND lu = 0";
$unreadStmt = $conn->prepare($unreadSql);
$unreadStmt->bind_param("s", $email);
$unreadStmt->execute();
$result0 = $unreadStmt->get_result();
$row0 = $result0->fetch_assoc();
$unreadCount = $row0['unread_count'];
$unreadStmt->close();

// Compter les notifications non lues
$unreadSql1 = "SELECT COUNT(*) as nb_notif FROM notifications WHERE user_2 = ? AND lu = 0";
$unreadStmt1 = $conn->prepare($unreadSql1);
$unreadStmt1->bind_param("s", $email);
$unreadStmt1->execute();
$result1 = $unreadStmt1->get_result();
$row1 = $result1->fetch_assoc();
$nb_notif = $row1['nb_notif'];
$unreadStmt1->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu déroulant</title>
    <link rel="stylesheet" href="css/all.min.css">
    <style>
        /* Style pour le conteneur du header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            height: 30px;
            background-color: rgb(75, 205, 162);
            padding: 10px 20px;
            z-index: 1000;
        }
        #zoneVoirPanier {
            display: none;
            position: fixed;
            top: 10px;
            right: 10px;
            width: 300px;
            height: 400px;
            border: 1px solid #ccc;
            background-color: #fff;
            padding: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
            overflow-y: auto;}
        .left-container {
            display: flex;
            align-items: center;
        }
        .wsshopmyaccount-container {
            display: flex;
            align-items: center;
        }
        .wsshopmyaccount {
            position: relative;
            margin-right: 20px;
            display: inline-block;
        }
        .wsshopmyaccount:last-child {
            margin-right: 0;
        }
        .q {
            display: inline-block;
            padding: 10px 20px;
            background-color: #fff;
            border-radius: 5px;
            text-decoration: none;
            color: #333;
            font-weight: bold;
            transition: background-color 0.3s, color 0.3s;
        }
        .q:hover {
            background-color: #f5f5f5;
            color: #555;
        }
        .sub-menu {
            display: none;
            position: absolute;
            background-color: #fff;
            min-width: 160px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 12px 0;
            z-index: 1;
            border-radius: 6px;
            border: 1px solid #ddd;
        }
        .wsshopmyaccount:hover .sub-menu {
            display: block;
        }
        .sub-menu li {
            list-style-type: none;
            margin: 5px 0;
        }
        .sub-menu li a {
            color: #333;
            text-decoration: none;
            display: block;
            padding: 8px 16px;
            transition: background-color 0.3s;
        }
        .sub-menu li a:hover {
            background-color: #f5f5f5;
        }
        .search-container {
            display: flex;
            align-items: center;
            background-color: #fff;
            border-radius: 20px;
            padding: 5px 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .search-container input[type="text"] {
            border: none;
            outline: none;
            padding: 10px;
            border-radius: 20px;
            flex: 1;
        }
        .search-container button {
            border: none;
            background-color: #4bcdA2;
            color: white;
            padding: 10px 15px;
            border-radius: 20px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .search-container button:hover {
            background-color: #3aa383;
        }
        .logo1, .logo3, .logo4 {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 20px;
        }
      
        .product img {
            max-width: 100%;
            height: auto;
            border-bottom: 1px solid #ddd; /* Ajouter une bordure en bas de l'image */
            margin-bottom: 10px;
        }
        .product h3 {
            color: #333;
            margin: 5px 0; /* Réduire la marge */
            font-size: 16px; /* Réduire la taille de la police */
        }
        .product p {
            color: #666;
            margin: 5px 0; /* Réduire la marge */
            font-size: 12px; /* Réduire la taille de la police */
        }
        .product .price {
            font-weight: bold;
            color: #333;
            margin: 5px 0; /* Réduire la marge */
            font-size: 14px; /* Réduire la taille de la police */
        }
        .comment-forms-container {
            display: flex;
            justify-content: space-between;
            gap: 5px; /* Réduire l'écart entre les boutons */
            margin-top: 5px; /* Réduire la marge */
        }
        .comment-button {
            background-color: #4bcdA2;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            padding: 5px; /* Réduire le padding */
            transition: background-color 0.3s, transform 0.2s, box-shadow 0.2s;
            width: 30px; /* Réduire la largeur */
            height: 30px; /* Réduire la hauteur */
        }
        .comment-button img {
            width: 100%;
            height: auto;
        }
        .comment-button:hover {
            background-color: #3aa383;
            transform: scale(1.1);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .comment-button:active {
            transform: scale(0.9);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        .container:hover .liste-cachée {
            display: block;
        }
        .center-icon {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        /* Ajout du curseur pointer */
        .profile-image{
            cursor: pointer;
        }  .profile-image {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
            cursor: pointer;
        }
        .success-message {
            width: 3cm;
            height: 3cm;
            background-color: lightgreen;
            border: 1px solid green;
            border-radius: 5px;
            padding: 10px;
            margin: 10px auto;
            text-align: center;
        }
        .close-button {
            position: absolute;
    top: 5px;
    right: 5px;
    background-color: red;
    border: none;
    color: white;
    font-weight: bold;
    padding: 5px;
    border-radius: 50%;
    cursor: pointer;
}
.icon{  cursor: pointer;}


.products-container {
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
    color: #ff7e5f; /* Couleur de prix */
    margin-top: 10px; /* Espacement en haut pour l'espace entre la description et le prix */
}.yellow {
    color: gold;
}.filled-heart {
    color: black; /* Changer la couleur de l'icône en noir */
}
.creative-link {
    display: inline-block;
    padding: 10px 20px;
    margin: 10px 0;
    font-size: 18px;
    font-weight: bold;
    color: white;
    text-decoration: none;
    background: linear-gradient(45deg, #ff7e5f, #feb47b);
    border-radius: 30px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.creative-link:before {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    width: 300%;
    height: 300%;
    background: rgba(255, 255, 255, 0.1);
    transition: all 0.5s ease;
    border-radius: 50%;
    transform: translate(-50%, -50%) scale(0);
}

.creative-link:hover {
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    background: linear-gradient(45deg, #ff6a6a, #febf7b);
}

.creative-link:hover:before {
    transform: translate(-50%, -50%) scale(1);
}
/* Container for the search form */
.creative-search {
    display: flex;
    justify-content: center;
    align-items: center;
    background: #f3f4f6;
    border-radius: 30px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    padding: 5px 10px;
    max-width: 400px;
    margin: 20px auto;
    transition: all 0.3s ease;
}

/* Input field for the search form */
.search-input {
    flex: 1;
    border: none;
    outline: none;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 30px;
    transition: all 0.3s ease;
    background: none;
    color: #333;
}

/* Search button for the search form */
.search-button {
    border: none;
    outline: none;
    padding: 10px 20px;
    margin-left: 10px;
    border-radius: 30px;
    background: linear-gradient(45deg, #ff7e5f, #feb47b);
    color: white;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

/* Hover effects for the search button */
.search-button:hover {
    background: linear-gradient(45deg, #ff6a6a, #febf7b);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
}

/* Focus effects for the search input */
.search-input:focus {
    background: #e9ecef;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

/* Adding some margin for better spacing */
.search-container {
    margin-bottom: 20px;
    margin-left:3cm;
}
/* Common styles for both 'Prix' and 'Catégorie' */
.creative-link {
    display: inline-block;
    padding: 10px 20px;
    margin: 10px 0;
    font-size: 18px;
    font-weight: bold;
    color: white;
    text-decoration: none;
    background: linear-gradient(45deg, #ff7e5f, #feb47b);
    border-radius: 30px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.creative-link:before {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    width: 300%;
    height: 300%;
    background: rgba(255, 255, 255, 0.1);
    transition: all 0.5s ease;
    border-radius: 50%;
    transform: translate(-50%, -50%) scale(0);
}

.creative-link:hover {
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    background: linear-gradient(45deg, #ff6a6a, #febf7b);
}

.creative-link:hover:before {
    transform: translate(-50%, -50%) scale(1);
}

.creative-menu .sub-menu {
    display: none;
    list-style: none;
    padding: 0;
    margin: 0;
    position: absolute;
    background: white;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
}

.creative-menu:hover .sub-menu {
    display: block;
}

.creative-sub-menu li {
    transition: background 0.3s;
}

.creative-sub-menu li a {
    display: block;
    padding: 10px 20px;
    color: #333;
    text-decoration: none;
    transition: all 0.3s ease;
}

.creative-sub-menu li a:hover {
    background: #ff7e5f;
    color: white;
}


.move-left {
        margin-left: 4cm; /* Déplace l'élément vers la gauche de 3cm */
    }    .icon { color:#ff7e5f;
        
        margin-right: 1cm; /* Espacement entre les icônes de 1cm */
    }
    #notification{ margin-left:1cm;}
    .vendor-link {
    text-decoration: none;
    color: rgb(75, 205, 162); /* Bleu */
    font-weight: bold;
    transition: color 0.3s, border-bottom 0.3s;
    border-bottom: 2px solid transparent;
}

.vendor-link:hover {
    color: #ff7e5f; /* Couleur légèrement plus foncée pour le survol */
    border-bottom: 2px solid #0056b3;
}

h3 {
    font-family: 'Arial', sans-serif;
    font-size: 18px;
    margin: 10px 0;
}.notif-popup
{    margin-top=-10cm;}
 </style>
</head>
<body>
    <div class="header">
    <div class="left-container">
    <div class="container">
        <?php
        if (isset($_SESSION['succes_message'])) {
            // Stocker le message de succès dans une variable locale
            $success_message = $_SESSION['succes_message'];
            // Supprimer la variable de session de succès pour qu'elle ne s'affiche pas à nouveau
            unset($_SESSION['succes_message']);
        }
    // Check if $vendeur is not null before accessing its properties
if ($vendeur) {
    // Access vendeur properties
    $image_path = "image/" . $vendeur['user_image'];
    // Check file existence and readability
    if (file_exists($image_path) && is_readable($image_path)) {
        echo '<img src="' . $image_path . '" alt="' . $vendeur['nom'] . '" class="logo1" id="logo-clickable">';
    } else {
        echo '<img src="chemin_vers_image_par_defaut/default_image.jpg" alt="Image par défaut" class="logo1" id="logo-clickable">';
    }
} elseif ($client) {
    // Access client properties
    // Modify as needed
} else {
    // Neither vendeur nor client found
    die("Utilisateur non trouvé");
} '<img src="chemin_vers_image_par_defaut/default_image.jpg" alt="Image par défaut" class="logo1" id="logo-clickable">';
        
        
        ?>
        <ul class="sub-menu creative-sub-menu" id="liste-cachée">
            <li><a href="#" id="voir-profil">Voir profil</a></li>
            <li><a href="voir_favorie.php">Favorie</a></li>
            <li><a href="#">Se déconnecter</a></li>
        </ul>
      
            </div>
            <div class="wsshopmyaccount-container">
    <ul>
    <li class="wsshopmyaccount creative-menu">
    <a href="#" class="q creative-link">Catégorie</a>
    <ul class="sub-menu creative-sub-menu">
        <li><a href="?category=Vetements">Vetements</a></li>
        <li><a href="?category=Chaussures">Chaussures</a></li>
        <li><a href="?category=Accessoire">Accessoires</a></li>
        <li><a href="?category=Électroniques">Électroniques</a></li>
        <li><a href="?category=Beauté et santé">Beauté et santé</a></li>
    </ul>
</li>

<li class="wsshopmyaccount creative-menu">
    <a href="#" class="q creative-link">Prix</a>
    <ul class="sub-menu creative-sub-menu">
        <li><a href="?min_price=0&max_price=50">0-50</a></li>
        <li><a href="?min_price=51&max_price=100">51-100</a></li>
        <li><a href="?min_price=101&max_price=150">101-150</a></li>
        <li><a href="?min_price=151&max_price=200">151-200</a></li>
        <li><a href="?min_price=201">Plus de 200</a></li>
    </ul>
</li>

    </ul>
</div>
        </div>  
        <form action="" method="POST" class="search-container creative-search">
    <input type="text" placeholder="Search..." name="search" class="search-input">
    <button type="submit" class="search-button">Search</button>
</form>

<!-- Icone pour ajouter un produit -->


<!-- Icône de notification de commentaire -->
<div class="icon right-container notification" id="comment" onclick="loadChatPage()">
    <i class="fa-solid fa-comment"></i>
    <?php if ($unreadCount > 0): ?>
        <span class="count"><?php echo $unreadCount; ?></span>
    <?php endif; ?>
</div>




<!-- Popup de chat -->
<div class="chat-popup" id="chat-popup"></div>

<!-- Icône de notification -->
<a href="notification.php">
    <div class="icon right_notification" id="notification">
        <i class="fa-solid fa-bell"></i>
        <?php if ($nb_notif > 0): ?>
            <span class="count"><?php echo $nb_notif; ?></span>
        <?php endif; ?>
    </div>
</a>
    <a href="voir_panier.php" id="karima" class="creative-link move-left">voir panier</a>


   
    </div>
    <div id="profile-content" style="display: none;"></div>
 
    
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
                      $stock = $row['stock'];
                      $stock_status = $stock > 0 ? "En stock" : "Rupture de stock";
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
    <p ><?php echo $row['description']; ?></p>
    <p ><?php echo $stock_status; ?></p>
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
    </form>    <form action="ajouter_panier.php" method="post">
                            <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="comment-button">
                            <i class="fa-solid fa-cart-shopping"></i>
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
 
                </div>
                <?php
            }
        } else {
            echo "Aucun produit trouvé.";
        }
        ?>
    </div>


    <script>


   

       
        document.getElementById('voir-profil').addEventListener('click', function(event) {
            // Empêcher le comportement par défaut du lien
            event.preventDefault();

            // Charger le contenu de profile.php dans #profile-content
            var profileContentDiv = document.getElementById('profile-content');
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    profileContentDiv.innerHTML = xhr.responseText;
                    profileContentDiv.style.display = 'block'; // Afficher le div une fois le contenu chargé
                }
            };
            xhr.open('GET', 'profile.php', true);
            xhr.send();

            // Cacher la liste cachée après le clic
            var listeCachee = document.getElementById('liste-cachée');
            listeCachee.style.display = 'none';
        });


        document.getElementById("logo-clickable").addEventListener("click", function() {
            var listeCachee = document.getElementById("liste-cachée");
            if (listeCachee.style.display === "none" || listeCachee.style.display === "") {
                listeCachee.style.display = "block";
            } else {
                listeCachee.style.display = "none";
            }
        });
        document.addEventListener('click', function(event) {
    var profileContentDiv = document.getElementById('profile-content');
    var targetElement = event.target; // Element sur lequel l'utilisateur a cliqué
    
    // Vérifier si l'élément cliqué est le div #profile-content ou un de ses enfants
    var isClickInsideProfileContent = profileContentDiv.contains(targetElement);
    
    // Si l'utilisateur clique en dehors de #profile-content, masquer le div
    if (!isClickInsideProfileContent) {
        profileContentDiv.style.display = 'none';
    }
});

    function loadCommentForm(productId) {
        var commentFormDiv = document.getElementById('comment-form-' + productId);
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                commentFormDiv.innerHTML = xhr.responseText;
                console.log('Page de commentaire chargée pour le produit ' + productId);
            }
        };
        xhr.open('GET', 'commenter.php?produit_id=' + productId, true);
        xhr.send();
    }
    function chargerPageVoirPanier() {
            fetch('voir_panier.php')
                .then(response => response.text())
                .then(data => {
                    const zoneVoirPanier = document.getElementById('zoneVoirPanier');
                    zoneVoirPanier.innerHTML = data;
                    zoneVoirPanier.style.display = 'block';

                    // Exécuter les scripts inclus dans le contenu chargé
                    const scripts = zoneVoirPanier.getElementsByTagName('script');
                    for (let script of scripts) {
                        eval(script.innerText);
                    }
                });
        }
 
        function closeMessage() {
            // Sélectionne la div du message de succès
            var successDiv = document.querySelector('.success-message');
            // Masque la div en la rendant invisible
            successDiv.style.display = 'none';
        }
        document.addEventListener('DOMContentLoaded', function() {
            const chatIcon = document.querySelector('.right-container');
            const chatPopup = document.getElementById('chat-popup');

            chatIcon.addEventListener('click', function() {
                if (chatPopup.style.display === 'none' || chatPopup.style.display === '') {
                    fetch('listechat1.php')
                        .then(response => response.text())
                        .then(html => {
                            chatPopup.innerHTML = html;
                            chatPopup.style.display = 'block';
                        })
                        .catch(error => console.error('Error loading chat:', error));
                } else {
                    chatPopup.style.display = 'none';
                }
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const chatIcon = document.getElementById('chat-icon');
            const chatPopup = document.getElementById('chat-popup');
            const addIcon = document.getElementById('add-icon');

});
function redirectToAddProduct() {
        window.location.href = 'add_produit.html';
    }

    function loadChatPage() {
        fetch('listechat1.php')
            .then(response => response.text())
            .then(data => {
                const chatPopup = document.getElementById('chat-popup');
                chatPopup.innerHTML = data;
                chatPopup.style.display = 'block';
            });
    }
  
    

    function chargerPageVoirPanier() {
        fetch('voir_panier.php')
            .then(response => response.text())
            .then(data => {
                const zoneVoirPanier = document.getElementById('zoneVoirPanier');
                zoneVoirPanier.innerHTML = data;
                zoneVoirPanier.style.display = 'block';

                // Exécuter les scripts inclus dans le contenu chargé
                const scripts = zoneVoirPanier.getElementsByTagName('script');
                for (let script of scripts) {
                    eval(script.innerText);
                }
            });
    }// Fonction pour charger le nombre de favoris via AJAX


// Appeler la fonction pour charger le nombre de favoris pour chaque produit
// Par exemple, si vous avez une liste de produits avec une classe "product", vous pouvez le faire comme ceci :
var produits = document.querySelectorAll('.product');
produits.forEach(function(produit) {
    var productId = produit.getAttribute('data-product-id');
    chargerNombreFavoris(productId);
});
document.addEventListener('DOMContentLoaded', function() {
    var button = document.querySelector('.comment-button');
    var heartIcon = button.querySelector('.fa-heart');

    button.addEventListener('click', function() {
        heartIcon.classList.toggle('filled-heart');
    });
});







</script>
    
</body>
</html>

<?php
$conn->close();
?>
