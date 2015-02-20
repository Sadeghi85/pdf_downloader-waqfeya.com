<?php

function prepareHTML($htmlContent) {
	
	$tempContent = @iconv('UTF-8', 'UTF-8//IGNORE', $htmlContent);

	if ($tempContent and mb_detect_encoding($htmlContent, 'UTF-8', true)) {
		$htmlContent = $tempContent;
	} else {
		$tempContent = @iconv('UTF-16', 'UTF-8//IGNORE', $htmlContent);
		
		if ($tempContent and mb_detect_encoding($htmlContent, 'UTF-16', true)) {
			$htmlContent = $tempContent;
		} else {
			$htmlContent = @iconv('CP1256', 'UTF-8//IGNORE', $htmlContent);
		}
	}

	$htmlContent =  preg_replace('#[[:space:]]+#', ' ',
			preg_replace('#\p{Cf}+#u', pack('H*', 'e2808c'),
				str_replace(pack('H*', 'c2a0'), '',
					str_replace(pack('H*', 'efbbbf'), '', $htmlContent)
				)
			)
		);

	$config = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config);
	$htmlContent = $purifier->purify($htmlContent);
	
	return $htmlContent;
}

function getDOMFromContent ($htmlContent) {
	$dom = new DOMDocument;
	$dom->loadHTML('<?xml encoding="UTF-8">' . $htmlContent); // dirty hack

	foreach ($dom->childNodes as $item)
		if ($item->nodeType == XML_PI_NODE)
			$dom->removeChild($item); // remove hack
	$dom->encoding = 'UTF-8'; // insert proper
	
	return $dom;
}