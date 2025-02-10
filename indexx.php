<?php 

// Création du fichier s'il n'existe pas
if (!file_exists("Emails.txt")) {
    file_put_contents("Emails.txt", "");
}

// Chargement des emails enregistrés
$emails = file("Emails.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$email_frequency = [];

// Calcul des fréquences
foreach ($emails as $email) {
    if (!isset($email_frequency[$email])) {
        $email_frequency[$email] = 1;
    } else {
        $email_frequency[$email]++;
    }
}

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $email_pattern = "/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/";

    // Vérification du format de l'email
    if (!preg_match($email_pattern, $email)) {
        $error_message = '⚠ Email invalide.';
    } elseif (in_array($email, $emails)) {
        // Vérification si l'email existe déjà
        $error_message = '⚠ Email déjà enregistré.';
    } else {
        // Ajout de l'email au fichier
        file_put_contents("Emails.txt", $email . "\n", FILE_APPEND);
        
        // Mise à jour de la fréquence
        $email_frequency[$email] = 1;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Emails</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center p-10">

    <h1 class="text-3xl font-bold text-gray-800 mb-8">📧 Gestion des Emails</h1>

    <!-- Bouton pour ouvrir la modale -->
    <button onclick="toggleModal()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow mb-6">
        Ajouter
    </button>

    <!-- Modale pour ajouter un email -->
    <div id="emailModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg w-96">
            <h2 class="text-xl font-bold mb-4">Ajouter un Email</h2>
            <form action="" method="POST" class="flex flex-col gap-4" id="emailForm">
                <input type="text" name="email" id="email" placeholder="Entrez un email" required 
                    class="border border-gray-300 p-2 rounded w-full focus:ring-2 focus:ring-blue-400">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow">
                    Ajouter
                </button>
            </form>

            <!-- Affichage du message d'erreur -->
            <?php if ($error_message): ?>
                <p id="error-message" class="text-red-500 text-sm mt-4"><?= htmlspecialchars($error_message) ?></p>
            <?php endif; ?>

            <button onclick="toggleModal()" class="mt-4 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded shadow w-full">Fermer</button>
        </div>
    </div>

    <!-- Tableau des emails et de leur fréquence -->
    <div class="flex gap-6 w-full max-w-4xl mt-6">

        <div class="flex-1 bg-white shadow-lg rounded-lg overflow-hidden">
            <table class="table-auto w-full text-left">
                <thead class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <tr>
                        <th class="p-4">Email</th>
                        <th class="p-4">Fréquence</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    <?php foreach ($email_frequency as $email => $frequency): ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-100 <?= strpos($email, '[INVALIDE]') !== false ? 'bg-red-100' : '' ?>">
                            <td class="p-4"><?= htmlspecialchars($email) ?></td>
                            <td class="p-4"><?= $frequency ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Boutons supplémentaires -->
        <div class="flex flex-col gap-4">
            <a href="supprimer_doublons.php" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded shadow w-52 text-center">
                🔄 Supprimer Doublons
            </a>
            <a href="trier_enregistrer.php" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded shadow w-52 text-center">
                📑 Trier & Enregistrer
            </a>
            <a href="domaine_emails.php" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded shadow w-52 text-center">
                🏷 Séparer Domaines
            </a>
        </div>

    </div>

    <script>
    // Fonction pour afficher ou masquer la modale
    function toggleModal() {
        const modal = document.getElementById("emailModal");
        modal.classList.toggle("hidden");
    }
    </script>

</body>
</html>
