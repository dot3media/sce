<?php

defined('TYPO3_MODE') || die('Access denied.');

call_user_func(static function () {
    \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class)->connect(
        \TYPO3\CMS\Backend\Utility\BackendUtility::class,
        'getPagesTSconfigPreInclude',
        D3M\Sce\Tca\Registry::class,
        'addPageTS'
    );

    // Register icons
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['GLOBAL']['extTablesInclusion-PostProcessing']['tx_sce'] =
        \D3M\Sce\Hooks\TableConfigurationPostProcessing::class;
});
