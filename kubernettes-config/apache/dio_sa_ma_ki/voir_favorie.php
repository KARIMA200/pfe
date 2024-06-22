<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    die("Vous n'êtes pas connecté.");
}

// Récupérer l'email de l'utilisateur depuis la session
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
// Rechercher dans la table clients
$sql_client = "SELECT nom, prenom FROM clients WHERE email = ?";
$stmt_client = $conn->prepare($sql_client);
$stmt_client->bind_param("s", $email);
$stmt_client->execute();
$result_client = $stmt_client->get_result();

// Rechercher dans la table vendeurs si pas trouvé dans la table clients
if ($result_client->num_rows == 0) {
    $sql_vendeur = "SELECT nom, prenom FROM vendeurs WHERE email = ?";
    $stmt_vendeur = $conn->prepare($sql_vendeur);
    $stmt_vendeur->bind_param("s", $email);
    $stmt_vendeur->execute();
    $result_vendeur = $stmt_vendeur->get_result();

    if ($result_vendeur->num_rows > 0) {
        $row = $result_vendeur->fetch_assoc();
        $nom = $row['nom'];
        $prenom = $row['prenom'];
    } else {
        die("Aucun utilisateur trouvé avec cet email.");
    }
} else {
    $row = $result_client->fetch_assoc();
    $nom = $row['nom'];
    $prenom = $row['prenom'];
}

// Préparer et exécuter la requête SQL pour récupérer les product_id depuis la table favoris
$sql = "SELECT product_id FROM favoris WHERE user_email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu déroulant</title>
    <link rel="stylesheet" href="css/all.min.css">
    <style>
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
        #comment {
            margin-right: -80px;
        }

        .notification {
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
        }

        .favoris-count {
            color: red;
            font-size: 0.8em;
            font-weight: bold;
            margin-left: 5px;
        }

        .ratting {
            margin-right: -4cm;
            margin-top: -2cm;
            color: green;
            padding-right: 3cm;
        }

        img {
            width: 100%;
        }

        .product-info {
            flex-grow: 1;
            margin-right: 1cm;
        }

        .icons {
            display: flex;
            flex-direction: column;
            margin-left: 4.4cm;
            margin-top: -2.9cm;
        }

        .product-info h3 {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }

        .product-info p {
            font-size: 16px;
            color: #666;
            line-height: 1.6;
        }

        .product-info .price {
            font-size: 18px;
            color: #ff4500;
            margin-top: 10px;
        }

        .yellow {
            color: gold;
        }

        .filled-heart {
            color: black;
        }

        .greeting {
            font-size: 24px;
            color: #ff7e5f;
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }

        .greeting {
            font-size: 24px;
            color: #fff;
            font-family: 'Arial', sans-serif;
            text-align: center;
            padding: 20px 0;
            background-color: #ff7e5f;
            border-bottom: 4px solid #ff6b3c;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .greeting p {
            font-size: 18px;
            margin-top: 10px;
        }

        .greeting strong {
            font-weight: bold;
        }

        .greeting:hover {
            background-color: #ff934d;
            border-bottom-color: #ff8133;
            transition: background-color 0.3s, border-bottom-color 0.3s;
        }

        .button-container {
            display: inline-block;
            justify-content: center;
            margin-top: 30px;
        }

        .button {
            background-color: #ff7e5f;
            color: #fff;
            font-size: 16px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            margin: 0 10px;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
        }

        .button:hover {
            background-color: #ff934d;
        }
    </style>
</head>
<body>
<div class="greeting">Bonjour <?php echo $nom. " " . $prenom; ?><p>Voici vos produits favoris :</p></div>
<span class="products-container">
    <?php
    // Vérifier s'il y a des produits à afficher
    if ($result->num_rows > 0) {
        // Parcourir chaque produit
        while ($row = $result->fetch_assoc()) {
            // Récupérer les détails du produit depuis la table produits avec informations supplémentaires
            $sql_product = "SELECT 
                                p.*,
                                (SELECT COUNT(*) FROM favoris WHERE favoris.product_id = p.id) AS favorie_count,
                                (SELECT COUNT(*) FROM commentaires WHERE commentaires.produit_id = p.id) AS commentaire_count,
                                v.nom AS nom_vendeur,
                                v.email as email_vendeur,
                                v.prenom AS prenom_vendeur
                            FROM 
                                produits p
                            LEFT JOIN 
                                produits_vendeurs pv ON p.id = pv.produit_id
                            LEFT JOIN 
                                vendeurs v ON pv.vendeur_id = v.id
                            WHERE 
                                p.id = ?";
            $stmt_product = $conn->prepare($sql_product);
            $stmt_product->bind_param("i", $row['product_id']);
            $stmt_product->execute();
            $result_product = $stmt_product->get_result();

            if ($result_product->num_rows > 0) {
                while ($product = $result_product->fetch_assoc()) {
                    ?>
                    <div class="product">
                        <?php
                        // Chemin de l'image du produit
                        $image_path = "image/" . $product['image']; 
                        // Vérifier si le fichier image existe
                        if (file_exists($image_path)) {
                            if (!is_readable($image_path)) {
                                chmod($image_path, 0644);
                            }
                            echo '<img src="' . $image_path . '" alt="' . $product['nom'] . '">';
                        } else {
                            echo '<img src="chemin_vers_image_par_defaut/default_image.jpg" alt="Image par défaut">';
                        }
                        ?>

                        <span class="product-info">
                            <h3><?php echo $product['nom']; ?></h3>
                            <p><?php echo $product['description']; ?></p>
                            <p class="price">Prix: <?php echo $product['prix']; ?>€</p>
                          
                            <a href="pro.php?email=<?php echo urlencode($product['email_vendeur']); ?>"><?php echo $product['prenom_vendeur'] . " " . $product['nom_vendeur']; ?></a>

                        </span>

                        <span class="icons">
                          
                            <form action="commenter.php" method="POST" class="comment-form">
                                <input type="hidden" name="produit_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" class="comment-button">
                                    <i class="fa-regular fa-comment"></i>
                                    <span><?php echo $product['commentaire_count']; ?></span>
                                </button>
                            </form>
                            <form action="ajouter_favorie.php" method="post">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" class="comment-button">
                                    <i class="fa-regular fa-heart filled-heart"></i>
                                    <span><?php echo $product['favorie_count']; ?></span>
                                </button>
                          
                            </form>
                        </span>
   

                    </div>
                    <?php if ($result_client->num_rows > 0): ?>
    <a href='supprimer_favorie.php?product_id=<?php echo $product['id']; ?>'><i class="fa-solid fa-trash"></i></a>
    <a href='favorie_vers_panier.php?product_id=<?php echo $product['id']; ?>'><i class="fa-solid fa-cart-shopping"></i></a>
<?php endif; ?>

<?php if (isset($result_vendeur) && $result_vendeur->num_rows > 0): ?>
    <a href='supprimer_favorie.php?product_id=<?php echo $product['id']; ?>'><i class="fa-solid fa-trash"></i></a>
<?php endif; ?>

                    <?php
                }
            } else {
                echo "Aucun produit trouvé pour cet utilisateur.";
            }
        }
    } else {
        echo "Aucun produit favori trouvé pour cet utilisateur.";
    }

    // Fermer les requêtes et la connexion
    $stmt->close();
    $stmt_product->close();
    $conn->close();
    ?>
</span>
</body>
</html>
