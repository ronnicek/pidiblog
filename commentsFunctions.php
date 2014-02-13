<?php
/***************************************************************************
 *   (c)2008 David Schwarz (http://vkapse.aspweb.cz)                       *
 *   (c)2008 Jindřich Skácel (http://www.vypni.net)                        *
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
	define("COMMENTS_DIR", "./txts/");
	
	//
	// Saves comment
	//
	function SaveComment($timeStamp, &$email, &$name, &$content,$lang)
	{
		// check the refer
		if(str_replace("http://","",$_SERVER["HTTP_REFERER"])!=$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]) { return false; die; }
		$path = COMMENTS_DIR.$timeStamp.".txt"; // Path to the book
		
		// Trim and strip html tags
		$email = strip_tags(trim($_POST["email"]));
		$name = strip_tags(trim($_POST["name"]));
		$content = strip_tags(trim($_POST["content"]));
		// Another check..
		if($email != "" && $name != "" && $content != "" &&
			strlen($email) < 64 && strlen($name) < 64 && strlen($content) < 512 && 
			$_POST["name"]!=$lang["comment_nick"] && $_POST["email"]!=$lang["comment_email"] && $_POST["content"]!=$lang["comment_text"])
		{		
			// Replace enters for brs
			$content = str_replace("\r\n", "<br/>", $content);
			$content = str_replace("\n", "<br/>", $content);
			
			// Prevent to save | char
			$content = str_replace("|", "&#124;", $content);
			$email = str_replace("|", "&#124;", $email);
			$name = str_replace("|", "&#124;", $name);
			
			// Lets do append here
			if(($handle = @fopen($path, "a+")))
			{
				// Compose line to write
				$write = time()."|".md5(stripslashes($email))."|".stripslashes($name)."|".stripslashes($content)."\n";
				fwrite($handle, $write);
				fclose($handle);
				$content = ""; // Clear content
				// email notification
				if(emailNotification!="0") {
					$to      = emailNotification;
					$subject = $lang["notification_from"].blogname;
					$message = $lang["notification_text"].$name.'. http://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"].'';
					$headers = 'From: '.emailNotification.'' . "\r\n" .
    						   'X-Mailer: PHP/' . phpversion();
					// send it - dont show errors :)
					mail($to, $subject, $message, $headers);
				}
				// save cookie with name and email
				if($_POST["save_cookie"]) {
					setcookie("name", $name, time()+(60*60*24*365));
					setcookie("email", $email, time()+(60*60*24*365));
				}
				return true;
			}
		}
		return false;
	}
	
	//
	// Prints hrefs to pages
	//
	function PrintPaging($lang, $timeStamp, $commentCount, $currentPage)
	{
		echo $lang["comment_page"].": ";
		$count = ($commentCount / commentsPerPage);
		for($i = 0; $i < $count; ++$i)
		{
			$j = ($i * commentsPerPage);
			if($j == $currentPage)
			{
				echo ($i + 1)." ";
				continue;
			}
			echo "<a href=\"?cmt=".$timeStamp."&page=".$j."\">".($i + 1)."</a> ";
		}
		echo "<br/>";
	}
	
	//
	// Prints all comments in defines page
	//
	function PrintComments($lang, $timeStamp, $page)
	{
		$path = COMMENTS_DIR.$timeStamp.".txt"; // Path to the book
		// Define array
		$comments = array();
		// Open book (won't show any warnings)
		if(($handle = @fopen($path, "r")))
		{
			while(($line = fgets($handle))) // Read 'til end
			{
				$parts = explode("|", $line); // Explode line
				$comment = "";
				if(minimalisticComments)
				{
					// If minimalistic commments are enabled
					$comment = "<span class=\"CommentHeader\">".
					$parts[2]." - <span class=\"DateTime\">".date("j.n.Y, H:i:s", $parts[0])."</span>:</span> ".
					$parts[3]."<br/>";
					
				}
				else
				{
					// Create normal comment with avatar
					$gravatar_url = "http://www.gravatar.com/avatar.php?".
						"gravatar_id=".$parts[1].
						"&size=32";
					$comment = 
						"<div class=\"CommentHeader\">".
						$parts[2]." - <span class=\"DateTime\">".date("j.n.Y, H:i:s", $parts[0])."</span><br/>".
						"</div><div class=\"CommentContent\"><img src=\"".$gravatar_url."\" class=\"Avatar\" />".
						$parts[3]."</div><br/><br/>";
				}
				array_push($comments, $comment);
			}
			fclose($handle);
		}
		
		$comments = array_reverse($comments); // Reverse
		$commentCount = count($comments); // Get comments count
		
		if($commentCount == 0)
		{
			echo $lang["comment_none"]."<br/>";
		}
		else
		{
			// Print paging on top of the page
			PrintPaging($lang, $timeStamp, $commentCount, $page);
			
			// Print comments
			for($i = $page; $i < ($page + commentsPerPage); ++$i)
			{
				if($i >= $commentCount)
				{
					break;
				}
				echo $comments[$i];
			}
			
			// Print paging on bottom of the page
			PrintPaging($lang, $timeStamp, $commentCount, $page);
		}
	}
function CommentForm($lang,$timeStamp,$name,$email,$content) {
	if($_COOKIE["name"]!="" AND !($_SESSION["auth"])) {
		$name = $_COOKIE["name"];
	} elseif($_SESSION["auth"]) {
		$name = name;
	}
	if($_COOKIE["email"]!="" AND !($_SESSION["auth"])) {
		$email = $_COOKIE["email"];
	} elseif($_SESSION["auth"]) {
		$email = email;
	}
	if(minimalisticCommentsForm) {
		$text='<form method="post">
						<table class="commentsmini">
							<tr>
								<td><input class="TextBoxMinimalistic" type="text" id="name" '.($name=="" ? 'onfocus="delText(\'name\')"' : '').' name="name" value="'.($name!="" ? $name : $lang["comment_nick"]).'"></td>
								<td><input class="TextBoxMinimalistic" type="text" name="email" id="email" '.($email=="" ? 'onfocus="delText(\'email\')"' : '').' value="'.($email!="" ? $email : $lang["comment_email"]).'"></td>
								<td><input class="TextBox" type="text" id="content" onfocus="delText(\'content\')" name="content" value="'.(isset($content) ? $content : $lang["comment_text"]).'"></td>
								<td><input type="checkbox" name="save_cookie" value="1" checked"><span class="note SmallText">'.$lang["save_name"].'</span></td>
								<td><input type="submit" name="submit" value="'.$lang["comment_add"].'"></td>
								<td><span><a title="'.$lang["comment_private"].' '.$lang["comment_length"].' '.$lang["comment_note"].'">?</a></span></td>
							</tr>
						</table>';
		return $text;
	}
$text.='<form method="post">
					<table class="comments">
						<input type="hidden" name="timestamp" value="'.$timeStamp.'">
					<tr>
						<td><span>'.$lang["comment_nick"].':</span></td>
						<td><input class="TextBox" type="text" name="name" value="'.$name.'"></td>
					</tr>	
					<tr>
						<td>
							<span>'.$lang["comment_email"].': <a title="'.$lang["comment_private"].'">?</a></span>
						</td>
						<td><input class="TextBox" type="text" name="email" value="'.$email.'"></td>
					</tr>
					<tr>
						<td><span>'.$lang["comment_text"].': <a title="'.$lang["comment_length"].'">?</a></span></td>
						<td><textarea class="TextBox" name="content">'.$content.'</textarea></td>
					</tr>
					<tr>
						<td><input type="checkbox" name="save_cookie" value="1" checked><span class="note SmallText">'.$lang["save_name"].'</span></td>
						<td><input type="submit" name="submit" value="'.$lang["comment_add"].'"><div class="note SmallText">'.$lang["comment_note"].'</div></td>
					</tr>
				</table>
			</form>';
	return $text;
}
?>
