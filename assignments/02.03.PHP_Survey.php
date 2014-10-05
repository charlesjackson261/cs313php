<?php

// this is the business logic for the application

// start the session
session_start();

// load up a survey from a file
// $survey = create_survey();

$survey = null;
$results = false;
$debug = false;

// process results
if (isset($_POST['submit']))
{
    $results = true;

    // we should have some data to increment
    // pull the survey from the post
    if (isset($_POST['data']))
    {
        $survey = json_decode($_POST['data']);

        // clean post of data and submit
        unset($_POST['submit']);
        unset($_POST['data']);

        // increment the results
        if (count($_POST) > 0)
        {
            foreach ($_POST as $ques=>$option)
            {
                // DEBUG
                // echo "$ques=>$option=>" . $survey->questions[$ques]->results[$option] . "<br>";
                $survey->questions[$ques]->results[$option]++;
            }
            save_survey($survey);
            $_SESSION[$survey->file] = true;
        }

    }

} else {
    $survey = load_survey('awesome_survey.svy');
}

// check to see if session submitted
// check survey title
$file = null;
if (is_object($survey)) { $file = $survey->file; }
else if (is_array($survey)) { $file = $survey['file']; }

if(isset($_SESSION[$file]))
{
    // the user has already submitted the survey
    $results = true;
}


?>

<!DOCTYPE html>
<html>
    <head>
        <title>02.03 PHP Survey</title>
    </head>
    <body>
        <?php 
if ($results)
    echo gen_result_html($survey);
else 
    echo gen_survey_html($survey);

        ?>
        <pre>
        <?php 
if ($debug)
{
    print_r($_POST);
    print_r($survey);
}
        ?>
        </pre>
    </body>
</html>


<?php

// functions
function create_survey() {
    // generate a survey object
    $survey = array(
        'title' => 'Awesome Survey',
        'file' => 'awesome_survey.svy',
        'questions' => null
    );

    $survey['questions'] = array();

    // add a question to the survey
    $question = array(
        'question'=>'What country do you live in?',
        'type'=>'radio',
        'options'=>array( 'Canada', 'United States', 'Mexico', 'England', 'France' )
    );
    array_push($survey['questions'], $question);

    // add a question to the survey
    $question = array(
        'question'=>'Select your age?',
        'type'=>'radio',
        'options'=>array( '0-10', '11-20', '21-30', '31-40', '41-50', '51-60', '60+' )
    );
    array_push($survey['questions'], $question);

    // add a question to the survey
    $question = array(
        'question'=>'How do you listen to music?',
        'type'=>'check',
        'options'=>array( 'CD', 'MP3', 'OGG', 'FLAC', 'Streaming', 'Radio' )
    );
    array_push($survey['questions'], $question);

    // add a question to the survey
    $question = array(
        'question'=>'What side do you butter your bread?',
        'type'=>'radio',
        'options'=>array( 'none', 'top', 'bottom' )
    );
    array_push($survey['questions'], $question);

    // Add empty results to the data structure
    foreach ($survey['questions'] as $index=>$question)
    {
        if (isset($question['options']))
        {
            $survey['questions'][$index]['results'] = array();
            foreach ($question['options'] as $option)
            {
                array_push($survey['questions'][$index]['results'], 0);
            }
        }
    }

    return $survey;
}

function save_survey($survey) {
    if(is_array($survey) || is_object($survey))
    {
        $survey = (array) $survey;

        try {
            // encode the survey for writing to a file
            $encoded_survey = json_encode($survey);

            // write the survey to a file
            $surveyFile = fopen($survey['file'], 'w');
            fwrite($surveyFile, $encoded_survey);
            fclose($surveyFile);

        } catch (Exception $e) {
            return false;
        }


    }
    else {
        return false;
    }

    return true;
}

function load_survey($file) {

    $survey = null;

    try {
        // read the survey to a file
        $surveyFile = fopen($file, 'r');
        $survey = json_decode( fread($surveyFile, filesize($file)) );
        fclose($surveyFile);

    } catch (Exception $e) {
        return false;
    }

    return $survey;

}

function gen_survey_html($survey) {
    $html = '';

    $html .= '<form method="post">';
    $html .= '<input type="hidden" name="data" value=\''.json_encode($survey).'\'>';
    $html .= '<h1>' . $survey->title.'</h1>';

    foreach($survey->questions as $index=>$ques)
    {
        $html .= '<h2>'.$ques->question.'</h2>';

        if ($ques->type == 'radio')
        {
            foreach($ques->options as $optn_idx=>$option)
            {
                $html .= '<input type="radio" name="'.$index.'" value="'.$optn_idx.'">'.$option.'<br>';
            }
        }

    }

    $html .= '<br><input type="submit" name="submit" value="Submit">';
    $html .= '<input type="submit" name="submit" value="View Results">';
    $html .= '</form>';

    return $html;
}

function gen_result_html($survey) {

    $survey = (array) $survey;

    $html = '';

    $html .= '<h1>' . $survey['title'].' Results</h1>';

    foreach($survey['questions'] as $index=>$ques)
    {
        $html .= '<h2>'.$ques->question.'</h2>';

        foreach($ques->options as $optn_idx=>$option)
        {
            $html .= $ques->results[$optn_idx].' - '.$option.'<br>';
        }
    }

    $html .= '<a href="">Submit Again</a>';

    return $html;
}
