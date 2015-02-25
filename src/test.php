<?php

require('HTMLPurifier.standalone.php');
require('utils.php');

$siteUrl = 'http://waqfeya.com/';

$allBooks = unserialize(file_get_contents('books.dat'));
$volumes = [];

foreach ($allBooks as $categoryTitle => $books) {
	
	foreach ($books as $bookTitle => $bookUrl) {
		//usleep(500000);
		
		if ( ! in_array($bookUrl, ['book.php?bid=5810', 'book.php?bid=7664', 'book.php?bid=7666', 'book.php?bid=7657', 'book.php?bid=7660', 'book.php?bid=8929', 'book.php?bid=5046', 'book.php?bid=594'])) { continue; }
		
		$bookPageContent = prepareHTML(file_get_contents(sprintf('%s/%s', $siteUrl, $bookUrl)));
		
		if (false === stripos($bookPageContent, '.pdf')) { continue; }
		
		$dom = getDOMFromContent($bookPageContent);
		$xpath = new DOMXpath($dom);
		
		// this is a xpath 1.0 reformulation of ends-with()
		// http://stackoverflow.com/a/5435487
		$xpathQuery = sprintf('//%s[contains(@%s, \'%s\') and \'%s\' = substring(@%s, string-length(@%s) - string-length(\'%s\') + 1)]', 'a', 'href', 'archive.org/download/', '.pdf', 'href', 'href', '.pdf');
		$volumesNodeList = $xpath->query($xpathQuery, $dom);

		foreach ($volumesNodeList as $node) {
			$volumes[$categoryTitle][$bookTitle][base64_encode($node->textContent)] = $node->getAttribute('href');
			echo $node->getAttribute('href')."\n";
		}
		
		$xpathQuery = sprintf('//%s[@%s=\'%s\']', 'td', 'class', 'row1');
		$tdNodeList = $xpath->query($xpathQuery, $dom);

		foreach ($tdNodeList as $node) {
			
			$html = $dom->saveHTML($node);
			if (preg_match('#archive.org/download/[^<>]*?\.pdf#i', $html)) {
				$volumes[$categoryTitle][$bookTitle][base64_encode('details')] = base64_encode($html);
			}
		}
	}
	
	//break;
}

//var_dump($volumes);
file_put_contents('volumes1.dat', serialize($volumes));
