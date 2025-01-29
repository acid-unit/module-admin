<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpDeprecationInspection */
/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */
/** @noinspection PhpUnused */

namespace AcidUnit\Admin\Model\System\Config\Backend;

use AcidUnit\Admin\Helper\AdminTableField as AdminTableFieldHelper;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Value;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

/**
 * Backend for serialized array data
 */
class AdminTableField extends Value
{
    /**
     * @param AdminTableFieldHelper $helper
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $config
     * @param TypeListInterface $cacheTypeList
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array<mixed> $data
     */
    public function __construct(
        private readonly AdminTableFieldHelper $helper,
        Context                                $context,
        Registry                               $registry,
        ScopeConfigInterface                   $config,
        TypeListInterface                      $cacheTypeList,
        AbstractResource                       $resource = null,
        AbstractDb                             $resourceCollection = null,
        array                                  $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $config,
            $cacheTypeList,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * Processing after load data
     *
     * @return AdminTableField|$this
     * @noinspection PhpParamsInspection
     * @throws LocalizedException
     */
    protected function _afterLoad(): AdminTableField|static
    {
        $value = $this->getValue();
        $value = $this->helper->makeArrayFieldValue($value);
        $this->setValue($value);

        return $this;
    }

    /**
     * Prepare data before save
     *
     * @return AdminTableField|$this
     */
    public function beforeSave(): AdminTableField|static
    {
        $value = $this->getValue();
        $value = $this->helper->makeStorableArrayFieldValue($value);
        $this->setValue($value);

        return $this;
    }
}
