<?php
    header ("Content-type: text/html");
    $title="Kunde";
    error_reporting(E_ALL);

echo <<<EOT
    <!DOCTYPE html>
    <html lang="de">
    <head>
        <meta charset="UTF-8" />
        <title>$title</title>
    </head>

    <body>
        <article><h3>Salami: bestellt</h3></article>
        <h3>Magherita: fertig</h3>
    </body>

EOT;
?>
</html>

