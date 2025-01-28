<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection UsingHelperClassInspection */
/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */

declare(strict_types=1);

namespace AcidUnit\Admin\Helper;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Serialize\Serializer\Json;

abstract class AdminAbstractTable
{
    /**
     * @var Json
     */
    private mixed $serializer;

    /**
     * @param Json|null $serializer
     */
    public function __construct(
        Json $serializer = null
    ) {
        /** @noinspection ObjectManagerInspection */
        $this->serializer = $serializer ?: ObjectManager::getInstance()->get(Json::class);
    }

    /**
     * Generate a storable representation of a value
     *
     * @param float|int|string|array<mixed> $value
     * @return string|bool
     * @noinspection PhpLoopCanBeConvertedToArrayFilterInspection
     */
    protected function serializeValue(array|float|int|string $value): string|bool
    {
        if (is_numeric($value)) {
            $data = (float)$value;

            return (string)$data;
        } elseif (is_array($value)) {
            $data = [];

            foreach ($value as $targetUrl => $buttonText) {
                if (!array_key_exists($targetUrl, $data)) {
                    $data[$targetUrl] = $buttonText;
                }
            }

            return $this->serializer->serialize($data);
        } else {
            return $value;
        }
    }

    /**
     * Create a value from a storable representation
     *
     * @param int|float|string $value
     * @return mixed
     */
    protected function unserializeValue(mixed $value): mixed
    {
        if (is_numeric($value) || is_string($value) && !empty($value)) {
            return $this->serializer->unserialize((string)$value);
        } else {
            return [];
        }
    }

    /**
     * Make value readable by
     * \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
     *
     * @param mixed $value
     * @return array<mixed>
     */
    public function makeArrayFieldValue(mixed $value): array
    {
        $value = $this->unserializeValue($value);

        if (!$this->isEncodedArrayFieldValue($value)) {
            $value = $this->encodeArrayFieldValue($value);
        }

        return $value;
    }

    /**
     * Make value ready for store
     *
     * @param mixed $value
     * @return string|bool
     */
    public function makeStorableArrayFieldValue(mixed $value): string|bool
    {
        if ($this->isEncodedArrayFieldValue($value)) {
            $value = $this->decodeArrayFieldValue($value);
        }

        return $this->serializeValue($value);
    }

    /**
     * Check whether value is in form retrieved by _encodeArrayFieldValue()
     *
     * This method should be overridden in child class
     *
     * @param string|array<mixed> $value
     * @return bool
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function isEncodedArrayFieldValue(array|string $value): bool
    {
        return true;
    }

    /**
     * Encode value to be used in
     * \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
     *
     * This method should be overridden in child class
     *
     * @param array<mixed> $value
     * @return array<mixed>
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function encodeArrayFieldValue(array $value): array
    {
        return [];
    }

    /**
     * Decode value from used in
     * \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
     *
     * This method should be overridden in child class
     *
     * @param array<mixed> $value
     * @return array<mixed>
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function decodeArrayFieldValue(array $value): array
    {
        return [];
    }
}
