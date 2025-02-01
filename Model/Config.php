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
    public const XML_PATH_ENABLE_WYSIWYG_FOR_PAGEBUILDER_HTML_ELEMENT
        = 'cms/wysiwyg/enabled_for_pagebuilder_html_element';

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {
    }

    /**
     * Is WYSIWYG Editor is enabled for 'HTML Code' Pagebuilder element
     *
     * @return bool
     */
    public function isWysiwygForPageBuilderHtmlElementEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLE_WYSIWYG_FOR_PAGEBUILDER_HTML_ELEMENT,
            ScopeInterface::SCOPE_STORE
        );
    }
}
