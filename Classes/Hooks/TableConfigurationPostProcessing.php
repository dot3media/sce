<?php

declare(strict_types=1);

namespace D3M\Sce\Hooks;

/*
 * This file is part of TYPO3 CMS-based extension "container" by b13.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use D3M\Sce\Tca\Registry;
use TYPO3\CMS\Core\Database\TableConfigurationPostProcessingHookInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TableConfigurationPostProcessing implements TableConfigurationPostProcessingHookInterface
{

    /**
     * @var Registry
     */
    protected $tcaRegistry;

    /**
     * @param Registry|null $tcaRegistry
     */
    public function __construct(Registry $tcaRegistry = null)
    {
        $this->tcaRegistry = $tcaRegistry ?? GeneralUtility::makeInstance(Registry::class);
    }

    public function processData()
    {
        $this->tcaRegistry->registerIcons();
    }
}