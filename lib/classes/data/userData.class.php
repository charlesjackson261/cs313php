<?php
// template engine that loads and then populates the data from the file.

// prevent direct access
defined('CS313') or die("Sorry, you are not allowed to directly access this page.<br /> Please press the back button in your browser.");

// These constants may be changed without breaking existing hashes.
define("PBKDF2_HASH_ALGORITHM", "sha256");
define("PBKDF2_ITERATIONS", 1000);
define("PBKDF2_SALT_BYTES", 24);
define("PBKDF2_HASH_BYTES", 24);

define("HASH_SECTIONS", 4);
define("HASH_ALGORITHM_INDEX", 0);
define("HASH_ITERATION_INDEX", 1);
define("HASH_SALT_INDEX", 2);
define("HASH_PBKDF2_INDEX", 3);

class User
{
    // TODO: Add in the code to create, retrive, update, delete entries from this table
    protected $db;

    public function __construct($db)
    {
        $this->db =& $db;
    }

    public function login($un, $up)
    {
        $jr = new JsonResponse("UserResponse", 1);

        try {

            // Get the SALT for this username
            if (!$userInfo = $this->db->query("SELECT UserID, UserName, UserPassword, UserTypeID, CSPRNG FROM `user` WHERE UserName = \"$un\" ")->fetch()) {
                $jr->setResponseCode(0);
                $jr->addMsg('Username or Password incorrect. (code 38)');
                return $jr;
            }

            // echo '<pre>'.print_r($userInfo, true).'</pre>';

            // Is SALT empty (possibly two verification methods)
            if ($userInfo['CSPRNG'] == 0) {

                // Check the password using a MD5
                if ($userInfo['UserPassword'] != md5($up)) {
                    $jr->setResponseCode(0);
                    $jr->addMsg('Username or Password incorrect. (code 50)');
                    return $jr;
                }

                // Now that we are validated, generate a new SALT and set the users password.
                // TODO: decide if we need to do this or just reply on the previous password
                // $this->ChangePassword($userInfo['UserID'], null, $up, $up, true);
            }
            else {

                // Check the users password using the random SALTED password
                if ($this->validate_password($up, $userInfo['UserPassword']) === false) {
                    $jr->setResponseCode(0);
                    $jr->addMsg('Username or Password incorrect. (code 63)');
                    return $jr;
                }
            }

            /*
            // check encode the password for verification.
            $sql = "SELECT * FROM user where UserName = :un and UserPassword = :up";
            $statement = $this->db->prepare($sql);
            $statement->execute(array(':un'=>$un, ':up'=>$this->create_hash($up)));
            $rows = $statement->fetchAll(PDO::FETCH_ASSOC); 
            */

            // echo '<pre>$rows: ' . print_r($rows, true) . '</pre>';
            // echo '<pre>$password: ' . print_r(array( 'raw'=>$up , 'encoded'=>$this->create_hash($up) ), true) . '</pre>';

            // remove the index values and keep the named values
            foreach($userInfo as $key=>$value)
            {
                if (is_int($key) || $key == "UserPassword")
                    unset($userInfo[$key]);
            }

            // $jr->rows = $rows;
            $jr->userinfo = $userInfo;

            return $jr;

            // return true;


        } catch (Exception $e) {
            return '<pre>Exception: '.print_r($e, true).'</pre>';
        }

    }

    public function create($un, $up, $em)
    {

        $jr = new JsonResponse("CreateUserResponse", 1);

        try {

            // required fields
            //   usertypeid = '3'
            //   UserName = ''
            //   UserPassword = ''
            //   loggedin = '0'
            //   homepage = 'dashboard'
            //   Retired = '0'
            //   CSPRNG = '0'
            
            $sql = "INSERT INTO user (usertypeid,UserName,UserPassword,email,loggedin,homepage,Retired, CSPRNG) VALUES (3,:un,:uph,:em,0,'dashboard',0,0)";
            $insert_stmt = $this->db->prepare($sql);
            $this->db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $insert_stmt->execute(array(':un'=>$un, ':uph'=>md5($up), ':em'=>$em));
            // $insert_stmt->execute(array(':un'=>$un, ':uph'=>$up, ':em'=>$em));

            $new_user_id = $this->db->lastInsertId();
            
            $jr->new_user_id = $new_user_id;
            $jr->PDO_error_info = $this->db->errorInfo();

        } catch (Exception $e) {
            $jr->setResponseCode(0);
            $jr->addMsg('<pre>Exception: '.print_r($e, true).'</pre> (code 63)');
            return $jr;
        }

        return $jr;

    }

