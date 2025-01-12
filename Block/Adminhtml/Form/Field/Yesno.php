<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace AcidUnit\Admin\Block\Adminhtml\Form\Field;

use Magento\Framework\View\Element\Html\Select;

class Yesno extends Select
{
    /**
     * Sets name for input element. Required for proper value storing
     *
     * @param string $value
     * @return mixed
     * @noinspection PhpUndefinedMethodInspection
     * @noinspection PhpUnused
     */
    public function setInputName(string $value): mixed
    {
        return $this->setName($value); // @phpstan-ignore-line
    }

    /**
     * Render HTML block
     *
     * @return string
     */
    public function _toHtml(): string
    {
        if (!$this->getOptions()) {
            $this->addOption('1', __('Yes'));
            $this->addOption('0', __('No'));
        }

        return parent::_toHtml();
    }
}
