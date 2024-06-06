<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changer Mot de Passe</title>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .change-password-container  {
            width: 300px;
            margin: 20px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .change-password-container h2 {
            text-align: center;
            color: #4bcdA2;
            margin-bottom: 20px;
        }

        .password-form {
            margin-top: 20px;
        }

        .password-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: border-color 0.3s;
        }

        .password-form input:focus {
            border-color: #4bcdA2;
            outline: none;
        }

        .password-form button {
            width: 100%;
            padding: 10px;
            border: none;
            background-color: #4bcdA2;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .password-form button:hover {
            background-color: #3ba88e;
        }

        .error-message {
            color: #dc3545;
            font-size: 14px;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="change-password-container">
        <h2>Changer Mot de Passe</h2>
        <!-- Vérification si le message est défini dans l'URL -->
        <?php if (isset($error_message)): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <!-- Formulaire de changement de mot de passe -->
        <form class="password-form" action="changer_password.php" method="POST">
            <input type="password" name="ancien" placeholder="Ancien Mot de Passe" required>
            <input type="password" name="nouveau" placeholder="Nouveau Mot de Passe" required>
            <input type="password" name="confirmer" placeholder="Confirmer le Nouveau Mot de Passe" required>
            <button type="submit">Changer Mot de Passe</button>
        </form>
    </div>
</body>
</html>
