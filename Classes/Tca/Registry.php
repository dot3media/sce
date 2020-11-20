<?php

declare(strict_types=1);

namespace D3M\Sce\Tca;

/*
 * This file is part of TYPO3 CMS-based extension "sce" by dot3media.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Registry implements SingletonInterface
{
    /**
     * @param SceConfiguration $sceConfiguration
     */
    public function configureSce(SceConfiguration $sceConfiguration): void
    {
        ExtensionManagementUtility::addTcaSelectItem(
            'tt_content',
            'CType',
            [
                $sceConfiguration->getLabel(),
                $sceConfiguration->getCType(),
                $sceConfiguration->getCType(),
                $sceConfiguration->getGroup()
            ]
        );

        if ($sceConfiguration->getShowitem()) {
            $generalTab = $sceConfiguration->getShowitem();
        } else {
            $fieldsArray = [];
            foreach ($sceConfiguration->getFields() as $fieldName => $fieldConfig) {
                $fieldsArray[] = $fieldName . ($fieldConfig['label'] ? ';' . $fieldConfig['label'] : '');
            }
            $generalTab = implode(',', $fieldsArray);
        }

        switch($sceConfiguration->getAdditionalTabs()) {
            case 'default':
                $defaultTabs = implode(',', [
                    '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance',
                    '--palette--;;frames',
                    '--palette--;;appearanceLinks',
                    '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language',
                    '--palette--;;language',
                    '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access',
                    '--palette--;;hidden',
                    '--palette--;;access',
                    '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories',
                    'categories',
                    '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes',
                    'rowDescription',
                    '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended',
                ]);
                $showitem = implode(',', [$generalTab, $defaultTabs]);
                break;

            case 'context':
                $contextTab = implode(',', [
                    '--div--;LLL:EXT:sce/Resources/Private/Language/locallang.xlf:context',
                    '--palette--;;general',
                    '--palette--;;hidden',
                    '--palette--;;access',
                    '--palette--;;language',
                    'categories',
                ]);
                $showitem = implode(',', [$generalTab, $contextTab]);
                break;

            default:
                $showitem = $generalTab;
        }

        $GLOBALS['TCA']['tt_content']['types'][$sceConfiguration->getCType()]['showitem'] = $showitem;

        $overrides = [];
        foreach ($sceConfiguration->getFields() as $fieldName => $fieldConfig) {    
            if ($fieldConfig['columnOverrides']) {
                $columnOverrides = $fieldConfig['columnOverrides'];
                if ($columnOverrides['allowedFileTypes']) {
                    $columnOverrides['overrideChildTca']['columns']['uid_local']['config']['appearance']['elementBrowserAllowed'] = $columnOverrides['allowedFileTypes'];
                }
                $overrides[$fieldName]['config'] = $columnOverrides;
            }
        }
        $GLOBALS['TCA']['tt_content']['types'][$sceConfiguration->getCType()]['columnsOverrides'] = $overrides;

        $GLOBALS['TCA']['tt_content']['palettes'] = array_merge($GLOBALS['TCA']['tt_content']['palettes'], $sceConfiguration->getPalettes());

        $GLOBALS['TCA']['tt_content']['sceConfiguration'][$sceConfiguration->getCType()] = $sceConfiguration->toArray();
    }

    public function registerIcons(): void
    {
        if (is_array($GLOBALS['TCA']['tt_content']['sceConfiguration'])) {
            $iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);
            foreach ($GLOBALS['TCA']['tt_content']['sceConfiguration'] as $sceConfiguration) {
                if (file_exists(GeneralUtility::getFileAbsFileName($sceConfiguration['icon']))) {
                    $provider = BitmapIconProvider::class;
                    if (strpos($sceConfiguration['icon'], '.svg') !== false) {
                        $provider = SvgIconProvider::class;
                    }
                    $iconRegistry->registerIcon(
                        $sceConfiguration['cType'],
                        $provider,
                        ['source' => $sceConfiguration['icon']]
                    );
                } else {
                    try {
                        $existingIconConfiguration = $iconRegistry->getIconConfigurationByIdentifier($sceConfiguration['icon']);
                        $iconRegistry->registerIcon(
                            $sceConfiguration['cType'],
                            $existingIconConfiguration['provider'],
                            $existingIconConfiguration['options']
                        );
                    } catch (\TYPO3\CMS\Core\Exception $e) {
                    }
                }
            }
        }
    }

    /**
     * Adds TSconfig
     *
     * @param array $TSdataArray
     * @param int $id
     * @param array $rootLine
     * @param array $returnPartArray
     * @return array
     */
    public function addPageTS($TSdataArray, $id, $rootLine, $returnPartArray): array
    {
        if (empty($GLOBALS['TCA']['tt_content']['sceConfiguration'])) {
            return [$TSdataArray, $id, $rootLine, $returnPartArray];
        }

        // Group containers by group
        $groupedByGroup = [];
        $defaultGroup = 'sce';

        foreach ($GLOBALS['TCA']['tt_content']['sceConfiguration'] as $cType => $sceConfiguration) {
            if ($sceConfiguration['registerInNewContentElementWizard'] === true) {
                $group = $sceConfiguration['group'] !== '' ? $sceConfiguration['group'] : $defaultGroup;
                if (empty($groupedByGroup[$group])) {
                    $groupedByGroup[$group] = [];
                }
                $groupedByGroup[$group][$cType] = $sceConfiguration;
            }
        }

        foreach ($groupedByGroup as $group => $sceConfigurations) {
            $groupLabel = $GLOBALS['TCA']['tt_content']['columns']['CType']['config']['itemGroups'][$group] ? $GLOBALS['TCA']['tt_content']['columns']['CType']['config']['itemGroups'][$group] : $group;

            $content = '
                mod.wizards.newContentElement.wizardItems.' . $group . '.header = ' . $groupLabel . '
                mod.wizards.newContentElement.wizardItems.' . $group . '.show = *';

            foreach ($sceConfigurations as $cType => $sceConfiguration) {
                $content .= '
                    mod.wizards.newContentElement.wizardItems.' . $group . '.elements {
                        ' . $cType . ' {
                            title = ' . $sceConfiguration['label'] . '
                            description = ' . $sceConfiguration['description'] . '
                            iconIdentifier = ' . $cType . '
                            tt_content_defValues.CType = ' . $cType . '
                            saveAndClose = ' . $sceConfiguration['saveAndCloseInNewContentElementWizard'] . '
                        }
                    }';
            }

            $TSdataArray['default'] .= LF . $content;
        }

        return [$TSdataArray, $id, $rootLine, $returnPartArray];
    }
}