<?php
// connect to the database
try
{ 
    $user = "php";
    $password = "1234";
    $db = new PDO("mysql:host=127.0.0.1;dbname=cs313", $user, $password);
}
catch (PDOException $ex)
{
    echo "Error!: " . $ex->getMessage();
    die();
}

// fetch the list of scriptures in an array
$statement = $db->query("SELECT * FROM Scriptures");
$rows = $statement->fetchAll(PDO::FETCH_ASSOC);

?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>4.02 Team Readiness Activity - Scriptures</title>
    </head>
    <body>
        <h1>Scripture Resources</h1>

        <?php

if ( isset($_GET['debug']) )
{
    echo '$rows: <pre>';
    print_r($rows);
    echo '</pre>';
}

if (count($rows) > 0)
{
    foreach ($rows as $row)
    {
        echo '<p><b>' . $row['book'] . ' ' . $row['chapter'] .':'. $row['verse'] . '</b> - "' . $row['content'] . '"</p>';
    }
}
        ?>

    </body>
</html>