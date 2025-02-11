<?php  

if (!file_exists("Emails.txt")) {
    file_put_contents("Emails.txt", "");
}


$emails = file("Emails.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$email_frequency = [];


foreach ($emails as $email) {
    if (!isset($email_frequency[$email])) {
        $email_frequency[$email] = 1;
    } else {
        $email_frequency[$email]++;
    }
}

$error_message = '';
$show_modal = false; 
$email_pattern = "/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/"; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);

    
    if (!preg_match($email_pattern, $email)) {
        $error_message = '⚠ Email invalide.';
        $show_modal = true;
    } elseif (in_array($email, $emails)) {
      
        $error_message = '⚠ Email déjà enregistré.';
        $show_modal = true;
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
<body class="bg-gray-100 min-h-screen flex flex-col items-center p-10">

    <h1 class="text-3xl font-bold text-gray-800 mb-8">📧 Gestion des Emails</h1>

    <button onclick="toggleModal()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow mb-6">
        Ajouter
    </button>

   
    <div id="emailModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 <?= $show_modal ? '' : 'hidden' ?>">
        <div class="bg-white p-6 rounded-lg w-96">
            <h2 class="text-xl font-bold mb-4">Ajouter un Email</h2>
            <form action="" method="POST" class="flex flex-col gap-4">
                <input type="text" name="email" placeholder="Entrez un email" required 
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

      
        <div class="flex flex-col gap-4">
            <a href="supprimer_doublons.php" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded shadow w-52 text-center">
                🔄 Supprimer Doublons
            </a>
            <a href="trier_enregistrer.php" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded shadow w-52 text-center">
                📑 Trier & Enregistrer
            </a>
            <a href="separer_domaines.php" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded shadow w-52 text-center">
                🏷 Séparer Domaines
            </a>
        </div>
    </div>

    <script>
    function toggleModal() {
        document.getElementById("emailModal").classList.toggle("hidden");
    }
    </script>

</body>
</html>
