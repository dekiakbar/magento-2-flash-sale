<?php
/**
 * Copyright © (Deki) nooby.dev All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Deki\FlashSale\Model;

class Config
{
    /**
     * Base path
     */
    public const XML_BASE_PATH = 'flash_sale/';

    /**
     * Is Enabled path
     */
    public const XML_IS_ENABLED = 'general/is_enabled';

    /**
     * Supported products
     */
    public const XML_SUPPORTED_PRODUCTS_TYPE = 'general/supported_products_type';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    /**
     * Get Config Value.
     *
     * @param string $path
     * @param string|array|bool $storeId if not specified, will get Default config (global)
     * @return string|array|bool
     */
    public function getConfig($path, $storeId = null)
    {
        if (!empty($storeId)) {
            return $this->scopeConfig->getValue(
                self::XML_BASE_PATH.$path,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $storeId
            );
        }

        return $this->scopeConfig->getValue(
            self::XML_BASE_PATH.$path,
            \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT
        );
    }

    /**
     * Get is enable config
     *
     * @param string|array|bool $storeId
     * @return boolean
     */
    public function isEnabled($storeId = null)
    {
        return $this->getConfig(self::XML_IS_ENABLED, $storeId);
    }

    /**
     * Get supported products type.
     * Return product type ID, eg: simple
     *
     * @param int|null $storeId
     * @return array
     */
    public function getSupportedProductTypes($storeId = null)
    {
        $productIds = $this->getConfig(self::XML_SUPPORTED_PRODUCTS_TYPE, $storeId);
        return explode(',', $productIds);
    }
}
