<?php
session_start();

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

$search_result = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search'])) {
    $search_query = $_POST['search'];
    
    // Préparez la requête SQL pour rechercher dans les tables vendeurs et clients
    $sql = "SELECT 'vendeur' as type, id, nom, prenom, user_image, email FROM vendeurs WHERE nom LIKE ? OR prenom LIKE ?
            UNION
            SELECT 'client' as type, id, nom, prenom, user_image, email FROM clients WHERE nom LIKE ? OR prenom LIKE ?";
    $stmt = $conn->prepare($sql);
    $search_param = "%" . $search_query . "%";
    $stmt->bind_param("ssss", $search_param, $search_param, $search_param, $search_param);
    $stmt->execute();
    $search_result = $stmt->get_result();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de la recherche</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .search-container {
            width: 30%;
            margin: 20px;
        }

        .search-results {
            width: 4cm;
            float: left;
            margin-right: 20px;
        }

        .search-results ul {
            list-style-type: none;
            padding: 0;
        }

        .search-results li {
            margin-bottom: 10px;
        }

        .search-results img {
            width: 50px;
            height: auto;
            border-radius: 50%;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="search-container">
        <h2>Résultats de la recherche</h2>
        <div class="search-results">
            <ul>
                <?php
                if ($search_result && $search_result->num_rows > 0) {
                    while ($row = $search_result->fetch_assoc()) {
                        $type = $row['type'];
                        $nom = $row['nom'];
                        $prenom = $row['prenom'];
                        $image_path = "image/" . $row['user_image'];
                        if (!file_exists($image_path)) {
                            $image_path = "chemin_vers_image_par_defaut/default_image.jpg";
                        }
                        echo '<li>';
                        echo '<img src="' . $image_path . '" alt="' . $nom . '">';
                        echo '<span>' . $nom . ' ' . $prenom . ' (' . $type . ')</span>';
                        echo '</li>';
                    }
                } else {
                    echo '<li>Aucun résultat trouvé.</li>';
                }
                ?>
            </ul>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
