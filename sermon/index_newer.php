<?php

/**
 * dynamic Sermon script
 * Looking to automate display of sermons

   It would be much better to have this be a script that
   generates a static HTML page
   with the result of the semon list
   instead of PHP dynamic

 */

include("phpscripts/functions.php");
include("phpscripts/initial_values.php");

//Check to see if there is get in the url
if (isset($_GET)) { //TODO make or get a universal GET function
    //TODO read questions
    //year ? month ? day ?

    //?year=2013&month=12&day=13
    //Reads variables
    $year = leeGET('year');
    $month = leeGET('month');
    $day = leeGET('day');



    //look into received variables  /?year= & month=  & day =
    if (valido($year) && valido($month) && valido($day)) {    //View a specific sermon
        $action = 'specific-sermon';
    } elseif (valido($year) && valido($month)) {  //View months list sermons
        $action = 'year-month';
    } elseif (valido($year)) {  //View sermons for the year
        $action = 'all-year';
    } //else
      // the action is 'recent-sermons'
} else {
    //default actions will be taken
}

//What do to  ?

//DEBUG echo print_r($years);


//if no get provided then show "recent sermons mode"  is the last month's sermon
$html = '';
//rails new rails-bootstrap -m https://raw.github.com/RailsApps/rails-composer/master/composer.rb
//You get a Rails application with Bootstrap. Customize as you wish.

