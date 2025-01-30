<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpMissingFieldTypeInspection */
/** @noinspection PhpUnused */

declare(strict_types=1);

namespace AcidUnit\Admin\Block\Adminhtml\Form\Field;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Helper\SecureHtmlRenderer;

class AdminTableField extends AbstractFieldArray
{
    /**
     * @var array
     */
    private $dropdownRenderers = [];

    /**
     * @var array
     */
    private array $tableFields;

    /**
     * @var string
     */
    private string $buttonLabel;

    /**
     * @param Context $context
     * @param array $data
     * @param SecureHtmlRenderer|null $secureRenderer
     * @param array $tableFields
     * @param string $buttonLabel
     */
    public function __construct(
        Context $context,
        array $data = [],
        ?SecureHtmlRenderer $secureRenderer = null,
        array $tableFields = [],
        string $buttonLabel = ''
    ) {
        $this->tableFields = $tableFields;
        $this->buttonLabel = $buttonLabel;

        parent::__construct($context, $data, $secureRenderer);
    }

    /**
     * Get dropdown renderer
     *
     * @param string $key
     * @return mixed
     * @throws LocalizedException
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    protected function getRenderer(string $key): mixed
    {
        if (!array_key_exists($key, $this->dropdownRenderers)) {
            $this->dropdownRenderers[$key] = $this->getLayout()->createBlock(
                $this->tableFields[$key]['renderer']::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
            $this->dropdownRenderers[$key]->setClass('admin__control-select'); // @phpstan-ignore-line
        }

        return $this->dropdownRenderers[$key];
    }

    /**
     * Prepare to render
     *
     * @return void
     * @throws LocalizedException
     */
    protected function _prepareToRender(): void
    {
        foreach ($this->tableFields as $key => $tableField) {
            $params = [
                'label' => $tableField['label']
            ];

            if (array_key_exists('class', $tableField)) {
                $params['class'] = $tableField['class'];
            }

            if (array_key_exists('renderer', $tableField)) {
                $params['renderer'] = $this->getRenderer($key);
            }

            $this->addColumn($key, $params);
        }

        $this->_addAfter = false;
        $this->_addButtonLabel = $this->buttonLabel;
    }

    /**
     * Prepare existing row data object
     *
     * @param DataObject $row
     * @return void
     * @throws LocalizedException
     */
    protected function _prepareArrayRow(DataObject $row): void
    {
        $optionExtraAttr = [];

        foreach ($this->tableFields as $key => $tableField) {
            if (array_key_exists('renderer', $tableField)) {
                $optionExtraAttr[
                'option_' . $this->getRenderer($key)->calcOptionHash($row->getData($key))
                ] = 'selected="selected"';
            }
        }

        $row->setData('option_extra_attrs', $optionExtraAttr);
    }
}
