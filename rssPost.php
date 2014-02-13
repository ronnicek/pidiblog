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

//
// Class for single pidiblog post
//
class RssPost
{

	// Class members
	var $m_text;
	var $m_dateTime;
	var $m_link;
	var $m_title;
	
	//
	// Constructor
	//
	function RssPost($time, $date, $text)
	{
		$hr = $min = $sec = 0; // Initilaze variables
		// Get hour, minute
		sscanf($time, "%d:%d:%d", $hr, $min, $sec);
		$dates = explode(".", $date); // Explode date string to get month, day and year
		// Create unix time for link
		$unixTime = mktime($hr, $min, $sec, $dates[1], $dates[0], $dates[2]);
		// Now can create a valid RFC822 date 'n time
		$this->m_dateTime = date("D, d M Y H:i:s O", $unixTime);		
		$this->m_text = $text; // Save pidipost..
		$this->m_title = mb_substr($text, 0, (mb_strlen($text,"utf-8") / 1.2),"utf-8")."..."; // Preview to title..
		$url = URL;
		$this->m_link = URL.($url[strlen($url) - 1] == "/"? "#" : "/#").$unixTime;
	}
	
	//
	// Writes itself as an RSS item
	//
	function Write()
	{
		echo "\t<item>\n".
			"\t\t<title>".$this->m_title."</title>\n".
			"\t\t<link>".$this->m_link."</link>\n".
			"\t\t<description>".$this->m_text."</description>\n".
			"\t\t<pubDate>".$this->m_dateTime."</pubDate>\n".
			"\t\t<guid>".$this->m_link."</guid>\n".
			"\t</item>\n";
	}
}

?>
