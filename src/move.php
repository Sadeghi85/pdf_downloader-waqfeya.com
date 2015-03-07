<?php


if ( ! file_exists('download')) {
	mkdir('download');
}
	
if ( ! file_exists('structure')) {
	mkdir('structure');
}
	
$downloadFolder = realpath('download');
$structureFolder = realpath('structure');
$sanityRegex = '[^\p{L}\p{M}\p{N}\p{Pe}\p{Ps}\p{Pd}\p{Pc} ]';
//$thisCategoryTitle = '218.1 التزكية والأخلاق والآداب';
$thisCategoryTitle = file_get_contents($argv[1]);
$thisCategoryTitle = trim(preg_replace(sprintf('#%s#u', $sanityRegex), '_', $thisCategoryTitle));
$allVolumes = unserialize(file_get_contents('volumes.dat'));
$num = 0;

function flatten($array)
{
	$return = [];
	array_walk_recursive($array, function($x) use (&$return) { $return[] = $x; });
	return $return;
}

	

$Directory = new DirectoryIterator($downloadFolder);
$Iterator = new IteratorIterator($Directory);
$Items = new RegexIterator($Iterator, '/^.+\.log$/i', RegexIterator::GET_MATCH);

$downlodedItems = flatten(iterator_to_array($Items));





foreach ($allVolumes as $categoryTitle => $books) {
	
	$categoryTitle = trim(preg_replace(sprintf('#%s#u', $sanityRegex), '_', base64_decode($categoryTitle)));
	
	if ($thisCategoryTitle != $categoryTitle) {	continue; }
	
	if ( ! file_exists(sprintf('wfio://%s\\%s', $structureFolder, $categoryTitle))) {
		mkdir(sprintf('wfio://%s\\%s', $structureFolder, $categoryTitle));
	}
	
	foreach ($books as $bookTitle => $volumes) {
		$bookTitle = trim(preg_replace(sprintf('#%s#u', $sanityRegex), '_', base64_decode($bookTitle)));
		
		if ( ! file_exists(sprintf('wfio://%s\\%s\\%s', $structureFolder, $categoryTitle, $bookTitle))) {
			mkdir(sprintf('wfio://%s\\%s\\%s', $structureFolder, $categoryTitle, $bookTitle));
		}
		
		foreach ($volumes as $title => $target) {
			$title = trim(preg_replace(sprintf('#%s#u', $sanityRegex), '_', base64_decode($title)));
			$target = str_ireplace('https://', 'http://', $target);
			
			if ($title == 'details') {
				file_put_contents(sprintf('wfio://%s\\%s\\%s\\%s', $structureFolder, $categoryTitle, $bookTitle, 'details.txt'), base64_decode($target));
			} else {
				if ( ! file_exists(sprintf('wfio://%s\\%s\\%s\\%s', $structureFolder, $categoryTitle, $bookTitle, $title))) {
					mkdir(sprintf('wfio://%s\\%s\\%s\\%s', $structureFolder, $categoryTitle, $bookTitle, $title));
				}
				
				//file_put_contents(sprintf('wfio://%s\\%s\\%s\\%s\\%s', $structureFolder, $categoryTitle, $bookTitle, $title, preg_replace('#.+/(.+)#', '$1', $target)), fopen($target, 'r'));
				
				
				foreach ($downlodedItems as $item) {
					$logFile = sprintf('%s\\%s', $downloadFolder, $item);
					$downlodedUrl = preg_replace('#.*URL:([^\r\n]+).+#isu', '$1', @file_get_contents($logFile));
					$downlodedName = preg_replace('#.*Name:([^\r\n]+).+#isu', '$1', @file_get_contents($logFile));
					//$oldFile = sprintf('wfio://%s\\%s', $downloadFolder, str_replace('.log', '', $item));
					//$newFile = sprintf('wfio://%s\\%s\\%s\\%s\\%s', $structureFolder, $categoryTitle, $bookTitle, $title, $downlodedName);
					
					$now = microtime(true);
					$oldFile = sprintf('%s\\%s', $downloadFolder, str_replace('.log', '', $item));
					$ren1File = sprintf('%s\\%s', $downloadFolder, $now.'.pdf');
					$ren2File = sprintf('wfio://%s\\%s', $downloadFolder, $now.'.pdf');
					$newNewFile = sprintf('wfio://%s\\%s\\%s\\%s\\%s', $structureFolder, $categoryTitle, $bookTitle, $title, $downlodedName);
					
					if ($downlodedUrl == $target) {
						//rename($oldFile, $newFile);
						
						rename($oldFile, $ren1File);
						rename($ren2File, $newNewFile);
						unlink(sprintf('%s\\%s', $downloadFolder, $item));
					
					}
				}
				
				
				echo ++$num.': '.$target."\n";
			}
		}
		
		//break 2;
	}
}