$total_sermons_by_device_base = 0;
//DEBUG
//echo 'action:'.$action.'<br />'.PHP_EOL;
//die();
if ($action == 'recent-sermons') {

    $month_count = 0;
    //read all the sermons in the month
    $recent_year = $years[sizeof($years) - 1];
    $recent_month_name = '@';
    $recent_dayth_name = '@';

    //DEBUG echo 'recent_year:'.$recent_year.'<br />'.PHP_EOL;
    $sermons_base = array();
    $sermons_list = array();
    $sermons_month_list = array();
    $sermons_by_device_base = array();

    /** @BUG is_dir needs to have the whole path to work properly and resolve file permisions in the file system */
    $path = realpath('../Sermons').DIRECTORY_SEPARATOR.'Sermons'.$recent_year;
    //DEBUG echo $path.'<br />'.PHP_EOL;
//scan dir and provide a list of the lates sermons unless link provided
    foreach (scandir($path) as $entryname) {
        //DEBUG echo $path.DIRECTORY_SEPARATOR.($entryname).'<br />'.PHP_EOL;
        if (is_file($path.DIRECTORY_SEPARATOR.$entryname)===true) {
            $dot = stripos($entryname,'.');
            $extension = strtolower(substr($entryname,$dot+1));
            $just_name = (substr($entryname,0,$dot-1));
            $dash =  stripos($entryname,'-');
            $caret =  stripos($entryname,'@');
            $video_format =  substr($entryname,$dash+1,(($dot-1)-($dash)));
            $video_device =  substr($entryname,$dash+1,(($caret-1)-($dash)));
            if ($extension == 'ogg') {
                if ($video_device == substr($entryname,1,-1)) {
                    $video_device = 'SoundFile';
                }

            }
            $video_encoding =  substr($entryname,$caret+1,(($dot-1)-($caret)));
            $date_sermon_year =  substr($entryname,4,4);
            $date_sermon_month =  substr($entryname,0,2);
            $date_sermon_day =  substr($entryname,2,2);
            //$date_sermon =  substr($entryname,0,$dash);
            $date_sermon =  $date_sermon_year.$date_sermon_month.$date_sermon_day; //Military format for sorting
            //DEBUG
            //            echo " extension:".$extension.'<br />'.PHP_EOL;
            //            echo " dash:".$dash.'<br />'.PHP_EOL;
            //            echo " video_format:".$video_format.'<br />'.PHP_EOL;
            //            echo " video_device:".$video_device.'<br />'.PHP_EOL;
            //            echo " video_encoding:".$video_encoding.'<br />'.PHP_EOL;
            //            echo " date_sermon:".$date_sermon.'<br />'.PHP_EOL;
            //            echo " date_sermon_year:".$date_sermon_year.'<br />'.PHP_EOL;
            //            echo " date_sermon_month:".$date_sermon_month.'<br />'.PHP_EOL;
            //            echo " date_sermon_day:".$date_sermon_day.'<br />'.PHP_EOL;
            //DEBUG echo " is dir ".'<br />'.PHP_EOL;

            //add all the sermons


            if ($extension == 'jpg' || $extension == 'mov' || $extension == 'mp4' || $extension == 'ogg') { //if it starts with 'Sermons'
                $sermons_base[$date_sermon_year][$date_sermon_month][$date_sermon_day] = array(
                    "date"=>$date_sermon,
                    "filename" => $entryname,
                    "just_name" => $just_name,
                    "device" => $video_device,
                    "format" => $video_format,
                    "encoding" => $video_encoding ); //add it to the array
                $sermons_by_device_base[$date_sermon][$video_device] = array(
                    "filename" => $entryname,
                    "just_name" => $just_name,
                    "device" => $video_device,
                    "format" => $video_format,
                    "encoding" => $video_encoding,
                    "year" =>$date_sermon_year,
                    "month" => $date_sermon_month,
                    "day" => $date_sermon_day); //add it to the array

                //month count
                //make a list of unique sermon entries
                 $encontre_mes = 0;
                foreach ($sermons_month_list as $fecha_mes=> $mes) {
                    if ($fecha_mes == $date_sermon_month) {
                        $encontre_mes = 1;
                    }
                }
                if ($encontre_mes == 0) { //did not find month so add it
                    $sermons_month_list["$date_sermon_month"] = $date_sermon_month;
                    //count it too
                    $month_count++;
                }

                //make a list of unique sermon entries
                $encontre_sermon = 0;
                foreach ($sermons_list as $fecha_sermon => $archivo) {
                    if ($fecha_sermon == $date_sermon) {
                        $encontre_sermon = 1;
                    }
                }
                if ($encontre_sermon == 0) {  //did not find so add it
                    $sermons_list["$date_sermon"] = $entryname;
                }
            }
        }
    }
    //sort our found sermons latest on top
    krsort($sermons_by_device_base, SORT_NUMERIC);
    //DEBUG echo print_r($years).'<br />'.PHP_EOL;;
    //        echo ' sermons_list:'.print_r($sermons_list).PHP_EOL;
    //        echo ' sermons_base:'.print_r($sermons_base).PHP_EOL;
    //            echo ' sermons_month_list:'.print_r($sermons_month_list).PHP_EOL;
    //            echo ' sermons_by_device_base:'.print_r($sermons_by_device_base).PHP_EOL;
    //        die();
    //
    // $sermons_by_device_base_encoded_json = json_encode($sermons_by_device_base ); // ? JSON_FORCE_OBJECT  for javascript integration ?

    //DEBUG
    //    echo ' sermons_by_device_base_encoded_json:'.print_r($sermons_by_device_base_encoded_json).PHP_EOL;

    //DEBUG This is how we are going to read and save and retrive into array
    //    $result = file_put_contents('sermons_by_device_base_encoded.json', $sermons_by_device_base_encoded_json);
    //    $test_read = file_get_contents('sermons_by_device_base_encoded.json');
    //    $sermons_by_device_base_read = json_decode($test_read, true);  //true is for Array transformation
    //
    //    echo ' sermons_by_device_base_read:'.print_r($sermons_by_device_base_read).PHP_EOL;
    //    echo ' sermons_by_device_base_read:'.print_r($sermons_by_device_base_read).PHP_EOL;

    //assign latest sermons or recent sermons
    // if there are more then one 3 months then  show a list of months
    $title = "Aloha Christian Church Sermons " . $recent_year; //assumed there will always be sermons
///TODO template


$base_host = '../';


    $html = '<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>'.$title.'</title>
    <base href="'.$base_host.'" >
    <meta name="keywords"
      content="sermons,bible study fellowship,sunday sermons,bible study,christian church,christian,christians,disciples of christ, disciples, jesus,christ, chalice, resources, god, disciple, church, congregation, religion, religious,audio sermon,sermons for youth,church of god,sermons on line,youth sermons,the church of christ,christain sermons,mp3 sermons,youth ministry,sermons online,video sermons,christian churches,online sermons,sermons on worship,community of christ,christian sermons,sermons that work,christian ministry,free sermon,online church services,free sermons,audio sermons,free online sermons,disciples of christ,sermons bible,sermon search,giving,tithe,donation,online donation,online giving"/>

 <meta name="viewport" content="width=device-width">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <meta http-equiv="cleartype" content="on">

 <!-- Mobile iOS stuff -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/> <!--320-->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="">
    <script>(function(a,b,c){if(c in b&&b[c]){var d,e=a.location,f=/^(a|html)$/i;a.addEventListener("click",function(a){d=a.target;while(!f.test(d.nodeName))d=d.parentNode;"href"in d&&(d.href.indexOf("http")||~d.href.indexOf(e.host))&&(a.preventDefault(),e.href=d.href)},!1)}})(document,window.navigator,"standalone")</script>

  <!-- Stylesheets -->

    <link rel="shortcut icon"  href="http://www.alohachristianchurch.org/favicon.ico"/>
    <link rel="stylesheet" href="sermon/stylesheets/template_u/template_u.css" />
    <link rel="stylesheet" href="sermon/stylesheets/template_u/template_index.css" />
    <link rel="stylesheet" href="sermon/stylesheets/fonts/ff-clan-web-pro_typekit.css" />
    <link rel="stylesheet" href="sermon/stylesheets/fonts/ff-clan-web-pro_typekit.css" />

    <link rel="stylesheet" href="sermon/stylesheets/sermon-styles.css" />

    <!--[if IE]>
        <link rel="stylesheet" href="sermon/stylesheets/sermon-styles-ie.css" />

    <![endif]-->


    <link href="jPlayer/jQuery.jPlayer.2.5.0.demos/skin/blue.monday/jplayer.blue.monday.css" rel="stylesheet"
          type="text/css"/>
    <style>';

    //dynamically add space for download section


    $html .='
    </style>
    <script type="text/javascript">

  (function(c,a){window.mixpanel=a;var b,d,h,e;b=c.createElement("script");
  b.type="text/javascript";b.async=!0;b.src=("https:"===c.location.protocol?"https:":"http:")+
  \'/sermon/stylesheets/template_u/mxpnl.com/libs/mixpanel-2.2.min.js\';d=c.getElementsByTagName("script")[0];
  d.parentNode.insertBefore(b,d);a._i=[];a.init=function(b,c,f){function d(a,b){
  var c=b.split(".");2==c.length&&(a=a[c[0]],b=c[1]);a[b]=function(){a.push([b].concat(
  Array.prototype.slice.call(arguments,0)))}}var g=a;"undefined"!==typeof f?g=a[f]=[]:
  f="mixpanel";g.people=g.people||[];h=[\'disable\',\'track\',\'track_pageview\',\'track_links\',
  \'track_forms\',\'register\',\'register_once\',\'unregister\',\'identify\',\'alias\',\'name_tag\',\'set_config\',
  \'people.set\',\'people.set_once\',\'people.increment\',\'people.track_charge\',\'people.append\'];
  for(e=0;e<h.length;e++)d(g,h[e]);a._i.push([b,c,f])};a.__SV=1.2;})(document,window.mixpanel||[]);
  mixpanel.init("30c4bc985bc9b41d537831b556409d8e");
</script>';
 /*
$html .= '
    <!--<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>-->

    ';
   */

    $html .= '
<script type="text/javascript" src="/sermon/stylesheets/template_u/mxpnl.com/libs/jquery.js"></script>
    <script type="text/javascript" src="jPlayer/jQuery.jPlayer.2.5.0.demos/js/jquery.jplayer.min.js"></script>
    <script type="text/javascript" src="jPlayer/jQuery.jPlayer.2.5.0.demos/js/jplayer.playlist.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {';


     $video_javascript_mapper = '[';
        /**
         *
        //Player Java Script Sample to follow
        //                {
        //                    title: "Sermon from Sunday December 29th, 2013",
        //                    artist: "Aloha Christian Church",
        //                    free: true,
        //                    m4v: "Sermons/Sermons2013/12292013-PSP@H264.mp4",       //Medium that plays too
        //                    ogg: "Sermons/Sermons2013/12292013.ogg",       //Sound file
        //                    poster: "Sermons/Sermons2013/12292013-PSP@H264.THM"  //AutoGenerated PSP Image
                            },   // <- notice comma

        ///add Sermon list
         * SERMON BY DEVICE ARRAY Structure
         * Array
            (
                [01052014] => Array
                    (
                        [MobilePhone] => Array
                            (
                                [filename] => 01052014-MobilePhone@H264.mp4
                                [device] => MobilePhone
                                [format] => MobilePhone@H264
                                [encoding] => H264
                                [year] => 2014
                                [month] => 05
                                [day] => 01
                            )
         *
         * HTML DOWLOAD Sample to follow
            <span class="video_player_links">
            <h4>December 29th Sermon</h4>
            <a class="a-btn" href="PDF Files/PDF 2013/12-29-13.pdf">Worship Page PDF</a>
            <a class="a-btn" href="Sermons/Sermons2013/12292013-iPhone@H264.mp4">Video for iPhone</a>
            <a class="a-btn" href="Sermons/Sermons2013/12292013-iPod@H264.mp4">Video for iPod</a>
            <a class="a-btn" href="Sermons/Sermons2013/12292013-PSP@H264.mp4">Video for PSP</a>
            <a class="a-btn" href="Sermons/Sermons2013/12292013-MobilePhone@H264.mp4">Video Sermon for Mobile</a>
            <a class="a-btn" href="Sermons/Sermons2013/12292013.ogg">Sound File Sermon</a>
            </span>
         **/

    $other_months_array =array();
    $all_devices_downloads = array();
        //if the there are 3 or more months
        if ($month_count > 3) {
            //TODO Then display all months filter. else display All three months
        } else {
            //less then 3
            //Display all three months together
            //$date_sermon
            $total_sermons_by_device_base = sizeof($sermons_by_device_base);
            $counter = 0;
            $counterito = -1;

            foreach($sermons_by_device_base as $get_sermon_date => $devices_arrays) {
                $counter ++;
                $devices_build_temp = '@';
                foreach($devices_arrays as $description => $contents_array) {
                    $device = $contents_array['device'];
                    $filename = $contents_array['filename'];
                    $just_name = $contents_array['just_name'];
                    $year = $contents_array['year'];
                    $year_left = substr($year,2);
                    $day = $contents_array['day'];
                    $month = $contents_array['month'];
                    $month_translated = ucwords($month_translation["$month"]); //capitalize month name
                    $month_st_or_th_rd = 'th';
                    $month_val  = intval($month);
                    if ($month_val == 1 || $month_val == 21 || $month_val == 31) { //st
                        $month_st_or_th_rd = 'th';
                    } elseif ($month_val == 2 || $month_val == 22) {
                        $month_st_or_th_rd = 'nd';
                    } elseif ($month_val == 3 || $month_val == 23) {
                        $month_st_or_th_rd = 'rd';
                    }
                    $dayth_st_or_th_rd = "th";
                    $dayth_val  = intval($day);
                    if ($dayth_val == 1 || $dayth_val == 21 || $dayth_val == 31) { //st
                        $dayth_st_or_th_rd = 'th';
                    } elseif ($dayth_val == 2 || $dayth_val == 22) {
                        $dayth_st_or_th_rd = 'nd';
                    } elseif ($dayth_val == 3 || $dayth_val == 23) {
                        $dayth_st_or_th_rd = 'rd';
                    }


                    //Recent month assign only once
                    if ($recent_month_name=='@') {  //heading for current page
                        $recent_month_name = $month_translated;
                    }
                    if ($recent_dayth_name=='@') {  //heading for current page
                        $recent_dayth_name = $day.$dayth_st_or_th_rd;
                    }

                    //HTML for Downloads build
                    if ($devices_build_temp == '@') {  //add only one per bunch of devices
                        $devices_build_temp = '<span class="video_player_links">';
                        $devices_build_temp .= '<h4>'.$month_translated.' '.$day.$dayth_st_or_th_rd.' Sermon</h4>';
                        //check if PDF exists
                        $pdf_file_temp = 'PDF Files/PDF '.$year.'/'.$month.'-'.$day.'-'.$year_left.'.pdf';
                        //check to see if thumbnail file exists
                        if (file_exists('../'.$pdf_file_temp)) {
                            //only create link if file exists
                            $devices_build_temp .= '<a href="'.$pdf_file_temp.'">Worship Page PDF</a>';
                            $devices_build_temp .= "<br />"; //add a break to make it stand out
                            $counterito = -2;
                        }
                    }
                    //add all the devices
                    $devices_build_temp .= '<a href="Sermons/Sermons'.$year.'/'.$filename.'">'.$device.'</a>';


                    //Other months build HTML
                    $year_month = $year.$month;
                    $other_months_array["$year_month"] = '<a href="sermon/?year='.$year.'&$month='.$month.'">'.$month_translated.' Sermons</a>';


                    //javascript Video players build. Assumption that we have a PSP file and that we have an ogg file
                    $thumbnail_image_temp = 'Sermons/Sermons'.$year.'/'.$just_name.'.THM'; //asumption
                    //check to see if thumbnail file exists
                    if (!file_exists('../'.$thumbnail_image_temp)) {
                        //asign default in case it doesn't exists
                        $thumbnail_image_temp = 'Sermons/AlohaChristianChurch_480x270.png';
                    }
                    if ($device == 'PSP') {  //WE are only posting PSP videos //asusmed they work in H264 mpegs
                        //DEBUG echo ' sermon get_sermon_date:'.$get_sermon_date.PHP_EOL;
                        $video_javascript_mapper .= PHP_EOL.'{'.PHP_EOL.'
                            title: "Sermon from Sunday '.$month_translated.' '.$day.$dayth_st_or_th_rd.', '.$year.'",
                            artist: "Aloha Christian Church",
                            free: true,
                            m4v: "Sermons/Sermons'.$year.'/'.$filename.'",
                            ogg: "Sermons/Sermons'.$year.'/'.$month.$day.$year.'.ogg",
                            poster: "'.$thumbnail_image_temp.'"
                            }';

                    } //endif only PSP
                    //$counterito ++;

                    if (++$counterito == 5) {
                        $counterito = -1;
                        //$devices_build_temp .= "<br />";
                    }
                } //end foreach device

                //html downloads build
                if ($devices_build_temp == '@') {  //repeated on purpose just in case there are no device so the html does not break
                    $devices_build_temp = '<span class="video_player_links">';
                }
                $devices_build_temp .= '</span>';
                //Pass the devices
                $all_devices_downloads[] = $devices_build_temp; //add to array

                //javascript build
                if ($counter < $total_sermons_by_device_base ){ //only ad comma if there are more
                    $video_javascript_mapper .= ',';
                }
            } //end foreach day entry
            //die();

        }


    /**
     *
     */
$video_javascript_mapper .= ']';



	$html .= 'new jPlayerPlaylist({
                jPlayer: "#jquery_jplayer_1",
                cssSelectorAncestor: "#jp_container_1"
            },';
	$html .= $video_javascript_mapper;
	$html .= ", {

                supplied: \"m4v, ogg\",

                smoothPlayBar: true,
                keyEnabled: true
            });";

