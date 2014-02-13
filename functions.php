<?php
// pidiblog - minimalistic blog (txt version)
// Copyright (C) 2008  Jindrich Skacel
//
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, either version 3 of the License, or
//  (at your option) any later version.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.

// this function 
function Actions($lang) {
	switch($_GET["menu"]) {
		// if login => show login form :)
		case "login":
			$text.='<div class="actions center">
							<form action="#" method="post">
								'.$lang["form_password"].': <input type="password" name="password" size="10">
								<input type="checkbox" name="permanent" value="1"><small>Permanent</small>&nbsp;&nbsp;<input type="submit" name="submit" value="'.$lang["submit_login"].'">
							</form>
						</div>';
		break;
		case "api":
			if(api=="1") {
				// check api code
				if(apiCode==$_GET["apiCode"]) {
					// check password
					if(md5(password)==$_GET["password"]) {
						// check text
						$text=$_GET["text"];
						if(strlen(str_replace(" ","",$text))!=0) {
							if(@Insert($lang,1,$text)) {
								echo "0"; die; // OK! :-)
							} else {
								echo "4"; die; // Error in inserting message, please try from web (bad permissions for txts/ folder etc)
							}
						} else {
							echo "3"; die; // Message is empty
						}
					} else {
						echo "2"; die; // Bad password
					}
				} else {
					echo "1"; die; // No API key!
				}
			}
		break;	
		default:
			// if logged
			if($_SESSION["auth"]) {
				// show form to add message :)
				$message = htmlspecialchars($_POST["message"]);

				$text.='<div class="actions center">
								<form action="#" method="post">
									<input name="message" maxlength="150" size="42" value="'.$message.'">
									<input type="submit" name="submit" value="'.$lang["submit_preview"].'">
									<input type="submit" name="submit" value="'.$lang["submit_insert"].'">
								</form>
							</div>';
			}
		break;
	}
	return $text;
}



// function to show about page :)
function About($lang) {
	$file = "txts/about.txt";
	// load txt/about.txt file
	$load = @file($file);
	if(!$load) {
		$text.=$lang["message_cantload"].' ('.$file.')';
		return $text;
	}
	while(count($load)>0) {
		$line=array_shift($load);
		$line=str_replace("<?","&lt;?",$line);
		$line=str_replace("?>","?&gt;",$line);
		$line=str_replace("\n","<br>",$line);
		$text.='<div class="text">'.$line.'</div>';
	}
	// for nice html source code :)
	$text.="\n\t\t\t";
	$text.='<div id="copy">Powered by <a href="http://code.google.com/p/pidiblog">pidiblog</a>.</div>';
	$text.="\n";
	return $text;
}

// function to show messages
function Messages($action="",$lang,$msg,$err) {
	switch($action) {
		case "preview":
			if(!($_SESSION["auth"])) { Header('Location: ?err=8'); die; }
			$text.='<div class="messages center">'.$_POST[message].'</div>';
		break;
		default:
			// if some msg or err show it
			if($_GET["msg"] OR $_GET["err"]) {
				if($_GET["msg"]) {
					$text.='<div class="messages center">'.$msg[$_GET[msg]].'</div>';
				} else {
					$text.='<div class="messages center">'.$err[$_GET[err]].'</div>';
				}
			}
		break;
	}
	return $text;
}


