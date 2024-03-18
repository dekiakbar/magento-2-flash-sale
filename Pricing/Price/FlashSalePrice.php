<?php
/**
 * Copyright © (Deki) nooby.dev All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\FlashSale\Pricing\Price;

use Deki\FlashSale\Model\FlashSaleService;
use Magento\Catalog\Model\Product;
use Magento\Customer\Model\Session;
use Magento\Framework\Pricing\Adjustment\Calculator;
use Magento\Framework\Pricing\Price\AbstractPrice;
use Magento\Framework\Pricing\Price\BasePriceProviderInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Store\Model\StoreManagerInterface;
use Deki\FlashSale\Model\ResourceModel\EventProductPriceFactory;
use Deki\FlashSale\Model\Config;

class FlashSalePrice extends AbstractPrice implements BasePriceProviderInterface
{
    /**
     * Price type identifier string
     */
    const PRICE_CODE = 'flash_sale_price';

    /**
     * @var TimezoneInterface
     */
    protected $dateTime;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @var EventProductPriceFactory
     */
    private $eventProductPriceFactory;

    /**
     * @var Config
     */
    private $config;

    /**
     *
     * @param Product $saleableItem
     * @param [type] $quantity
     * @param Calculator $calculator
     * @param PriceCurrencyInterface $priceCurrency
     * @param TimezoneInterface $dateTime
     * @param StoreManagerInterface $storeManager
     * @param Session $customerSession
     * @param EventProductPriceFactory $eventProductPriceFactory
     * @param Config $config
     */
    public function __construct(
        Product $saleableItem,
        $quantity,
        Calculator $calculator,
        PriceCurrencyInterface $priceCurrency,
        TimezoneInterface $dateTime,
        StoreManagerInterface $storeManager,
        Session $customerSession,
        EventProductPriceFactory $eventProductPriceFactory,
        Config $config
    ) {
        parent::__construct($saleableItem, $quantity, $calculator, $priceCurrency);
        $this->dateTime = $dateTime;
        $this->storeManager = $storeManager;
        $this->customerSession = $customerSession;
        $this->eventProductPriceFactory = $eventProductPriceFactory;
        $this->config = $config;
    }

    /**
     * Returns flas sale price value (apply to PDP and PLP)
     *
     * @return float|boolean
     */
    public function getValue()
    {
        if (!$this->config->isEnabled()) {
            return false;
        }

        if (null === $this->value) {
            if ($this->product->hasData(self::PRICE_CODE)) {
                $value = $this->product->getData(self::PRICE_CODE);
                $this->value = $value ? (float)$value : false;
            } else {
                $flashSalePriceInfo = $this->eventProductPriceFactory->create()->getFlashSalePriceInfo(
                    $this->dateTime->scopeDate($this->storeManager->getStore()->getId(), null, true),
                    $this->product->getId()
                );
                $this->setFlashSaleInfo($flashSalePriceInfo);
                $this->value = $flashSalePriceInfo ? (float)$flashSalePriceInfo['price'] : false;
            }
            if ($this->value) {
                $this->value = $this->priceCurrency->convertAndRound($this->value);
            }
        }

        return $this->value;
    }

    /**
     * Set flash sale into into product attribute
     *
     * @param array|false $flashSalePriceInfo
     * @return void
     */
    public function setFlashSaleInfo($flashSalePriceInfo)
    {
        if (!$flashSalePriceInfo) {
            return;
        }

        if ($discount = $flashSalePriceInfo['discount_amount']) {
            $this->product->setData(FlashSaleService::FLASH_SALE_DISCOUNT, $discount);
        }
    }
}
