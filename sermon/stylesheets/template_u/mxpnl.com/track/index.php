<?php
/**
 * Sample POST
 * name:    ada
 * email:   asd@asd.com
 * message: asdasd asdakjh a's d'as
 * a sdajsdl asf
 * a falsjf
 * ask alskfj
 * a;fk lskjd
 * asg
 * send:
 * updated: 20131212
 *
 * Sample Response
 */
//echo "thanks";


//*********************************************************************************************\\
//*********************************************************************************************\\
//**                                                                                          **|
//**                                                                                          **|
//**             Functions in Procedural Programming. FUCK OOP!!! Yeah!!!                     **|
//**                                                                                          **|
//**                                                                                          **|
//*********************************************************************************************//
//*********************************************************************************************//
//

/**
 * If file does not exist create it with default value
 * @param $myFile
 */
function checkORCreate($myFile) {
    //If file does not exist create it with default value
    if (!file_exists($myFile)) {
        $fh = fopen($myFile, 'w') or die("var message='Can't create file $myFile;");
        $stringData = "<?php ".PHP_EOL."die(\"No Direct Access\");".PHP_EOL."".PHP_EOL."";
        fwrite($fh, $stringData);
        fclose($fh);
    };
}

/**
 * Append all POST Values info to file
 * @param $myFile
 */
function appendContact($myFile, $errors = "@") {
    //If file does not exist create it with default value
    checkORCreate($myFile);


    $fh = fopen($myFile, 'a') or die("var message='Can't open file $myFile;");
    $stringData = "".PHP_EOL."- - - - - - - - - - - - - - - - - " . date('Y-m-d H:i:s') . ";".PHP_EOL."";
    foreach ($_POST as $key => $value) {
        $stringData .= "$key: $value;".PHP_EOL."";
    }
    if ($errors!="@") {
        $stringData .= "Some errors found oh-oh!\r";
        foreach ($errors as $key => $values) {
            if (is_array($values)) {
                $stringData .= "$key: ==>".PHP_EOL."";
                foreach($values as $value) {
                    $stringData .= "$key: $value;".PHP_EOL."";
                }
            } else if (!is_array($values)) {
                $stringData .= "$key: $value;".PHP_EOL."";
            }
        }
    }
    fwrite($fh, $stringData);
    //close file
    fclose($fh);
}

/**
 * Write ALL POSTs GETs and SERVERS to TRACKING INTRUDERS / ATTACKS
 * @param $myFile
 */
function appendTracking($myFile) {
    //If file does not exist create it with default value
    checkORCreate($myFile);
    /**
     * TRACKING INTRUDERS / ATTACKS
     */
    $fh = fopen($myFile, 'a') or die("var message='Can't open file';");
    $stringData = "".PHP_EOL."- - - - - - - - - - - - - - - - - Tracked on: " . date('Y-m-d H:i:s') . ";".PHP_EOL."";
    $stringData .= "- - - _GET's: ".PHP_EOL."";
    foreach ($_GET as $key => $value) {
        $stringData .= "$key: $value;".PHP_EOL."";
    }
    $stringData .= "- - - _POST's: ".PHP_EOL."";
    foreach ($_POST as $key => $value) {
        $stringData .= "$key: $value;".PHP_EOL."";
    }
    //Not Working for now
    //$stringData .= "- - - _REFERRER: ".PHP_EOL."";
    //$stringData .= get_search_query(). "".PHP_EOL."";
    $stringData .= "- - - _SERVER's: ".PHP_EOL."";
    foreach ($_SERVER as $key => $value) {
        $stringData .= "$key: $value;".PHP_EOL."";
    }

    fwrite($fh, $stringData);
    fclose($fh);

}
/**
 * Text me and Email me to monitor the reservations
 * @param $myCell
 * @param $myEmail
 */
function textAndEmailMe($myCell, $myEmail) {
    /**
     * Email me to monitor the reservations
     **/
    /**
     * Text Me  - SMMS
     */
    $to = $myCell;
    $subject = 'Contact';
    $headers = 'From: "ACC Website" <accdoc@alohachristianchurch.org>' . "".PHP_EOL."" .
        'Reply-To: "'
        . $_POST['name'] . '"<' . $_POST['email'] . ">".PHP_EOL."" .
        'X-Mailer: PHP/' . phpversion();

    //send email to phone or email
    // The message
    $message = "".PHP_EOL."" .
        "Email:" . $_POST['email'] . "".PHP_EOL."" .
        "Person:" .  $_POST['name'] . "".PHP_EOL."" .
        "Request:" . $_POST['message'];

    // In case any of our lines are larger than 70 characters, we should use wordwrap()
    $message = wordwrap($message, 70, "".PHP_EOL."");


    $onTextingMe = @mail($to, $subject, $message, $headers);
    //log error
    $returno = array();
    if ($onTextingMe===false) {
        array_push($returno, array("TEXT ME:" => "Error in Texting me"));
        array_push($returno, array("TEXT ME:" => "--------Start >>-----------"));
        array_push($returno, error_get_last());
        array_push($returno, array("TEXT ME:" => "--------<< end  -----------"));
    } else if ($onTextingMe===true) {
        array_push($returno, array("TEXT ME:" => "Success!"));
    }


    // Send
    /**
     * Me - EMail   - Tracking
     */
    $to = $myEmail;
    $onEmailngMe = @mail($to, $subject, $message, $headers);

    if ($onEmailngMe===false) {
        array_push($returno, array("EMAIL ME:" => "Error in emailing me"));
        array_push($returno, array("EMAIL ME:" => "--------Start >>-----------"));
        array_push($returno, error_get_last());
        array_push($returno, array("EMAIL ME:" => "--------<< end  -----------"));
    } else if ($onEmailngMe===true) {
        array_push($returno, array("EMAIL ME:" => "Success!"));
    }
    return $returno;
}


