<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Emails</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
    <style>
        .content-table{
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 0.9em;
            min-width: 400px;
            border-radius: 5px 5px 0 0;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        }


        .content-table thead tr{
            color: #ffffff;
            text-align: left;
            font-weight: bold;
        }

        .content-table th, .content-table td{
            padding: 12px 15px;
        }

        .content-table tbody tr{
            border-bottom: 1px solid #dddddd;
        }

        .content-table tbody tr:nth-of-type(even){
            background-color: #f3f3f3;
        }

        .content-table tbody tr:last-of-type{
            border-bottom: 2px solid #6366F1;
        }

         div {
            margin: 0;
            padding: 15px;
            line-height: 1.6;
        }

    </style>
    <body>
    <?php include 'navbar.php'; ?>
    <div>
    <?php
            $fichier="Emails.txt";

            if (!file_exists("Emails.txt")) {
                file_put_contents("Emails.txt", "");
            }
            
            $emails = file($fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $emails_par_domaine = [];
            foreach ($emails as $email) {
                $parts=explode('@',$email);
                if(count($parts)==2){
                    $domaine = $parts[1];
                    $emails_par_domaine[$domaine][] = $email;
                }   
            }

            foreach ($emails_par_domaine as $domaine => $Liste){
                file_put_contents("$domaine.txt",implode("\n", $Liste));
            }
            

            echo "<h1 class='text-4xl font-bold text-gray-800 text-center mb-8'>Emails classés par domaine</h1>";
            foreach ($emails_par_domaine as $domaine => $Liste){
                echo "<table class='content-table mx-auto px-4 w-full max-w-4xl text-left'>";
                echo "<thead class='bg-indigo-500'><tr><th>Emails avec le domaine : @". $domaine . "</th></tr></thead>";
                echo "<tbody>";
                
                foreach($Liste as $email){
                    echo "<tr>";
                    echo "<td>".$email."</td>";
                    echo "</tr>";
                }
                
                echo "</tbody>";
                echo "</table>";
            }
            

            ?>

    <a href="indexx.php" class="bg-gray-900 hover:bg-gray-700 text-white px-4 py-2 rounded shadow inline-flex items-center justify-center">
        <button class="flex items-center justify-center">←</button>
    </a>

    </div>

    </body>
</html>
