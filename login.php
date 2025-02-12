<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    // Simple validation (for demonstration)
    if (!empty($username) && !empty($password)) {
        // Enregistrer l'utilisateur dans une session (en pratique, on utiliserait une base de données)
        $_SESSION['user'] = $username;
        
        // Rediriger vers la page de gestion des emails après l'inscription
        header("Location: indexx.php");
        exit();
    } else {
        $error_message = "⚠️ Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-10">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Sign in</h1>
        <form action="" method="POST" class="flex flex-col gap-4">
            <input type="text" name="username" placeholder="Nom d'utilisateur" required class="border border-gray-300 p-2 rounded focus:ring-2 focus:ring-blue-400">
            <input type="password" name="password" placeholder="Mot de passe" required class="border border-gray-300 p-2 rounded focus:ring-2 focus:ring-blue-400">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow">Se connecter</button>
        </form>
        <?php if (isset($error_message)): ?>
            <p class="text-red-500 text-sm mt-4"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>
        <p class="text-sm text-center mt-4"><a href="login.php" class="text-blue-500"></a></p>
    </div>
</body>
</html>
