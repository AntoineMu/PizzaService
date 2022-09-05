<?php
    header ("Content-type: text/html");
    $title="Test";
    error_reporting(E_ALL);
?>

<?php
$pizza = "Salami";
$address = "Hallo";
$sql = sprintf("insert into ordering (address) values %s %s", $pizza, "HAllo");
echo <<<EOT
    <!DOCTYPE html>
    <html lang="de">
    <head>
        <meta charset="UTF-8" />
        <title>$title</title>
    </head>
    
    $sql

EOT;
?>
</html>