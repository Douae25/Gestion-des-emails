<html>
    <head></head>
    <body>
        <h1>Tableau des Emails</h1>
    <table border='1'>
        <thead>
            <tr>
                <th scope="col">Email</th>
                <th scope="col">Fréquence</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $fichier="Emails.txt";
            if(!file_exists($fichier)){
                die("Erreur : Le fichier est introuvable !");
            }
            
                $emails = file($fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                $Frequenceemail=array_count_values($emails);
                foreach ($Frequenceemail as $email => $count) {
                    echo "<tr>";

                    echo "<td>".$email."</td>";
                    echo "<td>".$count."</td>";

                    echo "</tr>";
                }
            

            ?>
        </tbody>
    </table>
    <a href="domaine_emails.php"><button>Emails par domaine</button></a>
    </body>
</html>