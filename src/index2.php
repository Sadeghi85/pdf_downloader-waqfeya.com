<?php

require('HTMLPurifier.standalone.php');
require('utils.php');

$siteUrl = 'http://waqfeya.com/';

$categories = unserialize(file_get_contents('categories.dat'));
$books = [];

foreach ($categories as $categoryTitle => $categoryUrl) {
	
	$categoryPageContent = prepareHTML(file_get_contents(sprintf('%s/%s', $siteUrl, $categoryUrl)));
	
	if (false === stripos($categoryPageContent, 'book.php')) { continue; }
	
	$st = 15;
	
	while (false !== stripos($tempCategoryPageContent = prepareHTML(file_get_contents(sprintf('%s%s&st=%s', $siteUrl, $categoryUrl, $st))), 'book.php')) {
		
		$categoryPageContent .= $tempCategoryPageContent;
		$st += 15;
	}
	
	$dom = getDOMFromContent($categoryPageContent);
	$xpath = new DOMXpath($dom);
	$xpathQuery = sprintf('//%s[starts-with(@%s, \'%s\')]', 'a', 'href', 'book.php');
	$booksNodeList = $xpath->query($xpathQuery, $dom);

	foreach ($booksNodeList as $node) {
		$books[$categoryTitle][base64_encode($node->textContent)] = $node->getAttribute('href');
		echo $node->getAttribute('href')."\n";
	}
	
	//break;
}

file_put_contents('books.dat', serialize($books));
