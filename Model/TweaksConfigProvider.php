<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpClassCanBeReadonlyInspection */
/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */
/** @noinspection PhpUnused */

declare(strict_types=1);

namespace AcidUnit\Admin\Model;

use AcidUnit\Core\Api\ConfigProviderInterface;
use Magento\Cms\Model\Wysiwyg\Config as MagentoWysiwygConfig;

class TweaksConfigProvider implements ConfigProviderInterface
{
    /**
     * @param Config $config
     * @param MagentoWysiwygConfig $magentoWysiwygConfig
     */
    public function __construct(
        private readonly Config               $config,
        private readonly MagentoWysiwygConfig $magentoWysiwygConfig
    ) {
    }

    /**
     * Get Config
     *
     * @return array<mixed>
     */
    public function getConfig(): array
    {
        return [
            'root_menu_item_hidden' => $this->config->isRootMenuItemHidden(),
            'wysiwyg_editor' => [
                'enabled' => $this->magentoWysiwygConfig->isEnabled(),
                'enabled_for_pagebuilder_html_element' => $this->config->isWysiwygForPageBuilderHtmlElementEnabled()
            ]
        ];
    }
}