//	$html .= 'new jPlayerPlaylist({
//                jPlayer: "#jquery_jplayer_2",
//                cssSelectorAncestor: "#jp_container_2"
//            },';
//	$html .= $video_javascript_mapper;
//	$html .= ", {
//
//                supplied: \"m4v, ogg\",
//
//                smoothPlayBar: true,
//                keyEnabled: true
//            });";

//end jquery
$html .= "

        });
    </script>";



    $links_on_the_left= array();
    $links_on_the_left[] = '<a class="home" href="/">Home</a>';
    $links_on_the_left[] = '<a href="belief.html">What We Believe</a>';
    $links_on_the_left[] = '<a href="message.html">Pastor&apos;s Greeting</a>';
    $links_on_the_left[] = ' <a href="youthandEd.html">Youth &amp; Education Ministries</a>';
    $links_on_the_left[] = ' <a href="mondaynights.html">Monday Nights</a>';
    $links_on_the_left[] = ' <a href="workship.html">Workship</a>';
    $links_on_the_left[] = ' <a href="outreach.html">Outreach Ministries</a>';
    $links_on_the_left[] = ' <a href="sermons.html">Sermons</a>';
    $links_on_the_left[] = ' <a href="audiofiles2013.html">Audio Sermons</a>';
    $links_on_the_left[] = ' <a href="Giving.html">Donate</a>';
    $links_on_the_left[] = ' <a href="location.html">Location</a>';
    $links_on_the_left[] = ' <a href="www.my.calendars.net/alohacccal">Calendar</a>';
    $links_on_the_left[] = ' <a href="links.html">Links</a>';
    $links_on_the_left[] = ' <a href="mailto:accdoc@alohachristianchurch.org">Contact Us</a>';


