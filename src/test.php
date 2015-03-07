<?php

require('HTMLPurifier.standalone.php');
require('utils.php');


$sanityRegex = '[^\p{L}\p{M}\p{N}\p{Pe}\p{Ps}\p{Pd}\p{Pc} ]';

$allVolumes = unserialize(file_get_contents('volumes.dat'));
$num = 0;

$notFoundReport = 'notfound.csv';
$notFoundItems = explode("\r\n", file_get_contents('notfoundurllist.lst'));

foreach ($notFoundItems as $notFoundItem) {
	foreach ($allVolumes as $categoryTitle => $books) {
		
		$categoryTitle = trim(preg_replace(sprintf('#%s#u', $sanityRegex), '_', base64_decode($categoryTitle)));
		
		foreach ($books as $bookTitle => $volumes) {
			$bookTitle = trim(preg_replace(sprintf('#%s#u', $sanityRegex), '_', base64_decode($bookTitle)));
			
			foreach ($volumes as $title => $target) {
				$title = trim(preg_replace(sprintf('#%s#u', $sanityRegex), '_', base64_decode($title)));

				if ($target === $notFoundItem) {
					file_put_contents($notFoundReport, sprintf('%s,%s,%s,%s%s', $categoryTitle, $bookTitle, $title, $target, "\r\n"), FILE_APPEND);
					
					echo ++$num."\n";
				}
			}
			
			//break 2;
		}
	}
}