// Load txt file and parse :)
function Show($lang) {
	// name of txt
	if(str_replace(".","",$_GET["date"])) {
		$file = "txts/".$_GET['date'].".txt";
	} else {
		$file = "txts/".Date('Y-m').".txt";
	}

	// file exists and have zero size?
	if(!(file_exists($file)) OR filesize($file)=="0") {
		$text.=$lang["message_emptyblog"];
		return $text;
	}
		// load txt
		$load = @file($file);
			// error in loading file?
			if(!($load)) { 
				$text.=$lang["message_cantload"].' ('.$file.')'; 
				return $text;
			}
		
			// load lines
			while(count($load)>0) {
				$line=array_shift($load);
				// count lines :)
				$count+=1;
				// if first char isn't "-" => it's date
				if(substr($line,0,1)!="-") {
					// is first line? no? if no we must close div
					if($count!="1") {
						// for nice html source code :)
						$text.="\n\t\t\t\t</div>\n";
						$text.="\n\t\t\t";
					}
					// replace \n
					$date=str_replace("\n","",$line);
					if(fullDate) {
						// empty..
						$showdate="";
						// explode
						$explodeDate=explode(".",$date);
						// day
						if(substr($explodeDate[0],0,1)=="0") {
							$showdate.=substr($explodeDate[0],1,2);
						} else {
							$showdate.=$explodeDate[0];
						}
						// month
							if(substr($explodeDate[1],0,1)=="0") {
								$explodeDate[1]=str_replace("0","",$explodeDate[1]);
							}
							$showdate.=$lang["months"][$explodeDate[1]];
						// and year
							$showdate.=' '.$explodeDate[2];
					} else {
						$showdate=$date;
					}
					$text.='<div class="date">'.$showdate.'</div>';
					// explode date for unixtime
					$date = explode(".",$date);
					// for nice html source code
					$text.="\n\t\t\t\t<div class=\"text\">";
				// else its post! :-)
				} else {
					// cut first char "-"
					$post = substr($line,1);
					// load time
					$time = substr($post,0,8);
					if($_GET["debug"]=="1") { echo '(Loaded time: '.$time.''; }
					// explode time for unixtime
					$time = explode(":",$time);
					// create unixtime
					if($_GET["debug"]=="1") { echo ' | Exploded time: '.$time.''; }
					$unixtime = mktime($time[0], $time[1], $time[2], $date[1], $date[0], $date[2]);
					if($_GET["debug"]=="1") { echo ' | Unixtime: '.$unixtime.''; }
					// for nice html source code
$text.="\n\t\t\t\t\t";
					$text.="<div>";
					// comment's link
					if(enableComments) {
						$hrefDate = IsSet($_GET['date'])? "&date=".$_GET["date"] : "";
						$text.='<a href="?cmt='.$unixtime.$hrefDate.'" class="CommentsImage">'.CommentsCount($unixtime).'</a>';

					}
					// show time :)
					$text.='<div class="time"><a name="'.$unixtime.'" href="#'.$unixtime.'">'.$time[0].':'.$time[1].'</a>&nbsp;&ndash;</div>';
					// cut the post and replace \n
					$post = str_replace("\n","",substr($post,9));
					// replace links
					$post = preg_replace('~\[a\](http://|https://)?(.*)\|(.*)\[/a\]~', '<a href="\\1\\2">\\3</a>', $post);
					$post = preg_replace('~\[a\](http://|https://)?(.*)\[/a\]~', '<a href="\\1\\2">\\2</a>', $post);
					// hack the '
					$post=str_replace("\'","'",$post);
					$post=str_replace('\"','"',$post);
					$text.='<span class="text2">'.$post.'</span><div class="cleaner"></div></div>';
					// for nice html source code
					$text.="\n";
			}
		}
		// close last div
		$text.="\t\t\t</div>\n";
	return $text;
}

// pages
function Pages($lang,$side) {
		// define
		$date = ""; // use to save date (ex. 2008-10)
		$open = ""; // use to open dir
		$file = ""; // use to filename from dir
		$filenames = array(); // array to list of files
		$filename = ""; // part of array (ex. filename = $filenames[1])
		

		$date=$_GET["date"];
		if(isset($date)) {
			$date=explode("-",$date);
		} else {
			$date[0]=Date("Y");
			$date[1]=Date("m");
		}
		// read dir
		$open = dir("./txts/");
		// save dir list to array
		while (false !== ($file = $open->read())) {
			// add only 11 lenght filenames (ex. 2008-10.txt)
			if(strlen($file)=="11") {
				// cut off .txt
				$file = substr($file,0,-4);
				array_push($filenames,$file);
			}
		}
		$open->close();


		rsort($filenames);
		$search=array_search($date[0].'-'.$date[1], $filenames);
		if($_GET["debug"]=="1") { echo 'Search: "'.$search.'" - filenames count: '.count($filenames).' ('.$date[0].'-'.$date[1].')'; }
		// prev
		if($side=="left" AND $search!=count($filenames) AND isset($search) AND $search!=count($filenames)-1) {
			if($date[0]==Date("Y") AND $date[1]==Date("m") AND !is_numeric($search)) {
				$search=0;
			} else {
				$search+=1;
			}
			$text.='<a href="?date='.$filenames[$search].'">'.$lang["prev_month"].'</a>';
		} else { $text.="&nbsp;"; }

		// next
		if($side=="right" AND $search!=0) {
			$search-=1;
			if($filenames[$search]=="".Date(Y)."-".Date(m)."") {
				$text.='<a href="?">'.$lang["next_month"].'</a>';
			} else {
				$text.='<a href="?date='.$filenames[$search].'">'.$lang["next_month"].'</a>';
			}
				
			
		} else { $text.="&nbsp;"; }


		return $text;
}


