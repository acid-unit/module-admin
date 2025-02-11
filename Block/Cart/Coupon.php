<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace AcidUnit\Admin\Block\Cart;

use AcidUnit\Admin\Model\Config;
use Magento\Checkout\Block\Cart\Coupon as CouponParent;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\View\Element\Template\Context;

class Coupon extends CouponParent
{
    /**
     * @param Config $config
     * @param Context $context
     * @param CustomerSession $customerSession
     * @param CheckoutSession $checkoutSession
     * @param array $data
     */
    public function __construct(
        private readonly Config $config,
        Context                 $context,
        CustomerSession         $customerSession,
        CheckoutSession         $checkoutSession,
        array                   $data = []
    ) {
        parent::__construct(
            $context,
            $customerSession,
            $checkoutSession,
            $data
        );
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml(): string
    {
        if (!$this->config->isDiscountFieldHidden()) {
            return parent::_toHtml();
        }

        return '';
    }
}
