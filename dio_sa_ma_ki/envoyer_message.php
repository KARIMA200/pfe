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

// Récupérer l'ID du produit spécifique (remplacez ID_DU_PRODUIT par l'ID réel)
$id_du_produit = $_POST['produit_id'];

// Préparez la requête SQL pour récupérer l'ID du vendeur en fonction de l'ID du produit
$stmt = $conn->prepare("SELECT vendeur_id FROM produits_vendeurs WHERE produit_id = ?");
$stmt->bind_param("i", $id_du_produit);
$stmt->execute();
$result = $stmt->get_result();

// Vérifier s'il y a des résultats
if ($result->num_rows > 0) {
    // Récupérer la première ligne de résultats
    $row = $result->fetch_assoc();
    // L'ID du vendeur
    $id_du_vendeur = $row['vendeur_id'];
} else {
    echo "Aucun vendeur trouvé pour le produit avec l'ID $id_du_produit.";
    exit;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Envoyer un message</title>
    <link rel="stylesheet" href="css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .chat-container {
            width: 400px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        .chat-header {
            background-color: #4caf50;
            color: white;
            padding: 10px;
            display: flex;
            align-items: center;
        }
        .user-avatar img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .chat-header span {
            font-weight: bold;
        }
        .chat-messages {
            flex: 1;
            padding: 10px;
            overflow-y: auto;
        }
        .chat-input {
            border-top: 1px solid #ddd;
            display: flex;
            align-items: center;
            padding: 10px;
        }
        .chat-input textarea {
            width: 100%;
            height: 50px;
            border: none;
            resize: none;
            padding: 10px;
            border-radius: 4px;
            margin-right: 10px;
        }
        .attachment-icons img {
            width: 24px;
            height: 24px;
            margin-right: 5px;
            cursor: pointer;
        }
        #sendMessageBtn {
            background-color: #4caf50;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        #sendMessageBtn:hover {
            background-color: #45a049;
        }
        #recordButton {
        background-color: transparent;
        border: none;
        cursor: pointer;
    }
    .recording {
        background-color: #f00; /* Couleur de fond pendant l'enregistrement */
    }
    #stopRecordingBtn {
        display: none;
    }
    #deleteRecordingBtn {
        display: none;
    }
    #sendRecording{background-color: #4caf50;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-bottom: 10px;}
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">
            <div class="user-avatar">
                <?php
                // Préparez la requête SQL pour récupérer le nom et le prénom du vendeur
                $sql = "SELECT nom, prenom, user_image, email FROM vendeurs WHERE id = '$id_du_vendeur'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $nom_vendeur = $row['nom'];
                    $prenom_vendeur = $row['prenom'];
                    $email_v = $row['email'];

                    $image_path = "image/" . $row['user_image'];
                    if (file_exists($image_path)) {
                        if (!is_readable($image_path)) {
                            chmod($image_path, 0644);
                        }
                        echo '<img src="' . $image_path . '" alt="' . $row['nom'] . '">';
                    } else {
                        echo '<img src="chemin_vers_image_par_defaut/default_image.jpg" alt="Image par défaut">';
                    }
                    $vendeur_nom = "$nom_vendeur";
                    $vendeur_prenom = "$prenom_vendeur";
                ?>
            </div>
            <span><?php echo $vendeur_nom . " " . $vendeur_prenom; ?></span>
        </div>
        <div class="chat-messages">
            <!-- Messages à afficher seront ajoutés ici via JavaScript -->
        </div>
        <div class="chat-input">
            <form id="messageForm" action="upload.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="product_id" value="123">
                <input type="hidden" name="vendeur_id" value="<?php echo $id_du_vendeur; ?>">
                <input type="hidden" name="email_v" value="<?php echo $email_v; ?>">
                <textarea id="messageInput" name="message" placeholder="Type your message..."></textarea>
                <div id="recordingsList"></div>        
    <i id="startRecording" class="fas fa-microphone"></i>
    <i id="image" class="fa-solid fa-file"></i>
    <input type="file" name="audio" id="audioInput" accept="audio/*" style="display
