<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */

declare(strict_types=1);

namespace AcidUnit\Admin\Block\Adminhtml\System\Config\Form\Field;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\PageBuilder\Block\WysiwygSetup;

class ToggleEditorButton extends Field
{
    /**
     * @param WysiwygSetup $wysiwygSetup
     * @param Context $context
     * @param array<mixed> $data
     */
    public function __construct(
        private readonly WysiwygSetup $wysiwygSetup,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Prepare layout
     *
     * @return $this|ToggleEditorButton
     */
    protected function _prepareLayout(): ToggleEditorButton|static
    {
        parent::_prepareLayout();
        $this->setTemplate('AcidUnit_Admin::system/config/toggle-editor-button.phtml');

        return $this;
    }

    /**
     * Render
     *
     * @param AbstractElement $element
     * @return string
     * @noinspection PhpUndefinedMethodInspection
     */
    public function render(AbstractElement $element): string
    {
        $element = clone $element;
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue(); // @phpstan-ignore-line

        return parent::render($element);
    }

    /**
     * Get element HTML
     *
     * @param AbstractElement $element
     * @return string
     * @noinspection PhpUndefinedMethodInspection
     */
    protected function _getElementHtml(AbstractElement $element): string
    {
        $originalData = $element->getOriginalData(); // @phpstan-ignore-line
        $this->addData([
            'html_id' => $element->getHtmlId(),
            'target_field' => $originalData['target_field']
        ]);

        return $this->_toHtml();
    }

    /**
     * Get editor config
     *
     * @return string
     */
    public function getEditorConfigJson(): string
    {
        return $this->wysiwygSetup->getConfigJson();
    }
}