//Start body
    $html .= '</head>
 <body class="dark light-foot twoColElsLt" onload="">';

//left menu autoclosable
$html .= '
    <!--[if lt IE 7]>
      <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>';

$html .= '
    <![endif]-->


    <div class="preloader js-preloader"></div>

    <!-- This allows for opening and closing of the main nav -->

    <div class="web global-container js-global-container">';











            $html .= '

                <nav class="global-nav js-global-nav">

                <div class="actions yui3-g">
                    <a href="#" class="close js-menu yui3-u-1-2 res-s-hidden">
                        <span class="icon-close"></span>
                    </a>
                </div>

                <p class="primary">
                            ';

                    foreach($links_on_the_left as $lnk_left) {
                    $html .= $lnk_left;
                    }

                    $html .= '
                    <!--
                        <a href="/log-in" class="signin yui3-u-1-2 res-s-hidden">
                              Log In
                        </a>
                        <a href="/sign-up" class="js-app-store-btn signup yui3-u-1">
                          Sign up
                        </a>
                    -->
                </p>

                <div class="secondary">

                    <!--
                    <p>

                      <a href="/log-in" class="res-m-hidden res-l-hidden">
                        Log In
                      </a>

                    </p>
                    -->
                </div>
                        <!--
                    <form class="language-picker js-language-picker" action="#">
                    <label for="language">Language</label>
                    <select id="language" class="lang-select js-lang-select">

                    <option value="en-US">
                      English
                    </option>
                        <option value="es-ES">
                          Español
                        </option>
                    </select>
                    <span class="dd-arrow icon-signup-downarrow"></span>
                    </form>

                    <div class="downloads res-s-hidden">
                    <a href="#donwload_for_iPhone"
                     target="_blank" class="apple-phone ir js-app-store-btn">Apple iPhone</a>
                    <a href="#donwload_for_Android"
                     target="_blank" class="google-phone ir">Android</a>
                    <a href="#donwload_for_iPod"
                     target="_blank" class="apple-store ir js-app-store-btn">Apple iPod</a>
                    <a href="#donwload_for_Mobile"
                     target="_blank" class="google-play ir">Mobile Phone</a>
                    </div>
                    -->
                </nav>

            <section class="site-wrapper js-site-wrapper">
                <div class="global-nav-overlay js-global-nav-overlay"></div>
                <div class="head-fade"></div>

                <header class="global-header js-global-header">
                              <div class="pull-left">
                                <a href="#" class="menu js-menu">
                                  <span class="icon-menu"></span>
                                  <span class="menu-text res-s-hidden">Menu</span>
                                </a>
                              </div>

                              <p class="pull-right actions res-s-hidden">

                                    <!--
                                    <a href="/log-in" class="ga-click-track"
                                       data-ga="global-header.login-button-click">
                                      Log In
                                    </a>

                                  <a href="/sign-up" class="btn ga-click-track js-signup-or-dl-cta js-signup-btn"
                                     data-ga="global-header.sign-up-button-click">
                                    Sign Up
                                  </a>
                        -->

                              </p>
                        <!--
                                  <p class="pull-right actions res-m-hidden res-l-hidden">
                                    <a href="/app" class="btn ga-click-track js-signup-or-dl-cta js-signup-btn"
                                       data-ga="global-header.download-mobile">
                                        Download
                                    </a>
                                  </p>
                        -->
                              <div class="yui3-u-1-3 center block-center">
                                <a href="/" class="uber-logo ir ga-click-track"
                                   data-ga="global-header.logo-click">Aloha Christian Church</a>
                              </div>

                </header>';


                /*
                $html .= '
                <section class="hero js-home-hero">

                                      <nav class="controller js-home-controller">
                                       </nav>
                                    <!--
                                      <article class="active hero-slide js-home-slide" data-slide="moving-people">
                                        <div class="headline">
                                          <h1>Moving people</h1>
                                          <p>The uber app connects you with a driver at the tap of a button</p>
                                          <p class="cta">
                                            <a href="/sign-up" class="ga-click-track js-signup-btn"
                                               data-ga="home-hero-exp.slide-nav-signup-button-one-click">
                                              Sign up for uber
                                            </a>
                                            <span></span>
                                          </p>
                                        </div>
                                     </article>

                                      <article class="hero-slide js-home-slide" data-slide="mark-it">
                                        <div class="headline">
                                          <h1>Get it done</h1>
                                          <p>uberX makes it easy and affordable to take care of the everyday</p>
                                          <p class="cta">
                                            <a href="/sign-up" class="ga-click-track js-signup-btn"
                                               data-ga="home-hero-exp.slide-nav-signup-button-two-click">
                                              Sign up for uber
                                            </a>
                                            <span></span>
                                          </p>
                                        </div>
                                      </article>

                                      <article class="hero-slide js-home-slide" data-slide="night-out">
                                        <div class="headline">
                                          <h1>Make it a night out</h1>
                                          <p>Kick off your evening with no reservations</p>
                                          <p class="cta">
                                            <a href="/sign-up" class="ga-click-track js-signup-btn"
                                               data-ga="home-hero-exp.slide-nav-signup-button-three-click">
                                              Sign up for uber
                                            </a>
                                            <span></span>
                                          </p>
                                        </div>
                                     </article>
                                    -->
                </section>
                ';
                */

                $html .= '
                <section class="request-and-ride js-request-and-ride">
                          <div class="grid-locked grid-squeezed">



                                        <article >
                                          <p class="label">Sermon</p>';

                                    //Say sermon name title month

                                    $html .= '<h3>'.$recent_month_name.' '.$recent_dayth_name.' Sermon</h3>

                                          <p>Enjoy this recorded sermon or scroll to find download links.</p>';

                                     //1st video player code
                                        $html .= '

                                                                    <span class="videocenter">
                                                                        <!-- video player start -->
                                                    <div id="jp_container_1" class=" jp-video jp-video-270p">
                                                        <div class="jp-type-playlist">
                                                            <div id="jquery_jplayer_1" class="jp-jplayer"></div>
                                                            <div class="jp-gui">
                                                                <div class="jp-video-play">
                                                                    <a href="javascript:;" class="jp-video-play-icon" tabindex="1">play</a>
                                                                </div>
                                                                <div class="jp-interface">

                                                                    <div class="jp-progress">
                                                                        <div class="jp-seek-bar">
                                                                            <div class="jp-play-bar"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="jp-current-time"></div>
                                                                    <div class="jp-duration"></div>
                                                                    <div class="jp-controls-holder">
                                                                        <ul class="jp-controls">
                                                                            <li><a href="javascript:;" class="jp-previous" tabindex="1">previous</a></li>
                                                                            <li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li>

                                                                            <li><a href="javascript:;" class="jp-pause" tabindex="1">pause</a></li>
                                                                            <li><a href="javascript:;" class="jp-next" tabindex="1">next</a></li>

                                                                            <li><a href="javascript:;" class="jp-stop" tabindex="1">stop</a></li>
                                                                            <li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute">mute</a>
                                                                            </li>
                                                                            <li><a href="javascript:;" class="jp-unmute" tabindex="1"
                                                                                   title="unmute">unmute</a></li>
                                                                            <li><a href="javascript:;" class="jp-volume-max" tabindex="1"
                                                                                   title="max volume">max volume</a></li>
                                                                        </ul>
                                                                        <div class="jp-volume-bar">
                                                                            <div class="jp-volume-bar-value"></div>

                                                                        </div>
                                                                        <ul class="jp-toggles">
                                                                            <li><a href="javascript:;" class="jp-full-screen" tabindex="1"
                                                                                   title="full screen">full screen</a></li>

                                                                            <li><a href="javascript:;" class="jp-restore-screen" tabindex="1"
                                                                                   title="restore screen">restore screen</a></li>
                                                                            <li><a href="javascript:;" class="jp-shuffle" tabindex="1" title="shuffle">shuffle</a>

                                                                            </li>
                                                                            <li><a href="javascript:;" class="jp-shuffle-off" tabindex="1"

                                                                                   title="shuffle off">shuffle off</a></li>
                                                                            <li><a href="javascript:;" class="jp-repeat" tabindex="1"
                                                                                   title="repeat">repeat</a></li>
                                                                            <li><a href="javascript:;" class="jp-repeat-off" tabindex="1"
                                                                                   title="repeat off">repeat off</a></li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="jp-title">
                                                                        <ul>
                                                                            <li></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="jp-playlist">
                                                                <ul>
                                                                    <!-- The method Playlist.displayPlaylist() uses this unordered list -->
                                                                    <li></li>
                                                                </ul>
                                                            </div>
                                                            <div class="jp-no-solution">
                                                                <span>Update Required</span>
                                                                To play the media you will need to either update your browser to a recent version or update
                                                                your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- video player end -->
                                                                    </span>';
                                    $html .='
                                        </article>


                          </div>
                </section>


                <section class="vehicle-fleet yui3-g">';
                            /*
                            $html .=
                                '
                              <div class="bg-top"></div>';
                            */
                                $html .=
                                    '
                                  <div class="grid-locked grid-squeezed">

                                    <article class="content js-content yui3-u-1">

                                      <p class="label inverted">Download Latest Sermon options</p>
                                      <h3>Download Area</h3>
                                      <p>Choice is a beautiful thing. Choose the media that fits your needs</p>
                                <span class="style6 green">';

                                 foreach($all_devices_downloads as $this_device_download) {
                                        $html .= $this_device_download;


                                 }
                            $html .= '
                                </span></article>

                              </div>
                </section>

            </section>





            <footer class="global-footer js-global-footer yui3-g">
                <div class="grid-locked">

                        ';
                        /*
                        $html .= '
                        <a href="/app"
                           class="sign-up yui3-u-1 res-l-hidden res-m-hidden ga-click-track js-signup-or-dl-cta js-signup-btn"
                           data-ga="global-footer.download-mobile">
                          Download
                        </a>';

                        $html .= '
                        <article class="social yui3-u-1-4 yui3-u-s-1">

                        <!--
                          <a href="https://www.facebook.com/uber" target="_blank"
                             class="icon icon-facebook ga-click-track"
                             data-ga="global-footer.facebook-button-click"></a>
                          <a href="https://twitter.com/uber" target="_blank"
                             class="icon icon-twitter ga-click-track"
                             data-ga="global-footer.twitter-button-click"></a>
                          <a href="http://www.linkedin.com/company/1815218" target="_blank"
                             class="icon icon-linkedin_box ga-click-track"
                             data-ga="global-footer.linkedin-button-click"></a>
                        -->
                        </article>';
                        */

                        $html .= '
                        <article class="navs yui3-u-1-2 res-s-hidden">';
                        /*
                        <!--
                          <a href="/sign-up"
                             class="sign-up ga-click-track js-signup-or-dl-cta js-signup-btn"
                             data-ga="global-footer.sign-up-button-web-click">
                            Sign up
                          </a>
                        -->
                        */

                        $html .= '
                          <nav class="primary">
                            <a href="/" class="ga-click-track"
                               data-ga="global-footer.home-button-click">
                              Home
                            </a>
                            <span>&bull;</span>
                            <a href="/" class="ga-click-track"
                               data-ga="global-footer.cities-button-click">
                              Service Times
                            </a>
                            <span>&bull;</span>
                            <a href="/belief.html" class="ga-click-track"
                               data-ga="global-footer.drivers-button-click">
                              What We Believe
                            </a>
                          </nav>
                          <nav class="secondary">
                            <a href="/location.html" class="ga-click-track"
                               data-ga="global-footer.about-button-click">
                              Where We&apos;re Located
                            </a>
                            <a href="/links.html" target="_blank">
                              Links
                            </a>
                            <a href="/outreach.html" class="ga-click-track"
                               data-ga="global-footer.jobs-button-click">
                              Out Reach Ministries
                            </a>
                            <a href="/Giving.html" target="_blank">
                              Donate
                            </a>
                          </nav>';

                        /*
                        $html .= '



                        <!--

                            <a href="#" class="current-lng">
                              English
                            </a>

                            <form class="language-picker js-language-picker" action="#">
                              <select id="language" class="lang-select js-lang-select">

                                  <option value="en-US">
                                    English
                                  </option>

                                 <option value="es-ES">
                                    Español
                                  </option>

                              </select>
                            </form>
                        -->';
                        */
                        $html .= '
                        </article>';

                        /*
                        $html .= '
                        <article class="downloads yui3-u-1-4 res-s-hidden">

                        <!--
                          <a href="https://itunes.apple.com/us/app/uber/id368677368?mt=8"
                             target="_blank" class="apple-store ir ga-click-track"
                             data-ga="global-footer.apple-store-button-click">Apple Store</a>
                          <a href="https://play.google.com/store/apps/details?id=com.ubercab"
                             target="_blank" class="google-play ir ga-click-track"
                             data-ga="global-footer.google-play-button-click">Google Play Store</a>
                        -->
                        </article>';
                        */
                        $html .= '
                </div>
            </footer>


    </div><!-- End of global-container -->




