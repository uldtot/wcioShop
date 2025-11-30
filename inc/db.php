<?php
if (!defined("ABSPATH")) {
    die("No ABSPATH definded");
}

if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) { //ob start to start fetching data including gzip
    ob_start("ob_gzhandler");

try {


    // Load ini file if present.
    $iniConfigFile = ABSPATH . "/../private.ini";
    if (file_exists($iniConfigFile)) {

        $iniArray = parse_ini_file($iniConfigFile);

        $dbname = $iniArray["dbname"];
        $dbport = $iniArray["dbport"];
        $dbhost = $iniArray["dbhost"];
        $dbuser = $iniArray["dbuser"];
        $dbpass = $iniArray["dbpass"];
        $dbprefix = $iniArray["dbprefix"];
    } else {

        // Or define your db connection here
        $dbname = "";
        $dbport = "";
        $dbhost = "";
        $dbuser = "";
        $dbpass = "";
    }

    $dbh = new PDO("mysql:dbname=$dbname;port=$dbport;host=$dbhost", "$dbuser", "$dbpass", array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

// SRC: http://ca3.php.net/magic_quotes
//Prevent Magic Quotes from affecting scripts, regardless of server settings
//Make sure when reading file data,
//PHP doesn't "magically" mangle backslashes!
//set_magic_quotes_runtime(FALSE);
if (function_exists("get_magic_quotes_gpc")) {
    /*
    All these global variables are slash-encoded by default,
    because    magic_quotes_gpc is set by default!
    (And magic_quotes_gpc affects more than just $_GET, $_POST, and $_COOKIE)
    */
    $_SERVER = stripslashes_array($_SERVER ?? '');
    $_GET = stripslashes_array($_GET ?? '');
    $_POST = stripslashes_array($_POST ?? '');
    $_COOKIE = stripslashes_array($_COOKIE ?? '');
    $_FILES = stripslashes_array($_FILES ?? '');
    $_ENV = stripslashes_array($_ENV ?? '');
    $_REQUEST = stripslashes_array($_REQUEST ?? '');
    $HTTP_SERVER_VARS = stripslashes_array($HTTP_SERVER_VARS ?? '');
    $HTTP_GET_VARS = stripslashes_array($HTTP_GET_VARS ?? '');
    $HTTP_POST_VARS = stripslashes_array($HTTP_POST_VARS ?? '');
    $HTTP_COOKIE_VARS = stripslashes_array($HTTP_COOKIE_VARS ?? '');
    $HTTP_POST_FILES = stripslashes_array($HTTP_POST_FILES ?? '');
    $HTTP_ENV_VARS = stripslashes_array($HTTP_ENV_VARS ?? '');
    if (isset($_SESSION)) {    #These are unconfirmed (?)
        $_SESSION = stripslashes_array($_SESSION ?? '');
        $HTTP_SESSION_VARS = stripslashes_array($HTTP_SESSION_VARS ?? '');
    }
    /*
    The $GLOBALS array is also slash-encoded, but when all the above are
    changed, $GLOBALS is updated to reflect those changes.  (Therefore
    $GLOBALS should never be modified directly).  $GLOBALS also contains
    infinite recursion, so it's dangerous...
    */
}
function stripslashes_array($data)
{
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = stripslashes_array($value);
        }
        return $data;
    } else {
        return stripslashes($data);
    }
}
