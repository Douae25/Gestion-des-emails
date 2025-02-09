<?php

if (!file_exists("Emails.txt")) {
    file_put_contents("Emails.txt", ""); // Crée un fichier vide
}


$emails = file("Emails.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
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
            $fichier="Emails.txt";

                $emails = file($fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                $Frequenceemail=array_count_values($emails);
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
        <a href="supprimer_invalides.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded shadow">
            🗑 Supprimer les emails invalides
        </a>
        <a href="supprimer_doublons.php" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded shadow">
            🔄 Supprimer les doublons
        </a>
        <a href="trier_enregistrer.php" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded shadow">
            📑 Trier et enregistrer
        </a>
        <a href="domaine_emails.php" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded shadow">
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
    </script>

</body>
</html>
