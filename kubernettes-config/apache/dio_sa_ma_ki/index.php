<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: flex-end;
            position: relative;
            overflow: hidden;
            background-color: #f8f9fa;
        }

        .background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('path_to_your_product_image.jpg'); /* Remplacez par le chemin de votre image */
            background-repeat: repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: -1;
        }

        .background h1 {
            font-size: 8em;
            color: #4bcdA2;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            position: absolute;
            left: 3cm;
            top: 27%;
            transform: translateY(-50%);
        }

        .login-section {
            position: relative;
            width: 45%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.9);
        }

        .form-container {
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            max-width: 350px;
            width: 100%;
            text-align: center;
        }

        .form-container h2 {
            margin-bottom: 20px;
            color: #343a40;
        }

        .form-container form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .form-container input {
            padding: 10px;
            border: 1px solid #4bcdA2;
            border-radius: 5px;
            font-size: 14px;
            color: #495057;
            width: calc(50% - 10px);
        }

        .form-container .full-width {
            width: calc(100% - 20px);
        }

        .form-container .input-row {
            display: flex;
            justify-content: space-between;
            align-items: center; /* Alignement vertical des éléments dans la ligne */
            gap: 10px; /* Espacement entre les éléments */
        }

        .form-container .image-row {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .form-container button {
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #4bcdA2;
            color: #ffffff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-container button:hover {
            background-color: #39a089;
        }

        .link {
            margin-top: 10px;
            font-size: 14px;
            color: #4bcdA2;
        }

        .link a {
            text-decoration: none;
            color: #4bcdA2;
            font-weight: bold;
            transition: color 0.3s;
        }

        .link a:hover {
            color: #0056b3;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
        }

        .footer a {
            margin: 0 10px;
            color: #4bcdA2;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer a:hover {
            color: #0056b3;
        }

        .footer i {
            font-size: 24px;
        }

        .hidden {
            display: none;
        }
        
        .radio-group {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .radio-group input[type="radio"] {
            accent-color: #4bcdA2;
        }

        .icon-button {
            background-color: #4bcdA2;
            color: white;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s;
        }

        .icon-button:hover {
            background-color: #39a089;
        }
    </style>
</head>

<body>
    <div class="background">
        <h1>Kya</h1>
    </div>
    <div class="login-section">
        <div id="loginContainer" class="form-container">
            <h2>Connexion</h2>
            <form action="connexion.php" method="post">
                <input type="email" id="email" name="email" placeholder="Email" class="full-width" required>
                <input type="password" id="password" name="password" placeholder="Mot de passe" class="full-width" required>
                <button type="submit">Se connecter</button>
            </form>
            <p class="link"><a href="oublie.html">J'ai oublié mon mot de passe</a></p>
            <p class="link"><a href="javascript:void(0);" onclick="showSignup()">Je n'ai pas de compte ? Je veux m'inscrire</a></p>
           
            <div class="footer">
                <a href="https://web.facebook.com/chakiri.karima.5/" target="_blank"><i class="fa-brands fa-facebook"></i></a>
                <a href="https://www.instagram.com/la_diabla_muerta/" target="_blank"><i class="fab fa-instagram"></i></a>
                <a href="https://www.linkedin.com" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                <a href="mailto:karima.chougri@gmail.com"><i class="fas fa-envelope"></i></a>
            </div>
        </div>

        <div id="signupContainer" class="form-container hidden">
           
            <form action="inscription.php" method="post" enctype="multipart/form-data">
                <div class="input-row">
                    <input type="text" id="firstName" name="prenom" placeholder="Prénom" required>
                    <input type="text" id="lastName" name="nom" placeholder="Nom" required>
                </div>
                <div class="input-row">
                    <input type="text" id="country" name="pays" placeholder="Pays" required>
                    <input type="text" id="city" name="ville" placeholder="Ville" required>
                </div>
                <div class="input-row">
                    <input type="tel" id="phone" name="telephone" placeholder="Téléphone" required>
                    <input type="email" id="emailSignup" name="email" placeholder="Email" required>
                </div>
                <div class="input-row">
                    <input type="text" id="adresse" name="adresse" placeholder="Adresse" required>
                    <input type="password" id="passwordSignup" name="password" placeholder="Mot de passe" required>
                </div>
                <div class="input-row">
                    <p>votre image:</p>
                    <label for="image"  class="icon-button"><i class="fa fa-upload"></i></label>
                    <input type="file" id="image" name="image" style="display: none;">
                </div>
                <div class="radio-group input-row">
                    <input type="radio" id="client" name="type_utilisateur" value="client" checked>
                    <label for="client">Client</label>
                    <input type="radio" id="vendeur" name="type_utilisateur" value="vendeur">
                    <label for="vendeur">Vendeur</label>
                </div>
            </php> <div class="g-recaptcha" data-sitekey="6Lcmr_YpAAAAAKsXtTxp64OXDw-cL07M8x8lgziy"></div>
                <button type="submit">S'inscrire</button>
            </form>
            <p class="link"><a href="javascript:void(0);" onclick="showLogin()">Se connecter</a></p>
           
        </div>
    </div>

    <script>
        function showSignup() {
            document.getElementById('loginContainer').classList.add('hidden');
            document.getElementById('signupContainer').classList.remove('hidden');
        }

        function showLogin() {
            document.getElementById('signupContainer').classList.add('hidden');
            document.getElementById('loginContainer').classList.remove('hidden');
        }

        // Script to update the text input with the chosen file name
        document.getElementById('image').addEventListener('change', function() {
            const fileName = this.files[0] ? this.files[0].name : 'Aucune image choisie';
            document.getElementById('imageText').value = fileName;
        });
    </script>
</body>
</html>
