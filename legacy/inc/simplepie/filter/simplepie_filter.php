<?php
/**
 SimplePie Add-on to enable filtering of items based on keywords
 Copyright (c) 2008 Michael Shipley
 All rights reserved. License matches the current SimplePie license.
 Help:
 Usage:
	require_once 'simplepie.inc';
	require_once 'simplepie_filter.php';
	$feed = new SimplePie_Filter();
	$feed->set_feed_url(array('feed1','feed2'));
	$filterWords = 'clinton loves obama'; // probably wont find any matches ;)
	$mode = 'and'; // (and/or)
	$feed->set_filter($filterWords,$mode);
	$feed->init();
	$items = $feed->get_items();
	$itemsFiltered = $feed->filter($items);
	echo '<h3>Filter Results</h3>';
	if(!count($itemsFiltered))
	{
		echo '<p><strong>' , $filterWords , '</strong> not found</p>';
	}
	else
	{
		foreach($itemsFiltered as $item)
		{
			echo '<div style="margin-bottom:3em">';
			echo '<a href="',$item->get_link(),'">',$item->get_title(),'</a><br />';
			echo $item->get_date(),'<br />';
			echo $item->get_content();
			echo '</div>';
		}
	}
*/

// Extend the SimplePie class.
class SimplePie_Filter extends SimplePie
{

	/* New variable
	* @access private
	* @var array Holds all filter function data
	*/
	var $filterData = array();

	/* New function
	* @access public
	* @param string $filterWords The filter words, space delimited
	* @param string $mode 'and' = must match all filter words, 'or' only one filter word must match.
	*/
	function set_filter($filterWords,$mode='and')
	{
		$this->filterData['pattern'] = '/[^\w]+/iu';
		$this->filterData['parsedWords'] = array();
		$this->filterData['words'] = strtolower($filterWords);
		$this->filterData['mode'] = $mode;

		// parse filter words
		$words = $this->filterData['words'];
		$words = preg_replace('#[\"\']#isu','',$words); // remove quotes and apost.
		$pattern = $this->filterData['pattern'];
		$parsedWords = preg_split($pattern,$words,65,PREG_SPLIT_NO_EMPTY);

		// remove words < 3 characters
		$s = array();
		foreach($parsedWords as $word)
		{
			if(strlen($word) >= 3)
			{
				$s[] =  $word;
			}
		}
		$this->filterData['parsedWords'] = $s;
	}

	/* New function
	* @access public
	* @param string $items Array of class SimplePie_Item
	*/
	function filter($items)
	{
		$max = count($items);
		$match = array();
		for($i=0;$i<$max;$i++)
		{
			$content = $items[$i]->get_content();
			$content = strip_tags($content);
//			$content = preg_replace('#[\"\']#isu','',$content); // remove qoutes and apost
			$content = preg_replace('#[\"\']#isu',' ',$content);	// replace qoutes and apost with space
			$content = strtolower($content);

			$title = $items[$i]->get_title();
			$title = strip_tags($title);
			$title = preg_replace('#[\"\']#isu','',$title);
			$title = strtolower($title);

			$content .= ' '.$title;
			$pattern = $this->filterData['pattern'];
			$parsedContent = preg_split($pattern, $content);
			$parsedWords = $this->filterData['parsedWords'];
			$intersect = array_intersect($parsedWords,$parsedContent);
			if(count($intersect))
			{
				// if mode is 'or' then we have at least one match
				if($this->filterData['mode'] != 'and')
				{
					$match[] = $items[$i];
					continue;
				}
				// mode is 'and' so all words must match
				if(count($intersect) == count($parsedWords))
				{
					$match[] = $items[$i];
					continue;
				}
			}
		}
		return $match;
	}
}
?>