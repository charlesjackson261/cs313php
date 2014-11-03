<?php
// prevent direct access
defined('CS313') or die("Sorry, you are not allowed to directly access this page.<br /> Please press the back button in your browser.");

global $data;

// echo '<pre>Data: '.print_r($data, true).'</pre>';

if (isset($data) && is_array($data))
{
    // generate a table for the data
    $firstRun = true;

    echo '<table class="table table-hover" border="1px" data-toggle="table">';

    foreach($data as $index=>$dataRow)
    {

        if ($firstRun)
        {
            // generate a set of headers
            echo '<tr>';

            foreach($dataRow as $colName=>$colVal)
            {
                echo '<th>' . $colName . '</th>';
            }
            echo '</tr>';
            $firstRun = false;
        }

        echo '<tr>';

        foreach($dataRow as $colName=>$colVal)
        {
            echo '<td>' . $colVal . '</td>';
        }
        echo '</tr>';
    }

    echo '</table>';


} else {
    // no data to display
?>
<table>
    <tr><td>Sorry, No Data to display.</td></tr>
</table>

<?php
}