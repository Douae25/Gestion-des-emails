<?php 
session_start();

$fichierEmails = "Emails.txt";
$fichierEmailsInvalides = "adressesNonValides.txt";
$fichierEmailsTries = "EmailsT.txt";

// Vérifier si le fichier existe
if (!file_exists($fichierEmails)) {
    file_put_contents($fichierEmails, ""); // Crée un fichier vide
}

// Lire les emails
$emails = file($fichierEmails, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
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


//  Vérifier si l'action demandée est un tri (GET request)
if (isset($_GET['tri']) && $_GET['tri'] === "oui") {
    // Trier les emails et les sauvegarder dans EmailsT.txt
    usort($emailsValides, 'strcasecmp');
    file_put_contents($fichierEmailsTries, implode("\n", $emailsValides) . "\n");

    $_SESSION['message'] = "Les emails ont été triés avec succès.";

    // Redirection vers index.php avec le mode trié
    header("Location: indexx.php?tri=oui");
    exit();
}

// suppression des doublons
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['supprimer_doublons'])) {
        $nbAvant = count($emailsValides);
        $emailsValides = array_unique($emailsValides);
        $nbApres = count($emailsValides);

        if ($nbAvant === $nbApres) {
            $_SESSION['message'] = "Aucun doublon trouvé.";
        } else {
            file_put_contents($fichierEmails, implode("\n", $emailsValides) . "\n");
            $_SESSION['message'] = "Doublons supprimés avec succès !";
        }

        header("Location: indexx.php");
        exit();
    }
}
?>
