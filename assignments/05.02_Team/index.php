<?php
// connect to the database
try
{ 
    $user = "root";
    $password = "";
    $db = new PDO("mysql:host=127.0.0.1;dbname=cs313", $user, $password);
}
catch (PDOException $ex)
{
    echo "Error!: " . $ex->getMessage();
    die();
}

// get input
if (isset($_POST['submit']))
{
    // someone submitted the form
    $sql = "INSERT INTO Scriptures (book,chapter,verse,content) VALUES (:book,:chapter,:verse,:content)";
    $insert_stmt = $db->prepare($sql);
    $insert_stmt->execute(array(':book'=>$_POST['book'], ':chapter'=>$_POST['chapter'],':verse'=>$_POST['verse'], ':content'=>$_POST['content'] ));

    $new_script_id = $db->lastInsertId();

    // completed adding the scripture
    unset($_POST['book']);
    unset($_POST['chapter']);
    unset($_POST['verse']);
    unset($_POST['content']);
    unset($_POST['submit']);

    // add the new topic
    if (!isset($_POST['new_checkbox']))
        unset($_POST['new_add']);
    else 
    {
        // add the new topic to the topics table
        $sql = "INSERT INTO topics (name) VALUES (:name)";
        $insert_stmt = $db->prepare($sql);
        $insert_stmt->execute(array(':name'=>$_POST['new_add']));

        // gets the id of the topic id
        $new_topic_id = $db->lastInsertId();

        // add our new topic to the insert list
        $_POST[$new_topic_id] = $_POST['new_add'];

        // remove the extra items from the array
        unset($_POST['new_checkbox']);
        unset($_POST['new_add']);

    }

    // add the topics now
    foreach($_POST as $id=>$name)
    {
        // someone submitted the form
        $sql = "INSERT INTO lkscripttopic (scripture_id, topic_id) VALUES (:scripture_id,:topic_id)";
        $insert_stmt = $db->prepare($sql);
        $insert_stmt->execute(array(':scripture_id'=>$new_script_id, ':topic_id'=>$id));

    }

}

// fetch the list of scriptures in an array
$statement = $db->query("SELECT * FROM Scriptures");
$scriptures = $statement->fetchAll(PDO::FETCH_ASSOC);

foreach ($scriptures as $idx=>$scripture)
{
    $statement = $db->query(
        "
        SELECT t.name 
        FROM lkscripttopic as lks
        join topics as t on t.id = lks.topic_id
        where lks.scripture_id = " . $idx
    );
    $scriptures[$idx]['topics'] = $statement->fetchAll(PDO::FETCH_ASSOC);
}

// fetch the list of topics in an array
$statement = $db->query("SELECT * FROM topics");
$topics = $statement->fetchAll(PDO::FETCH_ASSOC);


?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>5.02 Team Readiness Activity - Scriptures</title>
    </head>
    <body>


        <h1>Scripture Resources</h1>

        <?php

if (count($scriptures) > 0)
{
    foreach ($scriptures as $scripture)
    {
        echo '<p><b>' . $scripture['book'] . ' ' . $scripture['chapter'] .':'. $scripture['verse'] . '</b> - "' . $scripture['content'] . '"';
        foreach($scripture['topics'] as $idx=>$topic)
        {
            if ($idx==0)
            {
                echo ' <strong>Topics:</strong> ';    
            }
            echo $topic["name"] . ' ';
        }
            echo '</p>';
    }
}



        ?>

        <div id="insertNew">
            <h3>Add Scripture</h3>
            <form method="post">
                <input type="text" required name="book" id="book" placeholder="Book" value="<?php if (isset($_POST['book'])) echo $_POST['book'] ?>"><br>
                <input type="number" required name="chapter" id="chapter" placeholder="Chapter" value="<?php if (isset($_POST['chapter'])) echo $_POST['chapter'] ?>"><br>
                <input type="number" required name="verse" id="verse" placeholder="Verse" value="<?php if (isset($_POST['verse'])) echo $_POST['verse'] ?>"><br>
                <textarea name="content" required id="content" placeholder="content"><?php if (isset($_POST['content'])) echo $_POST['content'] ?></textarea><br>

                <h3>Topics</h3>
                <?php
            if (count($topics) > 0)
        {
            foreach ($topics as $topic)
            {
                ?>

                <input type="checkbox" name="<?php echo $topic['id']; ?>" value="<?php echo $topic['name']; ?>" ><?php echo $topic['name']; ?><br>
                <?php
            }
        }
                ?>
                <input type="checkbox" name="new_checkbox" value="new" ><input type="text" name="new_add" id="new_add" placeholder="other"><br>
                <input type="submit" name="submit" value="Add Scripture">

            </form>
        </div>


    </body>
</html>