# EXT:sce - A TYPO3 extension for creating simple content elements

Webpages ca be seen as a composition of 3 elements: grids building the structure (sections or columns), containers like a card container containing several cards and very often quite simple content elements (like text elements with header and text). Some of these content elements may have the same fields, but they 'behave' differently in different contexts e.g. a text element can be a rendered as 'normal' text or as a card in a card container. 

The idea of this extension is to have a simple way to build content elements from scratch, which are easy to configure by integrators easy to use by editors. Instead of using several layout options in the content element itself to make it fit a certain context, the suggestion of this extension is, to build as many content elements as there are contexts in your webpage. By using mainly default fields of the tt_content table, the content type can be easily switched in bulk from TYPO3-default content type to site-package-specific content type and vice versa in the database.

**This documentation is about how to create and configure simple content elements with this extension. If you want to know, how to create contexts and limit the usage of content elements to a certain context, I recommend reading the following documentations:**

- [Backend Layouts](https://docs.typo3.org/m/typo3/reference-coreapi/master/en-us/ApiOverview/BackendLayout/Index.html)
- [gridelementsteam/gridelements extension](https://docs.typo3.org/typo3cms/extensions/gridelements/stable/)
- [b13/container extension](https://github.com/b13/container/blob/master/README.md)
- [ichhabrecht/content-defender](https://github.com/IchHabRecht/content_defender/blob/master/README.md)

## Installation

Install this extension via `composer require d3m/sce` or download it from the TYPO3 Extension Repository (extension name "sce") and activate the extension in the Extension Manager of your TYPO3 installation. Once installed, add custom simple content elements to your site extension (see "Adding your own simple content element").

## Adding your own simple content element

- Go to your site package extension
- Register your simple content element by adding a configuration file in Configuration/TCA/Overrides/ (e.g. site-package-text.php)
- Add TypoScript and a Fluid Template for frontend rendering
- Add an Icon in Resources/Public/Icons (e.g. SitePackageText.svg)

### Registration of simple content elements

This is an example for create a text-with-header element. The element is registered with its CType, its name and description and field configuration in form of an array.

```php
\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\D3M\Sce\Tca\Registry::class)-> configureSce(
    (
        new \D3M\Sce\Tca\SceConfiguration(
            'site-package-text', // CType of the simple content element
            'Site Package Text', // Name of the simple content element
            'Insert an element with text and header', // Description for the simple content element
            [
                'header' => [
                    'label' => 'Simple Text Header',
                    'columnOverrides' => [
                        'eval' => 'required,trim',
                    ],
                ],
                'header_layout' => [
                    'columnOverrides' => [
                        'eval' => 'required',
                    ],
                ],
                'bodytext' => [
                    'columnOverrides' => [
                        'enableRichtext' => true,
                        'eval' => 'required,trim',
                    ],
                ],
            ]
        )
    )
);
```

#### Field configuration

| Option | Description | Parameters |
| ----------- | ----------- | ---------- |
| `label` | If set, the default label will be replaced | `string` or language label 'LLL:EXT:site_package/Resources/Private/Language/locallang.xlf:title' |
| `columnOverrides` | Allows to change the column definition of a field (see [TYPO3 documentation](https://docs.typo3.org/m/typo3/reference-tca/master/en-us/Types/Index.html)) | `array` |
| `columnOverrides.allowedFileTypes` | Shortcut for `$columnOverrides['overrideChildTca']['columns']['uid_local']['config']['appearance']['elementBrowserAllowed']` to change the allowed file types for FAL fields like 'assets' in tt_content | `string` |

#### Additional Methods of the SceConfiguration Object

| Method name | Description | Parameters | Default |
| ----------- | ----------- | ---------- | ---------- |
| `setPalettes` | Add palettes to the default TCA configuration to use them in the showitem configuration (see below and [TYPO3 documentation](https://docs.typo3.org/m/typo3/reference-tca/master/en-us/Palettes/Index.html)) | `array $palettes` | `[]` |
| `setAdditionalTabs` | Add default tabs (`'default'`) or a simplified context tab (`'context'`) | `string $additionalTabs` | `'none'` |
| `setShowitme` | Set the complete showitem definition for the general tab (e.g. `'header, header_layout, bodytext'`). If not set, all defined fields will be simply concatenated in order of definition. | `string $showitem` | `''` |
| `setIcon` | icon file, or existing icon identifier | `string $icon` | `EXT:sce/Resources/Public/Icons/Extension.svg` |
| `setSaveAndCloseInNewContentElementWizard` | saveAndClose for new content element wizard (v10 only) | `bool $saveAndCloseInNewContentElementWizard` | `false` |
| `setRegisterInNewContentElementWizard` |Register in new content element wizard | `bool $registerInNewContentElementWizard` | `true` |
| `setGroup` | Custom Group (used as optgroup for CType Select (v10 only), and as Tab in New Content Element Wiazrd) (if empty "container" is used as Tab and no optgroup in CType is used) | `string $group` | `'sce'` |

### TypoScript

    tt_content.site-package-text =< lib.contentElement
    tt_content.site-package-text {
        templateName = SitePackageText
        variables < temp.variables
    }

### Template

```html
<f:render partial="Header/All" arguments="{_all}" />
<f:format.html>{data.bodytext}</f:format.html>
```

To find out, which variables are available, you can add `<f:debug>{_all}</f:debug>` in your template.

## To-dos

- Add configuration for backend rendering
- Add backend module for bulk changing the CType of content elements e.g. switching from TYPO3-default content type to site-package-specific content type and vice versa

## Credits

This extension was created by Jan-Philipp Halle ([dot3media](https://www.dot3media.de)) in 2020.  
The initial implementation heavily bases on the [container extension](https://github.com/b13/container/) by [b13 GmbH](https://b13.com).