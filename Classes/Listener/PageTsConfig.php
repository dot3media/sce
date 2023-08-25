<?php

declare(strict_types=1);

namespace D3M\Sce\Listener;

/*
 * This file is part of TYPO3 CMS-based extension "sce" by dot3media.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use D3M\Sce\Tca\Registry;
use TYPO3\CMS\Core\Configuration\Event\ModifyLoadedPageTsConfigEvent;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PageTsConfig
{
    /**
     * @var Registry
     */
    protected $tcaRegistry;

    public function __construct(Registry $tcaRegistry = null)
    {
        $this->tcaRegistry = $tcaRegistry ?? GeneralUtility::makeInstance(Registry::class);
    }

    public function __invoke(ModifyLoadedPageTsConfigEvent $event): void
    {
        $event->addTsConfig($this->tcaRegistry->getPageTsString());
    }
}