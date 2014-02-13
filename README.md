# 1. General Hosting Requirements

Working  installation of PHP is required in order  to run this application;
PHP 5 is known to work, but older versions might work as well.


# 2. Basic Settings

Although pidiblog is instantly fully operative,  there are few options such
as blog name or password you would probably like to change.  To do so, open
the  config.php  in your favourite text  editor and find  following section
near the beginning of the file:

```php
  // change your pidiblog settings here:
  $blogname = "Your blog name :)"; // your pidiblog name
  $title    = "";                  // when empty, use blogname instead
  $password = "your_password";     // password to log in
  $about    = "pidiblog";          // blog name in about section
  $language = "en.php";            // name of language file in lang/ folder
  $name     = "your_nickname";     // if logged use this name in comments
  $email    = "your_email";        // if logged use this email in comments
  $apiCode  = "your_api_code";     // special code to authorize the soft...
```

Each line should be well commented and rather self explanatory;  the use of
UTF-8 encoding is recommended  to avoid possible issues with special chara-
cters.


# 3. Additional Settings

Besides these basic settings,  there are few options enabling you to adjust
the look and feel of your pidiblog:

```php
  $fullDate = 1;                   // 1 or 0 - whether to use 1 December...
  $showmenu = 1;                   // 1 or 0 - whether to show menu bar
  $showrss  = 1;                   // 1 or 0 - whether to list RSS in menu
  $enableComments    = 1;          // 1 or 0 - enable or disable comments
  $commentsPerPage   = 5;          // number of comments per page
  $minimalisticComments       = 0; // 1 or 0 - enable or disable minimal...
  $minimalisticCommentsForm   = 0; // 1 or 0 - enable or disable minimal...
  $colorCommentsLinkHref      = '#494343'; // colour of comments count b...
  $colorCommentsLinkHrefHover = '#ccc';    // colour of comments count b...
  $emailNotification = '0';        // 0 or your email - send email if so...
  $api      = 0;                   // 1 or 0 - enable or disable the API


# 3.1 Menu Related Settings

First thing you might want to adjust  is the appearance  of the menu bar at
the top of the page. Currently, there are only two options: whether to list
the link to the RSS feed, and -- more importantly -- whether to display the
menu bar at all.  Both options  are enabled by default,  but you can easily
disable any of these by changing the value of the $showmenu and/or $showrss
variable to 0.


# 3.2 Comments Related Settings

Although pidiblog has a built-in support for users' comments,  not everyone
find them as useful  as their creators,  or is satisfied with their default
appearance.  To meet these needs,  there are several options you might want
to take a closer look at.

Changing the value of $commentsPerPage gives you the opportunity to specify
how many comments are to be listed on a single page. 

Changing the value of $minimalisticComments to 1 makes the comments listing
more compact: each comment is displayed on a single line with avatar turned
off. Similarly, $minimalisticCommentsForm places on a single line all input
forms.

The  $colorCommentsLinkHref and  $colorCommentsLinkHrefHover options affect
the colour of the comments count box and its highlighted variant  respecti-
vely. The value has to be in the form recognised by W3C CSS standard.

Finally, changing the value of $enableComments to 0  disables comments com-
pletely; this will, of course, render all previous options insignificant.


# 3.3 Posts Listing Related Settings

Another thing  you might  want to adjust  is the look of the pidiblog posts
listing, or, at least, the date format. The full date (24 December 2008) is
displayed by default;  to use the  DD.MM.YYYY  format (24.12.2008) instead,
simply change the value of $fullDate to 0.


# 4. Installation on Server

pidiblog installation is fast and easy: simply copy the content of pidiblog
source package to the remote server and change the permissions of the txts/
directory to 777,  so that everyone is  able to read,  write and access its
content.


# 5. API Usage

To make the blogging experience as pleasant as possible,  pidiblog offers a
simple  API to be used as a GET request  by a third-party software  in  the
following form:

  http://example.com/index.php?menu=api&apiCode=&password=&text=

Available options are:

 ----------+--------------------------------------------------------------
  Option   | Meaning
 ----------+--------------------------------------------------------------
  apiCode  | an authorization code set in the configuration file
  password | MD5 checksum of the valid password
  text     | a message text; use + instead of spaces, e.g. Hello+World!
 ----------+--------------------------------------------------------------

Possible return values:

 ----------+--------------------------------------------------------------
  Value    | Meaning
 ----------+--------------------------------------------------------------
  0        | everything is OK, the message has been added successfully
  1        | invalid apiCode
  2        | invalid password
  3        | empty or missing message text
  4        | unable to add the message; please try it from the website,
           | you may have set incorrect permissions to the txts/ directory
 ----------+--------------------------------------------------------------


# 6. Bugs

To report bugs,  please visit the appropriate  section on the project home-
page: <http://code.google.com/p/pidiblog/issues/>.


# 7. Author

Written by Jaromir Hradilek <jhradilek@gmail.com> and Jindrich Skacel.

Permission  is  granted  to  copy,  distribute  and/or modify this document
under the terms of  the GNU Free Documentation License, Version 1.3 or  any
later version published by the Free Software Foundation;  with no Invariant
Sections, no Front-Cover Texts, and no Back-Cover Texts.

For more information, see <http://www.gnu.org/licenses/>.


# 8. Copyright

Copyright (C) 2008 Jindrich Skacel

This program is free software;  see LICENCE  for copying conditions.  It is
distributed  in the hope that it will be useful,  but WITHOUT ANY WARRANTY;
without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PAR-
TICULAR PURPOSE.
