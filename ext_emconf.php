<?php

$EM_CONF[$_EXTKEY] = [
	'title' => 'HTTP/2 Push',
	'description' => 'HTTP/2 Push for TYPO3. Be sure to include extension after scriptmerger if installed. It should be 
	    the "last hook".',
	'category' => 'misc',
	'author' => 'Kevin Lieser',
	'author_email' => 'info@ka-mediendesign.de',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => '0',
	'createDirs' => '',
	'clearCacheOnLoad' => 0,
	'version' => '0.4.0',
	'constraints' => [
		'depends' => [
            'php' => '5.6.0-7.1.99',
			'typo3' => '6.2.0-7.6.99',
        ],
		'conflicts' => [
        ],
		'suggests' => [
        ],
    ],
];