';



    //test table fist start
    //test table fist start
    //test table fist start
    //test table fist start
    //test table fist start
    //test table fist start
    //test table fist start
    //test table fist start
    //test table fist start

    //first table old site
    $html .= '
<div style="height:81px; display:block;"></div>
<table width="1105" border="1" align="center">

<td width="307" valign="top" align="center" class="style6 blue">';

//second table  links on the left

    foreach($links_on_the_left as $lnk_left) {
        $html .= '<p>'.$lnk_left.'</p>';
    }
    $html .= '

</td>';


//The start for the old page
    $html .='
<td width="782" valign="top">
<div align="center" class="style1">
<table width="625" border="0" align="center">
    <tr>
        <td width="615">
            <div align="center"><span class="style11 style2">Aloha Christian Church</span></div>
        </td>
    </tr>
    <tr>
        <td height="59">
            <div align="center"><span class="style10">(Disciples of Christ) </span></div>
        </td>
    </tr>
</table>';








    //links to other sermons from the same year in groups of 4
    $html .= '
<table width="786" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td align="center" valign="top" class="style6">';
    $counter = 0;
    $open_td = 0;
    foreach($other_months_array as $month_link) {
        $counter++;
        $html .= $month_link.PHP_EOL;
        $open_td = 1;
        if ($counter == 4) {
            $html .= '
                </td>
            </tr>
            <tr>
                <td align="center" valign="top" class="style16">';
        }
    }


    $html .= '  </td>
    </tr>';






    //links to older years

    $html .= '
    <tr>
        <td align="center" valign="top" class="style6">

                <a href="sermons.html">2013 Sermons</a> -
                <a href="ACC2012/sermons2012.html">2012 Sermons</a> -
                <a href="ACC2011/sermons2011.html">2011 Sermons</a>
            	<a href="ACC2010/sermons2010.html">2010 Sermons</a>
        </td>
    </tr>';









    //current month display

    $html .= '
    <tr>
        <td width="786">
            <div align="center"><span class="style8">'.$recent_month_name.' '.$recent_year.' Sermons</span></div>
        </td>
    </tr>';




    //video player code was here after the div
    $html .= '
    <tr>
        <td>
            <div align="center">';

    /*
    //2nd video player code old table style
    $html .= '

                                <span>
                                    <!-- video player start -->
                <div align="center" id="jp_container_2" class=" style16 jp-video jp-video-270p">
                    <div class="jp-type-playlist">
                        <div id="jquery_jplayer_2" class="jp-jplayer"></div>
                        <div class="jp-gui">
                            <div class="jp-video-play">
                                <a href="javascript:;" class="jp-video-play-icon" tabindex="1">play</a>
                            </div>
                            <div class="jp-interface">
                                <div class="jp-progress">
                                    <div class="jp-seek-bar">
                                        <div class="jp-play-bar"></div>
                                    </div>
                                </div>
                                <div class="jp-current-time"></div>
                                <div class="jp-duration"></div>
                                <div class="jp-controls-holder">
                                    <ul class="jp-controls">
                                        <li><a href="javascript:;" class="jp-previous" tabindex="1">previous</a></li>
                                        <li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li>
                                        <li><a href="javascript:;" class="jp-pause" tabindex="1">pause</a></li>
                                        <li><a href="javascript:;" class="jp-next" tabindex="1">next</a></li>
                                        <li><a href="javascript:;" class="jp-stop" tabindex="1">stop</a></li>
                                        <li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute">mute</a>
                                        </li>
                                        <li><a href="javascript:;" class="jp-unmute" tabindex="1"
                                               title="unmute">unmute</a></li>
                                        <li><a href="javascript:;" class="jp-volume-max" tabindex="1"
                                               title="max volume">max volume</a></li>
                                    </ul>
                                    <div class="jp-volume-bar">
                                        <div class="jp-volume-bar-value"></div>
                                    </div>
                                    <ul class="jp-toggles">
                                        <li><a href="javascript:;" class="jp-full-screen" tabindex="1"
                                               title="full screen">full screen</a></li>
                                        <li><a href="javascript:;" class="jp-restore-screen" tabindex="1"
                                               title="restore screen">restore screen</a></li>
                                        <li><a href="javascript:;" class="jp-shuffle" tabindex="1" title="shuffle">shuffle</a>
                                        </li>
                                        <li><a href="javascript:;" class="jp-shuffle-off" tabindex="1"
                                               title="shuffle off">shuffle off</a></li>
                                        <li><a href="javascript:;" class="jp-repeat" tabindex="1"
                                               title="repeat">repeat</a></li>
                                        <li><a href="javascript:;" class="jp-repeat-off" tabindex="1"
                                               title="repeat off">repeat off</a></li>
                                    </ul>
                                </div>
                                <div class="jp-title">
                                    <ul>

                                        <li></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="jp-playlist">
                            <ul>
                                <!-- The method Playlist.displayPlaylist() uses this unordered list -->
                                <li></li>
                            </ul>
                        </div>
                        <div class="jp-no-solution">
                            <span>Update Required</span>
                            To play the media you will need to either update your browser to a recent version or update
                            your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
                        </div>
                    </div>
                </div>

                <!-- video player end -->
                                </span>';
*/

    // $downloads for all devices

    $html .= '<span class="video_player_links">
                <h3>'.$recent_month_name.' Downloads</h3>
              </span> <span class="style6">';


    //Expecting this
    /**

     *
    <span class="video_player_links">
    <h4>December 29th Sermon</h4>

    <a class="a-btn" href="PDF Files/PDF 2013/12-29-13.pdf">Worship Page PDF</a>
    <a class="a-btn" href="Sermons/Sermons2013/12292013-iPhone@H264.mp4">Video for iPhone</a>
    <a class="a-btn" href="Sermons/Sermons2013/12292013-iPod@H264.mp4">Video for iPod</a>
    <a class="a-btn" href="Sermons/Sermons2013/12292013-PSP@H264.mp4">Video for PSP</a>

    <a class="a-btn" href="Sermons/Sermons2013/12292013-MobilePhone@H264.mp4">Video Sermon for Mobile</a>
    <a class="a-btn" href="Sermons/Sermons2013/12292013.ogg">Sound File Sermon</a>
    </span>

     */
    foreach($all_devices_downloads as $this_device_download) {
        $html .= $this_device_download;

    }



    //close main div
    $html .= '
            </span></div>

        </td>
    </tr>
</table>


</div>
</td>

</tr>
</table>



<p>';



    //footer
    $html .= '<span class="style17">Website questions/suggestions may be directed to <a

            href="mailto:webmaster@alohachristianchurch.org">webmaster@alohachristianchurch.org</a></span></p>';




    //test table fist end
    //test table fist end
    //test table fist end
    //test table fist end
    //test table fist end
    //test table fist end
    //test table fist end
    //test table fist end

   $html .= '<script src="/sermon/stylesheets/template_u/mxpnl.com/libs/require-2.1.5.min.js" data-main="/sermon/stylesheets/template_u/mxpnl.com/libs/index-path.js"></script>';


//common user
$html .= '
<script>
 //analytics

</script>
</body>
</html>
';
/* */
} //end if ($action == 'recent-sermons')

//title for monthly format Aloha Christian Church Sermons December 2013

echo $html;
/**
*/
