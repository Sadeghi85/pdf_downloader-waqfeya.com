<?php

require('HTMLPurifier.standalone.php');
require('utils.php');

//$siteUrl = 'http://waqfeya.com/';
$rootFolder = 'structure-test';
$sanityRegex = '[^\p{L}\p{M}\p{N}\p{Pe}\p{Ps}\p{Pd}\p{Pc} ]';
$thisCategoryTitle = '213.3 الجرح والتعديل';
$thisCategoryTitle = trim(preg_replace(sprintf('#%s#u', $sanityRegex), '_', $thisCategoryTitle));
$pdfUrlList = [];

if ( ! file_exists($rootFolder)) {
	mkdir($rootFolder);
}

$allVolumes = unserialize(file_get_contents('volumes.dat'));
$num = 0;

foreach ($allVolumes as $categoryTitle => $books) {
	
	$categoryTitle = trim(preg_replace(sprintf('#%s#u', $sanityRegex), '_', base64_decode($categoryTitle)));
	
	if ($thisCategoryTitle != $categoryTitle) {	continue; }
	
	if ( ! file_exists(sprintf('wfio://%s\\%s', $rootFolder, $categoryTitle))) {
		mkdir(sprintf('wfio://%s\\%s', $rootFolder, $categoryTitle));
	}
	
	foreach ($books as $bookTitle => $volumes) {
		$bookTitle = trim(preg_replace(sprintf('#%s#u', $sanityRegex), '_', base64_decode($bookTitle)));
		
		if ( ! file_exists(sprintf('wfio://%s\\%s\\%s', $rootFolder, $categoryTitle, $bookTitle))) {
			mkdir(sprintf('wfio://%s\\%s\\%s', $rootFolder, $categoryTitle, $bookTitle));
		}
		
		foreach ($volumes as $title => $target) {
			$title = trim(preg_replace(sprintf('#%s#u', $sanityRegex), '_', base64_decode($title)));
			$target = str_ireplace('https://', 'http://', $target);
			
			if ($title == 'details') {
				file_put_contents(sprintf('wfio://%s\\%s\\%s\\%s', $rootFolder, $categoryTitle, $bookTitle, 'details.txt'), base64_decode($target));
			} else {
				if ( ! file_exists(sprintf('wfio://%s\\%s\\%s\\%s', $rootFolder, $categoryTitle, $bookTitle, $title))) {
					mkdir(sprintf('wfio://%s\\%s\\%s\\%s', $rootFolder, $categoryTitle, $bookTitle, $title));
				}
				
				//file_put_contents(sprintf('wfio://%s\\%s\\%s\\%s\\%s', $rootFolder, $categoryTitle, $bookTitle, $title, preg_replace('#.+/(.+)#', '$1', $target)), fopen($target, 'r'));
				$pdfUrlList[] = $target;
				
				echo ++$num.': '.$target."\n";
			}
		}
		
		//break 2;
	}
}

file_put_contents('pdfurllist.lst', implode("\r\n", $pdfUrlList)."\r\n");