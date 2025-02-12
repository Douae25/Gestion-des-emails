<?php include 'navbar.php'; ?>
<?php    
session_start();

// Création du fichier s'il n'existe pas
if (!file_exists("Emails.txt")) {
    file_put_contents("Emails.txt", "");
}

// Vérifier le mode d'affichage (trié ou non trié)
$fichier = "Emails.txt"; 
$fichierEmailsInvalides = "adressesNonValides.txt";

// Lire les emails
$emails = file($fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$emailsValides = [];
$emailsInvalides = [];

// Séparer les emails valides et invalides
foreach ($emails as $email) {
    $email = trim($email);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailsValides[] = $email;
    } else {
        $emailsInvalides[] = $email;
    }
}

// Enregistrer les emails invalides
file_put_contents($fichierEmailsInvalides, implode("\n", $emailsInvalides) . "\n");

// Réécrire le fichier Emails.txt avec les emails valides
file_put_contents($fichier, implode("\n", $emailsValides) . "\n");



if (isset($_GET['tri']) && $_GET['tri'] === "oui") { 
    $fichier = "EmailsT.txt";
}

// Lire les emails depuis le fichier choisi
$emails = file($fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];

// Chargement des emails enregistrés
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
$email_pattern = "/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);

    if (!preg_match($email_pattern, $email)) {
        $error_message = '⚠ Email invalide.';
    } elseif (in_array($email, $emails)) {
        $error_message = '⚠ Email déjà enregistré.';
    } else {
        file_put_contents("Emails.txt", $email . "\n", FILE_APPEND);
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
<body>
<div class="bg-gray-100 min-h-screen flex flex-col items-center p-10">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">📧 Gestion des Emails</h1>

    <button onclick="toggleModal()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow mb-6">
        Ajouter
    </button>

    <!-- Modale -->
    <div id="emailModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 <?= $error_message ? '' : 'hidden' ?>">
        <div class="bg-white p-6 rounded-lg w-96">
            <h2 class="text-xl font-bold mb-4">Ajouter un Email</h2>
            <form action="" method="POST" class="flex flex-col gap-4">
                <input type="text" name="email" placeholder="Entrez un email" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                    class="border border-gray-300 p-2 rounded w-full focus:ring-2 focus:ring-blue-400">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow">
                    Ajouter
                </button>
            </form>

            <?php if ($error_message): ?>
                <p class="text-red-500 text-sm mt-4"><?= htmlspecialchars($error_message) ?></p>
            <?php endif; ?>

            <button onclick="toggleModal()" class="mt-4 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded shadow w-full">Fermer</button>
        </div>
    </div>


    <?php
    if (isset($_SESSION['message'])) {
        echo "<div class='bg-green-200 text-green-800 p-3 rounded mb-4'>{$_SESSION['message']}</div>";
        unset($_SESSION['message']); 
    }
    ?>

    <!-- Tableau et boutons repositionnés -->
    <div class="flex gap-4 w-full max-w-4xl mt-6">
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
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="p-4 <?= !preg_match($email_pattern, $email) ? 'text-red-500' : '' ?>">
                                <?= htmlspecialchars($email) ?>
                            </td>
                            <td class="p-4"><?= $frequency ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

            <!-- Boutons -->
    <div class="flex flex-col gap-4"> <!-- Augmenté à 6 pour plus d'espace -->
        <form method="POST" action="EmailsInvalide.php">
            <button type="submit" name="supprimer_doublons"
                class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg shadow-md transition duration-300 text-center">
                🔄 Supprimer les doublons
            </button>
        </form>
        
        <a href="domaine_emails.php" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg shadow-md transition duration-300 text-center">
            🏷 Séparer par domaine
        </a>
        
        <button onclick="toggleMenu()" class="bg-green-500 hover:bg-green-600 text-white px-7 py-2 rounded-lg shadow-md transition duration-300 inline-flex items-center">
            📑 Mode d'affichage
            <svg class="w-4 h-4 ml-2 transition-transform duration-300" id="arrowIcon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
        <div id="menu" class="hidden absolute bg-white shadow-lg rounded-lg p-2 mt-2">
            <a href="EmailsInvalide.php?tri=oui" class="block px-4 py-2 text-gray-800 hover:bg-gray-200 rounded-md">📌 Tableau trié</a>
            <a href="indexx.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-200 rounded-md">📋 Tableau non trié</a>
        </div>
    </div>


    <script>
        function validerEmail() {
            let email = document.getElementById("email").value;
            let regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            let erreur = document.getElementById("erreur");

            if (!regex.test(email)) {
                erreur.classList.remove("hidden");
                return false;
            } else {
                erreur.classList.add("hidden");
                return true;
            }
        }

        function toggleMenu() {
            document.getElementById("menu").classList.toggle("hidden");
        }

        function toggleModal() {
        let modal = document.getElementById("emailModal");
        modal.classList.toggle("hidden");
    }
    </script>
    </div>
</body>
</html>