// insert message to the file :)
function Insert($lang,$api=0,$message="") {
	if(!($_SESSION["auth"]) AND $api==0) { Header('Location: ?err=8'); die; }
	if($message) { $_POST["message"]=$message; }
	$folder = "txts/";
	$file = Date('Y-m').".txt";
	$path = $folder."/".$file;
	// folder exists?
	if(!(is_dir($folder))) {
			// if error -> redirect
			if($api) {
				return false;
			} else {
				Header('Location: ?err=3');
			}
	}

	// file exists?
	if(file_exists($path)) {
		if(!(is_writable($path))) {
			if($api) {
				return false;
			} else {
				Header('Location: ?err=4');
			}
		}
	} else {
		// create the file :)
		$create = touch($path);
		chmod($path,0777);
		if(!($create)) {
			// if error -> redirect
			if($api) {
				return false;
			} else {
				Header('Location: ?err=3');
			}
		}
	}
	// read first line
	$open2 = fopen($path, "r");
		$firstline = fgets($open2);
	fclose($open2);
	$open3 = fopen($path, "r");
		if(filesize($path)!="0") {
			$contents = fread($open3, filesize($path));
		}
	fclose($open3);
	// load file :)
	$open = fopen($path, "w+");
	$today = Date("d.m.Y")."\n";
	$hour = Date("H:i:s");
	$message=str_replace(">","&gt;",$_POST["message"]);
	$message=str_replace("<","&lt;",$message);
	$message=str_replace('"','&#34;',$message);
	$string = "-".$hour."|".$message."\n";
	$explode = explode($today,$contents);
	$finalstring = $explode[0] . $today . $string . $explode[1];
	$finalstring2 = $today . $string . $explode[0] . $explode[1];
	/*
		// This for testing only :-) 
		echo "Text: ".$contents."<br>";
		echo "Explode 0: ".$explode[0]."<br>";
		echo "Explode 1: ".$explode[1]."<br>";
		echo "Final String: ".$finalstring."<br>";
		die;
	*/
	if($firstline==$today) {
		$insert = fwrite($open,$finalstring);
	} else {
		$insert = fwrite($open,$finalstring2);
	}
	// echo $insert; die; // testing only :)
	fclose($open);
	if($insert) {
		if($api) {
			return true;
		} else {
			Header('Location: ?msg=2');
		}
	} else {
		if($api) {
			return false;
		} else {
			Header('Location: ?err=7');
		}
	}
}

// function return count of comments
function CommentsCount($timeStamp) {
	if($_GET["debug"]=="1") { echo ' | Comments count: '.$timeStamp.')<br>'; }
	define("COMMENTS_DIR", "./txts/");
	$path = COMMENTS_DIR.$timeStamp.".txt"; // Path to the book
	// Define
	$commentCount=0;	// Open book (won't show any warnings)
	if(($handle = @fopen($path, "r")))
	{
		while(($line = fgets($handle))) // Read 'til end
		{
			$commentCount+=1;
		}
		fclose($handle);
	}
	if($commentCount=="0") {
		return '0';
	} else {
		return $commentCount;
	}
}
?>
