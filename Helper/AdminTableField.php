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
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Math\Random;
use Magento\Framework\Serialize\Serializer\Json;

class AdminTableField
{
    /**
     * @var Json
     */
    private mixed $serializer;

    /**
     * @var array<string>
     */
    private array $tableFields;

    /**
     * @param Json|null $serializer
     * @param Random $mathRandom
     * @param array $tableFields
     */
    public function __construct(
        Json                    $serializer = null,
        private readonly Random $mathRandom,
        array                   $tableFields = []
    ) {
        /** @noinspection ObjectManagerInspection */
        $this->serializer = $serializer ?: ObjectManager::getInstance()->get(Json::class);
        $this->tableFields = $tableFields;
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
     * @throws LocalizedException
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
     * @param string|array<mixed> $value
     * @return bool
     */
    protected function isEncodedArrayFieldValue(array|string $value): bool
    {
        if (!is_array($value)) {
            return false;
        }

        unset($value['__empty']);

        foreach ($value as $row) {
            if (!is_array($row) || array_diff($this->tableFields, array_keys($row))) {
                return false;
            }
        }

        return true;
    }

    /**
     * Encode value to be used in
     * \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
     *
     * @param array<mixed> $value
     * @return array<mixed>
     * @throws LocalizedException
     */
    protected function encodeArrayFieldValue(array $value): array
    {
        $result = [];
        unset($value['__empty']);

        foreach ($value as $row) {
            $resultId = $this->mathRandom->getUniqueHash('_');

            foreach ($this->tableFields as $field) {
                $result[$resultId][$field] = $row[$field];
            }
        }

        return $result;
    }

    /**
     * Decode value from used in
     * \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
     *
     * @param array<mixed> $value
     * @return array<mixed>
     */
    protected function decodeArrayFieldValue(array $value): array
    {
        $result = [];
        unset($value['__empty']);

        foreach ($value as $key => $row) {
            if (!is_array($row) || array_diff($this->tableFields, array_keys($row))) {
                continue;
            }

            foreach ($this->tableFields as $field) {
                $result[$key][$field] = $row[$field];
            }
        }

        return $result;
    }
}
