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

final class SceConfiguration
{
    protected string $cType = '';

    protected string $label = '';

    protected string $description = '';

    /**
     * @var array<array-key, mixed>
     */
    protected array $fields = [];

    /**
     * @var array<array-key, mixed>
     */
    protected array $palettes = [];

    protected string $additionalTabs = 'none';

    protected string $showitem = '';

    protected string $icon = 'EXT:sce/Resources/Public/Icons/Extension.svg';

    protected bool $saveAndCloseInNewContentElementWizard = false;

    protected bool $registerInNewContentElementWizard = true;

    protected string $group = 'sce';

    /**
     * @param array<string, array<string, mixed>> $fields
     */
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

    public function setIcon(string $icon): SceConfiguration
    {
        $this->icon = $icon;
        return $this;
    }

    public function setSaveAndCloseInNewContentElementWizard(bool $saveAndCloseInNewContentElementWizard): SceConfiguration
    {
        $this->saveAndCloseInNewContentElementWizard = $saveAndCloseInNewContentElementWizard;
        return $this;
    }

    public function setRegisterInNewContentElementWizard(bool $registerInNewContentElementWizard): SceConfiguration
    {
        $this->registerInNewContentElementWizard = $registerInNewContentElementWizard;
        return $this;
    }

    public function getCType(): string
    {
        return $this->cType;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return array<array-key, mixed>
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @return array<array-key, mixed>
     */
    public function getPalettes(): array
    {
        return $this->palettes;
    }

    /**
     * @param array<array-key, mixed> $palettes
     */
    public function setPalettes(array $palettes): SceConfiguration
    {
        $this->palettes = $palettes;
        return $this;
    }

    public function getAdditionalTabs(): string
    {
        return $this->additionalTabs;
    }

    public function setAdditionalTabs(string $additionalTabs): SceConfiguration
    {
        $this->additionalTabs = $additionalTabs;
        return $this;
    }

    public function getShowitem(): string
    {
        return $this->showitem;
    }

    public function setShowitem(string $showitem): SceConfiguration
    {
        $this->showitem = $showitem;
        return $this;
    }

    public function getGroup(): string
    {
        return $this->group;
    }

    public function setGroup(string $group): SceConfiguration
    {
        $this->group = $group;
        return $this;
    }

    /**
     * @return array<array-key, mixed>
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
            'group' => $this->group,
        ];
    }

    /**
     * @param array<string, array<string, mixed>> $fields
     */
    public static function showItemGenerator(array $fields, string ...$showItems): string
    {
        $fieldKeys = array_keys($fields);
        $showItem = implode(',', $showItems);
        return sprintf(
            $showItem,
            implode(',', $fieldKeys)
        );
    }
}