    public function __get($data)
    {
        // this runs a simple query against the database to lookup a user.
        return $this->data['data'][$key];
    }

    public function __set($key, $value)
    {
        $this->data['data'][$key] = $value;
    }

    public function __toString()
    {
        return json_encode($this->data);
    }

    // encryption methods for the passwords

    /*
     * Password hashing with PBKDF2.
     * Author: havoc AT defuse.ca
     * www: https://defuse.ca/php-pbkdf2.htm
     */
    private function create_hash($password)
    {
        // format: algorithm:iterations:salt:hash
        $salt = base64_encode(mcrypt_create_iv(PBKDF2_SALT_BYTES, MCRYPT_DEV_URANDOM));
        return PBKDF2_HASH_ALGORITHM . ":" . PBKDF2_ITERATIONS . ":" .  $salt . ":" .
            base64_encode($this->pbkdf2(
                PBKDF2_HASH_ALGORITHM,
                $password,
                $salt,
                PBKDF2_ITERATIONS,
                PBKDF2_HASH_BYTES,
                true
            ));
    }

    /*
     * PBKDF2 key derivation function as defined by RSA's PKCS #5: https://www.ietf.org/rfc/rfc2898.txt
     * $algorithm - The hash algorithm to use. Recommended: SHA256
     * $password - The password.
     * $salt - A salt that is unique to the password.
     * $count - Iteration count. Higher is better, but slower. Recommended: At least 1000.
     * $key_length - The length of the derived key in bytes.
     * $raw_output - If true, the key is returned in raw binary format. Hex encoded otherwise.
     * Returns: A $key_length-byte key derived from the password and salt.
     *
     * Test vectors can be found here: https://www.ietf.org/rfc/rfc6070.txt
     *
     * This implementation of PBKDF2 was originally created by https://defuse.ca
     * With improvements by http://www.variations-of-shadow.com
     */
    public function pbkdf2($algorithm, $password, $salt, $count, $key_length, $raw_output = false)
    {
        $algorithm = strtolower($algorithm);
        if(!in_array($algorithm, hash_algos(), true))
            die('PBKDF2 ERROR: Invalid hash algorithm.');
        if($count <= 0 || $key_length <= 0)
            die('PBKDF2 ERROR: Invalid parameters.');

        $hash_length = strlen(hash($algorithm, "", true));
        $block_count = ceil($key_length / $hash_length);

        $output = "";
        for($i = 1; $i <= $block_count; $i++) {
            // $i encoded as 4 bytes, big endian.
            $last = $salt . pack("N", $i);
            // first iteration
            $last = $xorsum = hash_hmac($algorithm, $last, $password, true);
            // perform the other $count - 1 iterations
            for ($j = 1; $j < $count; $j++) {
                $xorsum ^= ($last = hash_hmac($algorithm, $last, $password, true));
            }
            $output .= $xorsum;
        }

        if($raw_output)
            return substr($output, 0, $key_length);
        else
            return bin2hex(substr($output, 0, $key_length));
    }

    public function validate_password($password, $good_hash)
    {
        $params = explode(":", $good_hash);
        if(count($params) < HASH_SECTIONS)
            return false;
        $pbkdf2 = base64_decode($params[HASH_PBKDF2_INDEX]);
        return $this->slow_equals(
            $pbkdf2,
            $this->pbkdf2(
                $params[HASH_ALGORITHM_INDEX],
                $password,
                $params[HASH_SALT_INDEX],
                (int)$params[HASH_ITERATION_INDEX],
                strlen($pbkdf2),
                true
            )
        );
    }

    // Compares two strings $a and $b in length-constant time.
    public function slow_equals($a, $b)
    {
        $diff = strlen($a) ^ strlen($b);
        for($i = 0; $i < strlen($a) && $i < strlen($b); $i++)
        {
            $diff |= ord($a[$i]) ^ ord($b[$i]);
        }
        return $diff === 0;
    }


}