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

class SceConfiguration
{
    /**
     * @var string
     */
    protected $cType = '';

    /**
     * @var string
     */
    protected $label = '';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var mixed[]
     */
    protected $fields = [];

    /**
     * @var mixed[]
     */
    protected $palettes = [];

    /**
     * @var string
     */
    protected $additionalTabs = 'none';

    /**
     * @var string
     */
    protected $showitem = '';

    /**
     * @var string
     */
    protected $icon = 'EXT:sce/Resources/Public/Icons/Extension.svg';

    /**
     * @var bool
     */
    protected $saveAndCloseInNewContentElementWizard = false;

    /**
     * @var bool
     */
    protected $registerInNewContentElementWizard = true;

    /**
     * @var string
     */
    protected $group = 'sce';

    public function __construct(
        string $cType,
        string $label,
        string $description,
        array $fields
    ) {
        $this->cType = $cType;
        $this->label = $label;
        $this->description = $description;
        $this->fields = $fields;
    }

    /**
     * @param mixed[] $palettes
     * @return SceConfiguration
     */
    public function setPalettes(array $palettes): SceConfiguration
    {
        $this->palettes = $palettes;
        return $this;
    }

    /**
     * @param string $icon
     * @return SceConfiguration
     */
    public function setIcon(string $icon): SceConfiguration
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * @param string $additionalTabs
     * @return SceConfiguration
     */
    public function setAdditionalTabs(string $additionalTabs): SceConfiguration
    {
        $this->additionalTabs = $additionalTabs;
        return $this;
    }

    /**
     * @param string $showitem
     * @return SceConfiguration
     */
    public function setShowitem(string $showitem): SceConfiguration
    {
        $this->showitem = $showitem;
        return $this;
    }

    /**
     * @param bool $saveAndCloseInNewContentElementWizard
     * @return SceConfiguration
     */
    public function setSaveAndCloseInNewContentElementWizard(bool $saveAndCloseInNewContentElementWizard): SceConfiguration
    {
        $this->saveAndCloseInNewContentElementWizard = $saveAndCloseInNewContentElementWizard;
        return $this;
    }

    /**
     * @param bool $registerInNewContentElementWizard
     * @return SceConfiguration
     */
    public function setRegisterInNewContentElementWizard(bool $registerInNewContentElementWizard): SceConfiguration
    {
        $this->registerInNewContentElementWizard = $registerInNewContentElementWizard;
        return $this;
    }

    /**
     * @param string $group
     * @return SceConfiguration
     */
    public function setGroup(string $group): SceConfiguration
    {
        $this->group = $group;
        return $this;
    }

    /**
     * @return string
     */
    public function getCType(): string
    {
        return $this->cType;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return mixed[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @return mixed[]
     */
    public function getPalettes(): array
    {
        return $this->palettes;
    }

    /**
     * @return string
     */
    public function getAdditionalTabs(): string
    {
        return $this->additionalTabs;
    }

    /**
     * @return string
     */
    public function getShowitem(): string
    {
        return $this->showitem;
    }

    /**
     * @return string
     */
    public function getGroup(): string
    {
        return $this->group;
    }

    /**
     * @return mixed[]
     */
    public function toArray(): array
    {
        return [
            'cType' => $this->cType,
            'icon' => $this->icon,
            'label' => $this->label,
            'description' => $this->description,
            'fields' => $this->fields,
            'saveAndCloseInNewContentElementWizard' => $this->saveAndCloseInNewContentElementWizard,
            'registerInNewContentElementWizard' => $this->registerInNewContentElementWizard,
            'additionalTabs' => $this->additionalTabs,
            'showitem' => $this->showitem,
            'group' => $this->group
        ];
    }
}