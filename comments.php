<?php
/***************************************************************************
 *   (c)2008 David Schwarz (http://vkapse.aspweb.cz)                       *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Library General Public License as       *
 *   published by the Free Software Foundation; either version 2 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 *   This program is distributed in the hope that it will be useful,       *
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of        *
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         *
 *   GNU General Public License for more details.                          *
 *                                                                         *
 *   You should have received a copy of the GNU Library General Public     *
 *   License along with this program; if not, write to the                 *
 *   Free Software Foundation, Inc.,                                       *
 *   59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.             *
 ***************************************************************************/

	require_once("./config.php");
	require("./commentsFunctions.php");

	// Important thing, this timestamp :)
	If(!IsSet($_GET["cmt"]))
	{
		echo "Wootahell?!";
		die;
	}
	$timeStamp = $_GET["cmt"];
	
	// Variable initialize
	$email = "";
	$name = "";
	$content = "";
	
	// Check
	if(IsSet($_POST["submit"]) && IsSet($_POST["email"]) && 
		IsSet($_POST["content"]) && IsSet($_POST["name"]))
	{
		// Save stuff to vars
		$email = $_POST["email"];
		$name = $_POST["name"];
		$content = $_POST["content"];
		
		if(SaveComment($timeStamp, $email, $name, $content,$lang)) // Try save
		{ // Everything is allrightski
			echo "<i>".$lang["comment_saved"]."</i><br/>\n";
		}
		else
		{ // Something smells funny here..
			echo "<b>".$lang["comment_error"]."</b><br/>\n";
		}
	}
	
	$href = IsSet($_GET["date"])? "?date=".$_GET["date"] : "/";
?>

<?php echo CommentForm($lang, $timeStamp, $name, $email, $content); ?>
<div class="hr"></div>
<?php
	$currentPage =  IsSet($_GET["page"]) ? IntVal($_GET["page"]) : 0;
	$currentPage = ($currentPage < 0)? 0 : $currentPage;
	PrintComments($lang, $timeStamp, $currentPage);
?>
