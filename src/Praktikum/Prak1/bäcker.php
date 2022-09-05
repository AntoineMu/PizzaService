<?php
    header ("Content-type: text/html");
    $title="Bäcker";
    error_reporting(E_ALL);
?>

<?php
echo <<<EOT
    <!DOCTYPE html>
    <html lang="de">
    <head>
        <meta charset="UTF-8" />
        <title>$title</title>
    </head>

    <body>
        <h3>Testadresse 1234 - Pizza Salami</h3>
        <form action="https://echo.fbi.h-da.de/" method="post">
            <label><input type="radio" name="status" value="1" checked>bestellt</label>
            <br><label><input type="radio" name="status" value="2">im ofen</label>
            <br><label><input type="radio" name="status" value="3">fertig</label>
            <br><input type="submit" name="submit" value="absenden">
        </form> 

    </body>

EOT;
?>
</html>

