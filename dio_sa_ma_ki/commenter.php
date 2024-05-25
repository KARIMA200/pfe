

<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce";
session_start(); // Démarrer la session

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérifier si l'identifiant du produit est présent dans la requête POST
if (isset($_POST['produit_id'])) {
    // Récupérer l'identifiant du produit depuis la requête POST
    $produit_id = $_POST['produit_id'];

    // Requête SQL pour récupérer les commentaires du produit spécifié
    $sql = "SELECT * FROM commentaires WHERE produit_id = $produit_id";
    $result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre titre ici</title>
    <!-- Inclure les styles CSS -->
    <link rel="stylesheet" href="css/all.min.css">
    <style>
        .liked {
            color: red; /* Couleur de l'icône de cœur lorsqu'il est aimé */
        }
        /* Style du curseur lorsqu'il est activé (clic sur l'icône) */
        #icon:active {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container" style="width: 10cm; border: 1px solid green;">
        <!-- Ici seront affichés les commentaires -->
        <?php
        // Afficher les commentaires
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='comment' style='background-color: #4bcdA2; border-radius: 10px; padding: 10px; margin-bottom: 20px;'>";
                // Afficher l'image de l'utilisateur
                echo "<img class='petite-image' src='image/" . $row['image'] . "' alt='" . $row['nom'] . "' style='width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; float: left;'>";
                // Afficher le nom et prénom de l'utilisateur dans le même conteneur
                echo "<div class='user-name' style='font-weight: bold; font-size: 1.1em; color: #fff; margin-top: 8px;'>{$row['prenom']} {$row['nom']}</div>";
                echo "<div class='content' style='margin-top: 10px; color: #fff;'>{$row['commentaire']}</div>";
                // Afficher les icônes pour les actions (inspirées de Facebook)
                echo '<div class="actions" style="margin-top: 10px; color: #fff;">';
                echo '<span class="clickCount">' . $row['nombre_clics'] . '</span>';
                echo '<i class="fas fa-heart"></i>';
                echo '<i class="fa-solid fa-trash" data-comment-id="' . $row['id'] . '"></i>';
                echo '<i class="fa-solid fa-comment"></i>';
                echo "<div class='timestamp' style='font-size: 0.6em;'>" . $row['date_commentaire'] . "</div>";
                echo '</div>';
                // Afficher le formulaire de réponse
                echo '<form class="reply-form" action="repondre_commentaire.php" method="POST" style="display: none;">';
                echo '<textarea name="ecrire un commentaire" placeholder="Répondre au commentaire"></textarea>';
                echo '<button type="submit">Envoyer</button>';
                echo '</form>';
                // Afficher le nombre de réponses
                echo '<div class="reponse-count">0 réponses</div>';
                echo '</div>';
            }
        } else {
            echo "Aucun commentaire pour ce produit.";
        }
        ?>
        <!-- Formulaire pour ajouter un nouveau commentaire -->
        <form action="ajouter_commentaire.php" method="POST">
            <input type="hidden" name="produit_id" value="<?php echo $produit_id; ?>">
            <textarea name="commentaire" placeholder="Écrire un commentaire"></textarea>
            <label for="file-upload" class="image-icon">&#128247;</label>
            <input id="file-upload" type="file" name="image" accept="image/*" style="display: none;">
            <button type="submit">Commenter</button>
        </form>
    </div>

    <!-- Script JavaScript pour afficher/masquer le formulaire de réponse et le nombre de réponses -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Gestion des clics sur l'icône de cœur
            document.querySelectorAll('.fa-heart').forEach(function(heartIcon) {
                heartIcon.addEventListener('click', function() {
                    var clickCountSpan = this.parentElement.querySelector('.clickCount');
                    var clickCount = parseInt(clickCountSpan.innerText);

                    if (this.classList.contains('liked')) {
                        // Décrémenter le nombre de clics
                        clickCount--;
                        this.classList.remove('liked');
                    } else {
                        // Incrémenter le nombre de clics
                        clickCount++;
                        this.classList.add('liked');
                    }

                    // Mettre à jour le nombre de clics affiché
                    clickCountSpan.innerText = clickCount;

                    // Envoyer une requête AJAX pour mettre à jour le nombre de clics dans la base de données
                    var commentId = this.getAttribute('data-comment-id');
                    fetch('update_clicks.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'comment_id=' + commentId + '&click_count=' + clickCount
                    })
                    .then(response => response.text())
                    .then(data => {
                        console.log(data);
                    })
                    .catch(error => {
                        console.error('Erreur lors de la mise à jour des clics:', error);
                    });
                });
            });

            // Gestion des clics sur l'icône de corbeille
            document.querySelectorAll('.fa-trash').forEach(function(trashIcon) {
                trashIcon.addEventListener('click', function() {
                    var commentId = this.getAttribute('data-comment-id');
                    if (confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')) {
                        fetch('supprimer_commentaire.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'commentaire_id=' + commentId
                        })
                        .then(response => response.text())
                        .then(data => {
                            if (data.includes('succès')) {
                                // Supprimer le commentaire du DOM
                                var commentDiv = document.querySelector(`.comment[data-comment-id="${commentId}"]`);
                                commentDiv.remove();
                            } else {
                                alert('Erreur lors de la suppression du commentaire.');
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>

</body>
</html>

<?php
} else {
    echo "Erreur: Aucun identifiant de produit fourni.";
}

// Fermer la connexion à la base de données
$conn->close();
?>
