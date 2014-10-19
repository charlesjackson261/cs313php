<?php
// prevent direct access
defined('CS313') or die("Sorry, you are not allowed to directly access this page.<br /> Please press the back button in your browser.");

if (isset($data) && is_array($data))
{
    echo '<select name="'.$selectName.'">';

    foreach($data as $index=>$dataRow)
    {

        foreach($dataRow as $colName=>$colVal)
        {
            echo '<option value="' . $colVal .'">' . $colVal . '</option>';
        }
    }

    echo '</select>';


} else {
    // no data to display
?>
<table>
    <tr><td>Sorry, No Data to display.</td></tr>
</table>

<?php
}