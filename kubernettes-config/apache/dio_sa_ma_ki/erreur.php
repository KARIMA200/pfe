<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffebee;
            color: #d32f2f;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            text-align: center;
            padding: 20px;
            border: 2px solid #d32f2f;
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
        .error-message {
            color: #d32f2f;
            font-size: 20px;
            margin-top: 10px;
        }
    </style>
    <script>
        // Fonction de redirection après 2 secondes
        function redirectAfterTimeout(url) {
            setTimeout(function() {
                window.location.href = url;
            }, 1000); // 2000 millisecondes = 2 secondes
        }

        // Vérification des paramètres URL
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const page = urlParams.get('page');
            const message = urlParams.get('message');
            
            // Affichage du message d'erreur si présent
            if (message) {
                const errorMessageElement = document.createElement('h4');
                errorMessageElement.className = 'error-message';
                errorMessageElement.textContent = message;
                document.querySelector('.container').appendChild(errorMessageElement);
            }

            // Redirection en fonction de la valeur du paramètre "page"
            if (page === 'connexion.php' || page === 'inscription.php') {
    redirectAfterTimeout('index.html.html');
} else if (page === 'uu.php') {
    redirectAfterTimeout('uu.php');
} else if (page === 'uuv.php') {
    redirectAfterTimeout('uuv.php');
}
        };
    </script>
</head>
<body>
    <div class="container">
        <div class="icon">❌</div>
        <div class="message">Une erreur s'est produite. Veuillez réessayer.</div>
    </div>
</body>
</html>
