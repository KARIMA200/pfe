<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body> 

<h2>Inscription</h2>

<form id="inscriptionForm" action="inscription.php" method="post" enctype="multipart/form-data">
    
    <label for="nom">Nom:</label>
    <input type="text" id="nom" name="nom" required><br><br>

    <label for="prenom">Prénom:</label>
    <input type="text" id="prenom" name="prenom" required><br><br>

    <label for="pays">Pays:</label>
    <input type="text" id="pays" name="pays" required><br><br>
       

    <label for="ville">Ville:</label>
    <input type="text" id="ville" name="ville" required><br><br>
        

    <label for="adresse">Adresse:</label>
    <input type="text" id="adresse" name="adresse" required><br><br>

    <label for="telephone">Téléphone:</label>
    <input type="number" id="telephone" name="telephone" required><br><br>
    <label for="email">email:</label>
    <input type="email" id="email" name="email" required><br><br>


    <label for="password">Mot de passe:</label>
    <input type="password" id="password" name="password" required><br><br>
    
    <label for="image">votre image:</label><br>
    <input type="file" id="image" name="image"><br><br>

    <label>Type d'utilisateur:</label><br>
    <input type="radio" id="client" name="type_utilisateur" value="client" checked>
    <label for="client">Client</label><br>
    <input type="radio" id="vendeur" name="type_utilisateur" value="vendeur">
    <label for="vendeur">Vendeur</label><br><br>
</php> <div class="g-recaptcha" data-sitekey="6Lcmr_YpAAAAAKsXtTxp64OXDw-cL07M8x8lgziy"></div><br><br>
    <input type="submit" value="S'inscrire">
</form>

</body>
</html>