: none;">
    <input type="file" name="image" id="imageInput" accept="image/*" style="display: none;">
    <button id="sendRecording">Send </button>
            </form>
        </div>
    </div>
    <script>
        let mediaRecorder;
        let recordedChunks = [];
        let isRecording = false;

        document.getElementById('startRecording').addEventListener('click', function() {
            if (!isRecording) {
                startRecording();
            } else {
                stopRecording();
            }
        });

        document.getElementById('sendRecording').addEventListener('click', sendRecording);

        function startRecording() {
            navigator.mediaDevices.getUserMedia({ audio: true })
                .then(function(stream) {
                    mediaRecorder = new MediaRecorder(stream);
                    mediaRecorder.start();

                    isRecording = true;
                    document.getElementById('startRecording').classList.add('recording');
                    document.getElementById('startRecording').classList.remove('fa-microphone');
                    document.getElementById('startRecording').classList.add('fa-stop');

                    mediaRecorder.addEventListener('dataavailable', function(event) {
                        recordedChunks.push(event.data);
                    });

                    mediaRecorder.addEventListener('stop', function() {
                        isRecording = false;
                        document.getElementById('startRecording').classList.remove('recording');
                        document.getElementById('startRecording').classList.add('fa-microphone');
                        document.getElementById('startRecording').classList.remove('fa-stop');

                        let blob = new Blob(recordedChunks, { type: 'audio/wav' });
                        let url = URL.createObjectURL(blob);
                        let audio = document.createElement('audio');
                        audio.controls = true;
                        audio.src = url;

                        // Créer un élément pour chaque enregistrement avec une icône de poubelle
                        let recordingItem = document.createElement('div');
                        recordingItem.classList.add('recording-item');
                        recordingItem.appendChild(audio);
                        
                        let deleteIcon = document.createElement('i');
                        deleteIcon.classList.add('fas', 'fa-trash', 'delete-icon');
                        deleteIcon.addEventListener('click', function() {
                            // Supprimer l'enregistrement de la liste
                            recordingItem.remove();
                        });
                        recordingItem.appendChild(deleteIcon);

                        document.getElementById('recordingsList').appendChild(recordingItem);
                    });
                });
        }

        function stopRecording() {
            mediaRecorder.stop();
        }

        function sendRecording() {
            if (recordedChunks.length === 0) {
                alert("No recording to send!");
                return;
            }

            let blob = new Blob(recordedChunks, { type: 'audio/wav' });

            let formData = new FormData();
            formData.append('audio', blob, 'recording.wav');

            let xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        let filePath = xhr.responseText;
                        console.log("File path:", filePath);
                        // Stocker le chemin du fichier dans une variable ou effectuer d'autres actions nécessaires
                    } else {
                        console.error('Error:', xhr.status);
                    }
                }
            };

            xhr.open('POST', 'upload.php', true);
            xhr.send(formData);
        }

        document.getElementById('image').addEventListener('click', function() {
            document.getElementById('imageInput').click();
        });

        document.getElementById('imageInput').addEventListener('change', function(event) {
            let file = event.target.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function() {
                    displayImage(reader.result);
                };
                reader.readAsDataURL(file);
            }
        });

        function displayImage(imageUrl) {
            let image = document.createElement('img');
            image.src = imageUrl;

            let deleteIcon = document.createElement('i');
            deleteIcon.classList.add('fa-solid', 'fa-trash', 'delete-icon');
            deleteIcon.addEventListener('click', function() {
                // Supprimer l'image
                image.parentNode.removeChild(image);
                deleteIcon.parentNode.removeChild(deleteIcon);
            });

            let recordItem = document.createElement('div');
            recordItem.classList.add('record-item');
            recordItem.appendChild(image);
            recordItem.appendChild(deleteIcon);

            document.getElementById('recordingsList').appendChild(recordItem);
        }

        document.getElementById('sendRecording').addEventListener('click', function() {
            let image = document.querySelector('#recordingsList img');
            if (image) {
                // Envoi de l'image au serveur
                let formData = new FormData();
                formData.append('image', image.src);

                let xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            console.log('Image envoyée avec succès');
                            // Réinitialiser la liste des enregistrements après l'envoi
                            document.getElementById('recordingsList').innerHTML = '';
                        } else {
                            console.error('Erreur lors de l\'envoi de l\'image:', xhr.status);
                        }
                    }
                };

                xhr.open('POST', 'upload_image.php', true);
                xhr.send(formData);
            } else {
                console.log('Aucune image à envoyer.');
            }
        });
    </script>
</body>
</html>

<?php
$conn->close();}
?>
