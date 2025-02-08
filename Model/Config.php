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
    public const XML_PATH_WYSIWYG_FOR_PAGEBUILDER_HTML_ELEMENT_ENABLED
        = 'cms/wysiwyg/enabled_for_pagebuilder_html_element';

    public const XML_PATH_DISCOUNT_FIELD_ENABLED
        = 'checkout/options/discount_field_enabled';

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {
    }

    /**
     * Is Discount Code field enabled
     *
     * @return bool
     */
    public function isDiscountFieldEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_DISCOUNT_FIELD_ENABLED,
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