# from
# http://techtalk.virendrachandak.com/get-search-query-string-from-search-engines-using-php/
/**
 * Function returns the query string
 * (q or query parameters) from the referrer
 * /  <-- not working  eeor in line 93
 * function get_search_query()
 * {
 * $ref_keywords = '';
 *
 * // Get the referrer to the page
 * $referrer = $_SERVER['HTTP_REFERER'];
 * if (!empty($referrer))
 * {
 * //Parse the referrer URL
 * $parts_url = parse_url($referrer);
 *
 * // Check if a query string exists
 * $query = isset($parts_url['query']) ? $parts_url['query'] : '');
 * if($query)
 * {
 * // Convert the query string into array
 * parse_str($query, $parts_query);
 * // Check if the parameters 'q' or 'query' exists, and if exists that is our search query terms.
 * $ref_keywords = isset($parts_query['q']) ? $parts_query['q'] : (isset($parts_query['query']) ? $parts_query['query'] : '' ));
 * }
 * }
 * return $ref_keywords;
 * }
 *
 * # from
 * # http://www.linuxjournal.com/article/9585?page=0,3
 * /**
 * Validate an email address.
 * Provide email address (raw input)
 * Returns true if the email address has the email
 * address format and the domain exists.
 */
function validEmail($email)
{
    $isValid = true;
    $atIndex = strrpos($email, "@");
    if (is_bool($atIndex) && !$atIndex) {
        $isValid = false;
    } else {
        $domain = substr($email, $atIndex + 1);
        $local = substr($email, 0, $atIndex);
        $localLen = strlen($local);
        $domainLen = strlen($domain);
        if ($localLen < 1 || $localLen > 64) {
            // local part length exceeded
            $isValid = false;
        } else if ($domainLen < 1 || $domainLen > 255) {
            // domain part length exceeded
            $isValid = false;
        } else if ($local[0] == '.' || $local[$localLen - 1] == '.') {
            // local part starts or ends with '.'
            $isValid = false;
        } else if (preg_match('/\\.\\./', $local)) {
            // local part has two consecutive dots
            $isValid = false;
        } else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
            // character not valid in domain part
            $isValid = false;
        } else if (preg_match('/\\.\\./', $domain)) {
            // domain part has two consecutive dots
            $isValid = false;
        } else if
        (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
            str_replace("\\\\", "", $local))
        ) {
            // character not valid in local part unless
            // local part is quoted
            if (!preg_match('/^"(\\\\"|[^"])+"$/',
                str_replace("\\\\", "", $local))
            ) {
                $isValid = false;
            }
        }
        if (function_exists('checkdnsrr')) {
            if ($isValid && !(checkdnsrr($domain, "MX") || checkdnsrr($domain, "A"))) {
                // domain not found in DNS
                $isValid = false;
            }

        }
    }
    return $isValid;
} //end valid email



//*********************************************************************************************\\
//*********************************************************************************************\\
//**                                                                                          **|
//**                                                                                          **|
//**                                    Execute Main Proc                                     **|
//**                                                                                          **|
//**                                                                                          **|
//*********************************************************************************************//
//*********************************************************************************************//


if (isset($_POST) &&
    (
        isset($_POST['name']) &&
        isset($_POST['email']) &&
        isset($_POST['message']) &&
        isset($_POST['send'])

    ) &&
    !is_null($_POST['name']) &&
    !is_null($_POST['email']) &&
    !is_null($_POST['message']) &&
    validEmail($_POST['email'])
) {

    //text me and email me this message Only for TESTING Reasons TODO Remove if it goes live
    $errors = textAndEmailMe("5034754214@txt.att.net", "jesusalc@gmail.com");

    //text and email the church Guys
    $errors = textAndEmailMe("5034754214@txt.att.net", "accdoc@alohachristianchurch.org");

    //Store in a file the contact information
    //+ Append all Contact info to contact file
    appendContact("contacts.php", $errors);


    /**
     * Give a positive response
     **/
    echo "thanks";
    exit(0);


} else {

    /**
     * TRACKING INTRUDERS / ATTACKS
     */
    appendTracking("tracking.php");


    echo "var message='false - Validation not passed';";
    exit;
}

