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
require_once("./rssPost.php");

//
// Class for RSS season (that means bunch of pidiblog posts 
// for current month and year)
//
class RssSeason
{
	
	// Class members
	var $m_posts; // Array of all pidiblog posts
	var $m_year; // Year
	var $m_month; // Month
	var $m_date; // Combined year & month
	
	//
	// Constructor
	//
	function RssSeason($year, $month)
	{
		$this->m_year = intval($year);
		$this->m_month = intval($month);
		
		$rssSeason = ("./txts/".$year."-".$month.".txt"); // Combine year and month to get filename
		if(($handle = fopen($rssSeason, "r"))) // Lets open pidiblog season
		{ // Success?
			$this->m_date = fgets($handle); // Gets date
			$this->m_date = mb_substr($this->m_date, 0, mb_strlen($this->m_date,"utf-8") - 1,"utf-8"); // Get rid of dash..
			$this->m_posts = array(); // Blah?
			while(($line = fgets($handle))) // Read the rest of content
			{
				$p = strpos($line, "|"); // Cannot use here explode 'cos blackened is a biiiig h4x0r :D
				if(!$p) // Not found
				{
					// It's a date
					$this->m_date = $line;
					continue;
				}
				// Get post time (combine with season date)
				$postTime = mb_substr($line, 1, $p - 1,"utf-8");
				++$p; // Increment by one
				$postText = mb_substr($line, $p, mb_strlen($line,"utf-8") - $p - 1,"utf-8"); // Get post text
				// Push to the array
				array_push($this->m_posts, new RssPost($postTime, $this->m_date, $postText));
			}
			// Don't forget to close the file..
			fclose($handle);
			return;
		}
		echo "Brutal error occured. xP";
		die; // uuuhahh.. we're dead!
	}
	
	//
	// Writes all posts as RSS
	//
	function Write()
	{
		foreach($this->m_posts as $post)
		{
			$post->Write();
		}
	}
	
}

?>
