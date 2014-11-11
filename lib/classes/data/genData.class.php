<?php
// template engine that loads and then populates the data from the file.

// prevent direct access
defined('CS313') or die("Sorry, you are not allowed to directly access this page.<br /> Please press the back button in your browser.");

require_once('lib/classes/response.class.php');

class GenData
{
    // TODO: Add in the code to create, retrive, update, delete entries from this table
    protected $db, $response;

    /*
     * This creates the default object and sets the db connection up
     */
    public function __construct($db)
    {
        $this->db =& $db;
        $this->response = new Response("GenData", 1);
    }

    public function __call($method, $args)
    {
        $method = ucfirst($method);

        // catch any additional calls that are made to this class that has not been defined
        $this->response->setResponseCode(-1);
        $this->response->addMsg("Function '$method' has not been implemented");
        $this->args = $args;
        return $this->response;

    }

    public function create($args)
    {
        // check for requried data
        // the user should supply a key:pair value for the insert into the database.
        // check for the table
        if (!isset($args['table']))
        {
            $this->response->setResponseCode(0);
            $this->response->addMsg('failure a table has not been specified');
            return $this->response;

            // check for a data source
        } else if (!isset($args['data']) || !is_array($args['data']) ) {

            $this->response->setResponseCode(0);
            $this->response->addMsg('Data key:value pair array has not been supplied');
            return $this->response;
        }

        // echo '<pre>$args: '.print_r($args, true).'</pre>';

        // create generic insert statement

        // get some information from the data supplied by the user
        $cols = '';
        $vals = '';

        $first = true;

        foreach($args['data'] as $col=>$val)
        {
            if ($first)
            {
                $cols = $col;
                $vals = ':'.$col;

                $first = false;
            } else {
                $cols .= ', '.$col;
                $vals .= ', '.':'.$col;
            }
        }

        try {

            $sql = "INSERT INTO ".$args['table']." ($cols) VALUES ($vals)";
            $this->response->sql = $sql;
            $insert_stmt = $this->db->prepare($sql);
            $this->db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            // ececute the call using the current data array.
            $insert_stmt->execute($args['data']);

            $this->response->insert_id = $this->db->lastInsertId();

        } catch (Exception $e) {
            $this->response->setResponseCode(0);
            $this->response->addMsg('<pre>Exception: '.print_r($e, true).'</pre> (code 63)');
            return $this->response;
        }

        return $this->response;
    }

    public function read($args)
    {
        return "This function has not been implemented";
    }

    public function update($args)
    {
        return "This function has not been implemented";
    }

    public function delete($args)
    {
        return "This function has not been implemented";
    }

//    public function __get($key)
//    {
//        // this runs a simple query against the database to lookup a user.
//        if (isset($this->data['data'][$key]))
//            return $this->data['data'][$key];
//        return false;
//    }
//
//    public function __set($key, $value)
//    {
//        // echo '<pre>$key: '.$key.'</pre>';
//        // echo '<pre>'.print_r(get_class_methods('GenData'), true).'</pre>';
//        if (!in_array($key, get_class_methods('GenData') ) && $key != 'args' )
//            $this->data['data'][$key] = $value;
//        return false;
//    }

    public function __toString()
    {
        return $this->data;
    }

}