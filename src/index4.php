<?php

require('HTMLPurifier.standalone.php');
require('utils.php');

$siteUrl = 'http://waqfeya.com/';
$rootFolder = 'waqfeya.com';
$sanityRegex = '[^\p{L}\p{M}\p{N}\p{Pe}\p{Ps}\p{Pd}\p{Pc} ]';

if ( ! file_exists($rootFolder)) {
	mkdir($rootFolder);
}

$allVolumes = unserialize(file_get_contents('volumes.dat'));
$num = 0;

foreach ($allVolumes as $categoryTitle => $books) {
	
	$categoryTitle = trim(preg_replace(sprintf('#%s#u', $sanityRegex), '_', base64_decode($categoryTitle)));
	
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
			
			if ($title == 'details') {
				file_put_contents(sprintf('wfio://%s\\%s\\%s\\%s', $rootFolder, $categoryTitle, $bookTitle, 'details.txt'), base64_decode($target));
			} else {
				if ( ! file_exists(sprintf('wfio://%s\\%s\\%s\\%s', $rootFolder, $categoryTitle, $bookTitle, $title))) {
					mkdir(sprintf('wfio://%s\\%s\\%s\\%s', $rootFolder, $categoryTitle, $bookTitle, $title));
				}
				
				file_put_contents(sprintf('wfio://%s\\%s\\%s\\%s\\%s', $rootFolder, $categoryTitle, $bookTitle, $title, preg_replace('#.+/(.+)#', '$1', $target)), fopen($target, 'r'));
				
				echo ++$num."\n";
			}
		}
		
		//break 2;
	}
}
