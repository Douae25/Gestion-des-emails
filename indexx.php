<?php  
session_start();

// Vérifier le mode d'affichage (trié ou non trié)
$fichier = "Emails.txt"; 
if (isset($_GET['tri']) && $_GET['tri'] === "oui") { 
    $fichier = "EmailsT.txt";
}

// Lire les emails depuis le fichier choisi
$emails = file($fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
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

    <form action="ajouterr.php" method="POST" onsubmit="return validerEmail();" 
        class="bg-white shadow-lg rounded-lg p-6 w-full max-w-lg mb-6 flex gap-2">
        <input type="email" name="email" id="email" placeholder="Entrez un email" required 
            class="border border-gray-300 p-2 rounded w-full focus:ring-2 focus:ring-blue-400">
        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow">
            Ajouter
        </button>
    </form>

    <p id="erreur" class="text-red-500 text-sm hidden">⚠ Email invalide !</p>

    <?php
    if (isset($_SESSION['message'])) {
        echo "<div class='bg-green-200 text-green-800 p-3 rounded mb-4'>{$_SESSION['message']}</div>";
        unset($_SESSION['message']); // Supprimer le message après affichage
    }
    ?>

    <div class="w-full max-w-2xl bg-white shadow-lg rounded-lg overflow-hidden">
        <table class="table-auto w-full text-left">
            <thead class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <tr>
                    <th class="p-4">Email</th>
                    <th class="p-4">Fréquence</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                <?php
                $Frequenceemail = array_count_values($emails);
                foreach ($Frequenceemail as $email => $count) {
                    echo "<tr class='border-b border-gray-200 hover:bg-gray-100'>";
                    echo "<td class='p-4'>".$email."</td>";
                    echo "<td>".$count."</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    
    <div class="mt-6 flex flex-wrap gap-3">
    <!-- Bouton Supprimer les doublons -->
    <form method="POST" action="EmailsInvalide.php">
        <button type="submit" name="supprimer_doublons"
            class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg shadow-md transition duration-300 inline-flex items-center">
            🔄 Supprimer les doublons
        </button>
    </form>

    <!-- Bouton Mode d'affichage -->
    <div class="relative">
        <button onclick="toggleMenu()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow-md transition duration-300 inline-flex items-center">
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

    <!-- Bouton Séparer par domaine -->
    <a href="domaine_emails.php" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg shadow-md transition duration-300 inline-flex items-center">
        🏷 Séparer par domaine
    </a>
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
    </script>

</body>
</html>
