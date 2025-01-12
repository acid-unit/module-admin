<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */
/** @noinspection PhpUnused */

declare(strict_types=1);

namespace AcidUnit\Admin\Model;

class AdminConfigProvider implements ConfigProviderInterface
{
    /**
     * @param ConfigProviderInterface[] $configProviders
     */
    public function __construct(
        private readonly array $configProviders
    ) {
    }

    /**
     * Get custom config providers
     *
     * @return array<mixed>
     */
    public function getConfig(): array
    {
        $config = [];

        foreach ($this->configProviders as $configProvider) {
            $config = array_merge_recursive($config, $configProvider->getConfig());
        }

        return $config;
    }
}
