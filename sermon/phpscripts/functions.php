<?php

#functions

# I am dumping all functions away from index so I better understand it


/**
 * Read $_GET vars
 * @param $que
 * @return mixed
 */
function leeGET($que)
{
    if (isset($_GET[$que])) {
        if (($_GET[$que]) == '') {
            return "$que";   //return the strings 'year' 'month' 'day' 'december' '2013'
        } else {
            return $_GET[$que];

        }
    }
}

/**
 * Check variable values that are same when transformed to intval()
 * php should compare as equal in this case.
 * @param $que
 * @return bool
 */
function valido($que)
{
    return (($que) != '' && intval($que) == $que);
}

/**
 * Get all the available years dirs
 * @return array
 */
function getYears()
{
    /** This array will hold all the year available */
    $years = array();

                                  /** @BUG-fix is_dir() funciton  needs to have the
                                     whole path to work properly and resolve file
                                     permisions in the file system. That is why
                                     I use realpath()
                                   */

    $path = realpath('../Sermons');

    //DEBUG echo $path.'<br />'.PHP_EOL;
//scan dir and provide a list of the lates sermons unless link provided
    foreach (scandir($path) as $entryname) {
        //DEBUG echo $path.DIRECTORY_SEPARATOR.($entryname).'<br />'.PHP_EOL;
        if (is_dir($path.DIRECTORY_SEPARATOR.$entryname)===true) {
            //DEBUG echo " is dir ".'<br />'.PHP_EOL;
            if (substr($entryname, 0, 7) == 'Sermons') { //if it starts with 'Sermons'
                $years[] = substr($entryname, 7); //add it to the array
            }
        }
    }
    //DEBUG echo print_r($years).'<br />'.PHP_EOL;;
    return $years;
}
