<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opération Réussie</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e0f7fa;
            color: #00695c;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            text-align: center;
            padding: 20px;
            border: 2px solid #00695c;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .icon {
            font-size: 50px;
        }
        .message {
            font-size: 24px;
            margin-top: 10px;
        }
    </style>
    <script>
        // Fonction de redirection après 1 seconde
        function redirectAfterTimeout(url) {
            setTimeout(function() {
                window.location.href = url;
            }, 1000); // 1000 millisecondes = 1 seconde
        }

        // Vérification des paramètres URL
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const page = urlParams.get('page');
            const message = urlParams.get('message');
            
            // Affichage du message si présent
            if (message) {
                const messageElement = document.createElement('div');
                messageElement.className = 'message';
                messageElement.textContent = message;
                document.querySelector('.container').appendChild(messageElement);
            }

            // Redirection en fonction de la valeur du paramètre "page"
            if (page === 'uu.php') {
        redirectAfterTimeout('uu.php');
    } else if (page === 'uuv.php') {
        redirectAfterTimeout('uuv.php');
    } else if (page === 'ajouter_commentaire.php.php') {
        redirectAfterTimeout('ajouter_commentaire.php');}
        else if (page === 'inscription.php') {
        redirectAfterTimeout('index.html.html');}else if (page === 'connexion.php') {
        redirectAfterTimeout('index.html.html.php');}else if (page === 'pro_chat.php') {
        redirectAfterTimeout('pro_chat.php');
    } else if (page === 'add_produit.html') {
        redirectAfterTimeout('add_produit.html');
    } else if (page === 'voir_produits.php') {
        redirectAfterTimeout('voir_produits.php');
    }}
    </script>
</head>
<body>
    <div class="container">
        <div class="icon">✔️</div>
        <div class="message">L'opération a été effectuée avec succès !</div>
    </div>
</body>
</html>
