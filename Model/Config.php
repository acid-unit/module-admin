<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpMissingClassConstantTypeInspection */

declare(strict_types=1);

namespace AcidUnit\Admin\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    public const XML_PATH_ROOT_MENU_ITEM_HIDDEN
        = 'acid_config/general/root_menu_item_hidden';

    public const XML_PATH_WYSIWYG_FOR_PAGEBUILDER_HTML_ELEMENT_ENABLED
        = 'acid_admin_tweaks/pagebuilder/html_element_wysiwyg_enabled';

    public const XML_PATH_DISCOUNT_FIELD_HIDDEN
        = 'acid_admin_tweaks/checkout/discount_field_hidden';

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {
    }

    /**
     * Is [Acid Unit] root menu item hidden
     *
     * @return bool
     */
    public function isRootMenuItemHidden(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ROOT_MENU_ITEM_HIDDEN,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is Discount Code field hidden
     *
     * @return bool
     */
    public function isDiscountFieldHidden(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_DISCOUNT_FIELD_HIDDEN,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is WYSIWYG Editor is enabled for 'HTML Code' Pagebuilder element
     *
     * @return bool
     */
    public function isWysiwygForPageBuilderHtmlElementEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_WYSIWYG_FOR_PAGEBUILDER_HTML_ELEMENT_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }
}
