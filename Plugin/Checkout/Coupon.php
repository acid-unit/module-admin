<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpClassCanBeReadonlyInspection */
/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */

declare(strict_types=1);

namespace AcidUnit\Admin\Plugin\Checkout;

use AcidUnit\Admin\Model\Config;
use Magento\Checkout\Block\Checkout\LayoutProcessor;

class Coupon
{
    /**
     * @param Config $config
     */
    public function __construct(
        private readonly Config $config
    ) {
    }

    /**
     * After Plugin to show/hide discount field on checkout payment step based on the configuration
     *
     * @param LayoutProcessor $processor
     * @param array<mixed> $jsLayout
     *
     * @return array<mixed>
     * @see \Magento\Checkout\Block\Checkout\LayoutProcessor::process
     * @noinspection UnusedFormalParameterInspection
     * @noinspection PhpUnusedParameterInspection
     * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
     */
    public function afterProcess(LayoutProcessor $processor, array $jsLayout): array
    {
        if ($this->config->isDiscountFieldHidden()) {
            $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
            ['payment']['children']['afterMethods']['children']['discount']['config']['componentDisabled'] = true;
        }

        return $jsLayout;
    }
}
