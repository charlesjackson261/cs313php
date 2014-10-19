<?php

// protect internal pages from being viewed, this gains access to those pages.
define('CS313', true);

require_once('../lib/config.php');

if ($debug)
{
    echo '<pre>' . print_r($db, true) . '</pre>';
}

// get a list of tables
$statement = $db->query("show tables;");
$rows = $statement->fetchAll(PDO::FETCH_ASSOC); 

$data =& $rows;
$selectName = 'tablename';

?>
<form method="post">
    <h2>Table Browser</h2>
    Please select a table to display:

    <?php
include('../views/displaySelect.php');

    ?>
    <input type="submit" name="submit" value="Display Table Contents">
</form>

<?php

if (isset($_POST['tablename']))
{
?>
<h2>Table Contents</h2>
<?php

    $statement = $db->query("SELECT * FROM " . $_POST['tablename']);
    $rows = $statement->fetchAll(PDO::FETCH_ASSOC); 
    $data =& $rows;
    include('../views/displayTable.php');
}


if ($debug)
{
    // echo '<pre>' . print_r($rows, true) . '</pre>';
}

