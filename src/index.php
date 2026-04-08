<?php
header("Content-type: text/html");

$Prak="";
$PrakInfo="";
if (is_dir("Praktikum")) {
    $PrakInfo="<li>Work on your lab exercises in the folder <b>Praktikum</b>.</li>";
    $Prak = "<a class=\"flex-item flex-item--warning\" href=\"./Praktikum\">Praktikum</a>";
}



echo <<<HTML
<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>EWA Repository</title>
        <link rel="stylesheet" href="index.css">
    </head>
    <body>
        <main class="main-container">
            <div class="main-wrapper">
                <header>
                    <h1 class="h1">EWA Repository</h1>
                </header>
                <hr>
                <ul>
                    <li>This is the content of the file 'index.php' in the folder <b>'src'</b>!</li>
                </ul>
                <h2>Navigation</h2>
                <div class="flex-container">
                    $Prak
                </div>
            </div>
        </main>
    </body>
</html>
HTML;