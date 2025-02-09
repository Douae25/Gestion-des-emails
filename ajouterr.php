<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);

 
    if (!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
        die("⚠ L'adresse email est invalide. <a href='indexx.php'>Retour</a>");
    }

   
    $emails = file("Emails.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

   
    if (in_array($email, $emails)) {
        die("⚠ L'email existe déjà. <a href='indexx.php'>Retour</a>");
    }

    
    file_put_contents("Emails.txt", $email . "\n", FILE_APPEND);
    header("Location: indexx.php");
    exit;
}
?>
``
