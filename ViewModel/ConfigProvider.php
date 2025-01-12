<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace AcidUnit\Admin\ViewModel;

use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use AcidUnit\Admin\Model\ConfigProviderInterface;

class ConfigProvider implements ArgumentInterface
{
    /**
     * @param Json $serializer
     * @param ConfigProviderInterface $configProvider
     */
    public function __construct(
        private readonly Json                    $serializer,
        private readonly ConfigProviderInterface $configProvider
    ) {
    }

    /**
     * Get admin config
     *
     * @return string
     */
    public function getSerializedCustomConfig(): string
    {
        return (string)$this->serializer->serialize($this->configProvider->getConfig());
    }
}
