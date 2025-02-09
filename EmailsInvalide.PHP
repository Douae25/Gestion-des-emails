<?php
$fichierEmails = "Emails.txt";
$fichierEmailsInvalides = "adressesNonValides.txt";
$fichierEmailsTries = "EmailsT.txt";

// Vérifier si le fichier existe
if (!file_exists($fichierEmails)) {
    die("Le fichier Emails.txt n'existe pas.");
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

// Enregistrer les emails invalides
file_put_contents($fichierEmailsInvalides, implode("\n", $emailsInvalides) . "\n");

// Réécrire le fichier Emails.txt avec les emails valides
file_put_contents($fichierEmails, implode("\n", $emailsValides) . "\n");

// Fonction pour supprimer les doublons
if (isset($_POST['supprimer_doublons'])) {
    // Supprimer les doublons
    $emailsValides = array_unique($emailsValides);  // Supprimer les doublons
    
    // Réécrire le fichier Emails.txt avec les emails sans doublons
    file_put_contents($fichierEmails, implode("\n", $emailsValides) . "\n");

    echo "Les doublons ont été supprimés avec succès.<br>";
}

// Fonction pour trier les emails
if (isset($_POST['trier_emails'])) {
    // Trier les emails
    sort($emailsValides);  // Trier les emails

    // Enregistrer les emails triés dans un nouveau fichier
    file_put_contents($fichierEmailsTries, implode("\n", $emailsValides) . "\n");

    echo "Les emails ont été triés et enregistrés dans $fichierEmailsTries.<br>";
}
?>

<!-- Formulaire HTML pour supprimer les doublons et trier les emails -->
<form method="POST">
    <button type="submit" name="supprimer_doublons">Supprimer les doublons</button>
    <button type="submit" name="trier_emails">Trier les emails</button>
</form>
