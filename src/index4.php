<?php

$siteUrl = 'http://waqfeya.com/';
$rootFolder = 'waqfeya.com';
$sanityRegex = '[^\p{L}\p{M}\p{N}\p{Pe}\p{Ps}\p{Pd}\p{Pc}. ]';

if ( ! file_exists($rootFolder)) {
	mkdir($rootFolder);
}

$allVolumes = unserialize(file_get_contents('volumes.dat'));

foreach ($allVolumes as $categoryTitle => $books) {
	
	$categoryTitle = preg_replace(sprintf('#%s#u', $sanityRegex), '_', base64_decode($categoryTitle));
	
	if ( ! file_exists(sprintf('%s\\%s', $rootFolder, $categoryTitle))) {
		mkdir(sprintf('%s\\%s', $rootFolder, $categoryTitle));
	}
	
	foreach ($books as $bookTitle => $volumes) {
		$bookTitle = preg_replace(sprintf('#%s#u', $sanityRegex), '_', base64_decode($bookTitle));
		
		if ( ! file_exists(sprintf('%s\\%s\\%s', $rootFolder, $categoryTitle, $bookTitle))) {
			mkdir(sprintf('%s\\%s\\%s', $rootFolder, $categoryTitle, $bookTitle));
		}
		
		foreach ($volumes as $title => $target) {
			$title = preg_replace(sprintf('#%s#u', $sanityRegex), '_', base64_decode($title));
			
			if ($title == 'details') {
				file_put_contents(sprintf('%s\\%s\\%s\\%s', $rootFolder, $categoryTitle, $bookTitle, 'details.txt'), base64_decode($target));
			} else {
				if ( ! file_exists(sprintf('%s\\%s\\%s\\%s', $rootFolder, $categoryTitle, $bookTitle, $title))) {
					mkdir(sprintf('%s\\%s\\%s\\%s', $rootFolder, $categoryTitle, $bookTitle, $title));
				}
				
				file_put_contents(sprintf('%s\\%s\\%s\\%s\\%s', $rootFolder, $categoryTitle, $bookTitle, $title, preg_replace('#.+/(.+)#', '$1', $target)), fopen($target, r));
			}
			
		}
		
		
	}
	
	break;
	
}

//var_dump($volumes);
file_put_contents('volumes.dat', serialize($volumes));
