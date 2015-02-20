<?php

$siteUrl = 'http://waqfeya.com/';

$categories = [];

$homepageContent = prepareHTML(file_get_contents($siteUrl));

$dom = getDOMFromContent($homepageContent);
$xpath = new DOMXpath($dom);
$xpathQuery = sprintf('//%s[starts-with(@%s, \'%s\')]', 'a', 'href', 'category.php');
$categoriesNodeList = $xpath->query($xpathQuery, $dom);

foreach ($categoriesNodeList as $node) {

	$categories[base64_encode($node->textContent)] = $node->getAttribute('href');
}

file_put_contents('categories.dat', serialize($categories));
