<?php

namespace Ka\Http2Push\Hooks;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 Kevin Lieser <info@ka-mediendesign.de>, www.ka-mediendesign.de
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * HTTP/2 Push
 *
 * @author		Kevin Lieser <info@ka-mediendesign.de>
 * @package		TYPO3
 * @subpackage	http2_push
 */
class ContentPostProcessor {

    /**
     * @var array
     */
	protected $headerLinkContent = [];

	public function renderHttp2PushHeader()
    {
		$this->readFilesFromContent();
		header('Link: ' . $this->checkHeaderLengthAndReturnImplodedArray($this->headerLinkContent));
    }
	
	public function readFilesFromContent()
    {
		preg_match_all(
		    '/href="([^"]+\.css[^"]*)"|src="([^"]+\.js[^"]*)"|src="([^"]+\.jpg[^"]*)"|src="([^"]+\.png[^"]*)"/',
            $GLOBALS['TSFE']->content,
            $matches
        );
		$result = array_filter(array_merge($matches[1], $matches[2], $matches[3], $matches[4]));
		foreach ($result as $file)
		{
			if ($this->checkFileForInternal($file)) {
                $this->headerLinkContent[] = sprintf('<%s>; %s', $file, $this->getConfigForFiletype($file));
			}
		}
	}

    /**
     * @param string $file
     * @return bool
     */
	public function checkFileForInternal($file)
    {
		$components = parse_url($file);
		return !isset($components['host']) && !isset($components['scheme']);
	}

    /**
     * @param string $file
     * @return string
     */
	public function getConfigForFiletype($file)
    {
        $path = parse_url($file, PHP_URL_PATH);
        $parts = GeneralUtility::trimExplode('.', $path, true);
		$extension = array_pop($parts);
		if ($extension === 'gzip') {
		    $extension = array_pop($parts);
        }
		switch ($extension) {
			case 'css':
				return 'rel=preload; as=stylesheet';
				break;
			case 'js':
				return 'rel=preload; as=script';
				break;
			case 'png':
			case 'jpg':
				return 'rel=preload; as=image';
			default:
				return 'rel=preload';
		}
	}

    /**
     * @param array $items
     * @return string
     */
	public function checkHeaderLengthAndReturnImplodedArray(array $items)
    {
		$limit = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['http2_push'])['webserverHeaderLengthLimit'];
		if (empty($limit)) {
		    $limit = 8190;
		}
		$full = implode(', ', $items);
		if (strlen($full) < $limit) {
            return $full;
        }
        $short = substr($full, 0, $limit);
        return substr($short, 0, strrpos($short, ','));
	}
		
	public function renderAll()
    {
		if (!$GLOBALS['TSFE']->isINTincScript()) {
			$this->renderHttp2PushHeader();	
		}
	}
	
	public function renderOutput()
    {
		if ($GLOBALS['TSFE']->isINTincScript()) {
			$this->renderHttp2PushHeader();
		}
	}

}




