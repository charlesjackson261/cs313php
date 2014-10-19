<?php

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