<?php
    header ("Content-type: text/html");
    $title="Fahrer";
    error_reporting(E_ALL);

echo <<<EOT
    <!DOCTYPE html>
    <html lang="de">
    <head>
        <meta charset="UTF-8" />
        <title>$title</title>
    </head>

    <body>
        <h4>Bestellung 14</h4>
        <article>
        <h2>Test 1234 name adresse</h2>
        <br>Salami, Hawaii
        <br><b>Summe: 9,50€</b>
        <form action="https://echo.fbi.h-da.de/" method="post">
            <br><label><input type="radio" name="status" value="unterwegs">unterwegs</label>
            <br><label><input type="radio" name="status" value="ausgeliefert">ausgeliefert</label>
            <br><input type="submit" name="submit" value="absenden">
        </form>
        </article>
    </body>

EOT;
?>
</html>