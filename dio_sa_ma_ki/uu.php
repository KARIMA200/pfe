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
$sql_vendeur = "SELECT * FROM vendeurs WHERE email = ?";
$stmt_vendeur = $conn->prepare($sql_vendeur);
$stmt_vendeur->bind_param("s", $email);
$stmt_vendeur->execute();
$result_vendeur = $stmt_vendeur->get_result();

// Rechercher dans la table clients si pas trouvé dans vendeurs
$sql_client = "SELECT * FROM clients WHERE email = ?";
$stmt_client = $conn->prepare($sql_client);
$stmt_client->bind_param("s", $email);
$stmt_client->execute();
$result_client = $stmt_client->get_result();



// Récupérer tous les produits de la table produits
$sql1 = "SELECT * FROM produits";
$result1 = $conn->query($sql1);

// Requête SQL pour compter le nombre de messages non lus pour ce vendeur
$sql2 = "SELECT COUNT(*) AS count FROM messages WHERE lu = 0 AND destinataire = '$email'";
$result2 = $conn->query($sql2);

// Nombre total de messages non lus pour ce vendeur
if ($result2->num_rows > 0) {
    $row = $result2->fetch_assoc();
    $total_unread_messages = $row['count'];
}
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
        .products-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
            margin-top: 70px; /* To avoid overlap with the fixed header */
        }
        .products-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px; /* Réduire l'écart entre les produits */
            padding: 20px;
            margin-top: 70px; /* Pour éviter le chevauchement avec l'en-tête fixe */
        }
        .product {
            width: 150px; /* Réduire la largeur du produit */
            border: 1px solid #ddd; /* Ajouter une bordure grise */
            border-radius: 5px; /* Ajouter des bordures arrondies */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Réduire l'ombre */
            overflow: hidden;
            text-align: center;
            background-color: #fff;
            padding: 10px; /* Réduire le padding */
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
        
        if ($result_vendeur->num_rows > 0) {
            $row = $result_vendeur->fetch_assoc();
        } elseif ($result_client->num_rows > 0) {
            $row = $result_client->fetch_assoc();
        } else {
            die("Utilisateur non trouvé");
        }
        $image_path = "image/" . $row['user_image'];
        if (file_exists($image_path) && is_readable($image_path)) {
            echo '<img src="' . $image_path . '" alt="' . $row['nom'] . '" class="logo1" id="logo-clickable">';
        } else {
            echo '<img src="chemin_vers_image_par_defaut/default_image.jpg" alt="Image par défaut" class="logo1" id="logo-clickable">';
        }
        
        ?>
        <ul class="sub-menu" id="liste-cachée">
            <li><a href="#" id="voir-profil">Voir profil</a></li>
            <li><a href="voir_favorie.php">Favorie</a></li>
            <li><a href="#">Se déconnecter</a></li>
        </ul>
      
            </div>
            <div class="wsshopmyaccount-container">
                <ul>
                    <li class="wsshopmyaccount">
                        <a href="#" class="q">Catégorie</a>
                        <ul class="sub-menu">
                            <li><a href="#">Vêtements</a></li>
                            <li><a href="#">Chaussures</a></li>
                            <li><a href="#">Accessoires</a></li>
                            <li><a href="#">Électroniques</a></li>
                            <li><a href="#">Beauté et santé</a></li>
                        </ul>
                    </li>
                    <li class="wsshopmyaccount">
                        <a href="#" class="q">Prix</a>
                        <ul class="sub-menu">
                            <li><a href="#">0-50</a></li>
                            <li><a href="#">51-100</a></li>
                            <li><a href="#">101-150</a></li>
                            <li><a href="#">151-200</a></li>
                            <li><a href="#">Plus de 200</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <div class="search-container">
            <input type="text" placeholder="Search...">
            <button>Search</button>
        </div>
        <div class="right-container">
            <img class="logo3" src="messenger1.png" alt="Logo 3">
    </div>
    <div class="center-icon clickable" onclick="chargerPageVoirPanier()">
        <i class="fa-solid fa-cart-shopping fa-3x"></i><br>
        Voir Panier
    </div>
    </div>
    <div id="profile-content" style="display: none;"></div>
    <div id="zoneVoirPanier" style="display: none;"></div>
    <div class="products-container">
    <div id="mot-content"></div>
    <?php if (isset($success_message)): ?>
        <!-- Div carrée pour afficher le message de succès -->
        <div class="success-message">
        <input type="submit" class="close-button" onclick="closeMessage()" value="X">
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>
        <?php
        // Vérifier s'il y a des produits à afficher
        if ($result1->num_rows > 0) {
            // Parcourir chaque produit
            while($row = $result1->fetch_assoc()) {
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
                    ?>
                    <h3><?php echo $row['nom']; ?></h3>
                    <p><?php echo $row['description']; ?></p>
                    <p class="price">Prix: <?php echo $row['prix']; ?>€</p>
                    <div class="comment-forms-container">
                        <form action="ajouter_panier.php" method="post">
                            <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="comment-button">
                                <img src="panier1.png" alt="Ajouter au panier">
                            </button>
                        </form>
                        <form action="envoyer_message.php" method="POST">
                            <input type="hidden" name="produit_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="comment-button">
                                <img src="messenger1.png" alt="Envoyer un message">
                            </button>
                        </form>
                        <form action="commenter.php" method="POST" class="comment-form">
                            <input type="hidden" name="produit_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="comment-button">
                                <img src="commente1.png" alt="Commenter">
                            </button>
                        </form>
                        <form action="ajouter_favorie.php" method="post">
                            <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="comment-button">
                            <i class="fa-regular fa-heart"></i>
                            </button>
                        </form>
                        <div class="comment-page" id="comment-page"></div>
                    </div>
                    <!-- commentaire.php -->


    <!-- Votre formulaire de commentaire ici -->
</div>

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
    </script>

    <!-- Zone où la page voir_panier.php sera chargée -->
   


    
</body>
</html>

<?php
$conn->close();
?>