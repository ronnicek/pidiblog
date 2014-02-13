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

// ini_set('display_errors', '1');
// error_reporting(E_ERROR);
header("Content-type: application/rss+xml");
define(URL, "http://".$_SERVER["HTTP_HOST"].dirname($_SERVER['PHP_SELF'])); // Define server path
require_once("./rssSeason.php");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
	<title><? echo blogname; ?></title>
	<link><? echo URL; ?></link>
	<description>Pidiblog - <? echo blogname; ?></description>
	<language>cs</language>
	<atom:link href="http://<? echo $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]; ?>" rel="self" type="application/rss+xml" />
<?php
	if(($dir = opendir("./txts")))
	{
		while(($file = readdir($dir)))
		{
			if($file == "." || $file == "..") // Skip the unwanted stuff ..
			{
				continue;
			}
			// Get data (without extension)
			$data = explode("-", substr(basename($file), 0, strlen(basename($file)) - 4));
			$size = count($data);
			if($size != 2) // Something smells here..
			{
				continue;
			}
			$season = new RssSeason($data[0], $data[1]); // Add new season
			$season->Write(); // And write it..
		}
	}
?>
</channel>
</rss>
