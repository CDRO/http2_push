<?php
defined('TYPO3_MODE') or die ('Access denied.');

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all'][]
    = \Ka\Http2Push\Hooks\ContentPostProcessor::class . '->renderAll';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-output'][]
    = \Ka\Http2Push\Hooks\ContentPostProcessor::class . '->renderOutput';
