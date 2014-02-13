<?php
// change your pidiblog settings here:
$blogname = "Your blog name :)"; // your pidiblog name
$title    = "";                  // when empty, use blogname instead
$password = "your_password";     // password to log in
$about    = "pidiblog";          // blog name in about section
$language = "en.php";            // name of language file in lang/ folder
$name     = "your_nickname";     // if logged use this name in comments
$email    = "your_email";        // if logged use this email in comments
$apiCode  = "your_api_code";     // special code to authorize the software

$fullDate = 1;                   // 1 or 0 - whether to use 1 December 2008 or 01.12.2008
$showmenu = 1;                   // 1 or 0 - whether to show menu bar
$showrss  = 1;                   // 1 or 0 - whether to list RSS in menu
$enableComments    = 1;          // 1 or 0 - enable or disable comments
$commentsPerPage   = 5;          // number of comments per page
$minimalisticComments       = 0; // 1 or 0 - enable or disable minimalistic comments (no avatars and inline)
$minimalisticCommentsForm   = 0; // 1 or 0 - enable or disable minimalistic comments form (inline)
$colorCommentsLinkHref      = '#494343'; // colour of comments count border
$colorCommentsLinkHrefHover = '#ccc';    // colour of comments count border on hover
$emailNotification = '0';        // '0' or your email - send email if someone posts comment to your pidiblog
$api      = 0;                   // 1 or 0 - enable or disable the API


// ******************* DO NOT CHANGE ANYTHING BELOW ******************** //
define("blogname",$blogname);
define("title",$title);
define("password",$password);
define("about",$about);
define("fullDate",$fullDate);
define("showmenu",$showmenu);
define("showrss",$showrss);
define("language",$language);
define("name",$name);
define("email",$email);
define("enableComments", $enableComments);
define("commentsPerPage", $commentsPerPage);
define("minimalisticComments", $minimalisticComments);
define("minimalisticCommentsForm", $minimalisticCommentsForm);
define("colorCommentsLinkHref", $colorCommentsLinkHref);
define("colorCommentsLinkHrefHover", $colorCommentsLinkHrefHover);
define("emailNotification", $emailNotification);
define("apiCode", $apiCode);
define("api", $api);


if(file_exists("lang/$language")) {
  include("lang/$language");     // insert original lang file and user lang file after :)
}
else
{
  require_once("lang/en.php");
}

?>
