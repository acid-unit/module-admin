<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpMissingClassConstantTypeInspection */
/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */

declare(strict_types=1);

namespace AcidUnit\Admin\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\ScopeInterface;

class Config extends DataObject implements ArgumentInterface
{
    public const XML_PATH_ENABLE_WYSIWYG_FOR_PAGEBUILDER_HTML_ELEMENT
        = 'cms/wysiwyg/enabled_for_pagebuilder_html_element';

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param array<mixed> $data
     */
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig,
        array                                 $data = []
    ) {
        parent::__construct($data);
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
