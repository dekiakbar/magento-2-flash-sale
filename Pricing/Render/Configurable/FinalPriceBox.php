<?php
/**
 * Copyright © (Deki) nooby.dev All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\FlashSale\Pricing\Render\Configurable;

use Magento\Catalog\Model\Product\Pricing\Renderer\SalableResolverInterface;
use Magento\Catalog\Pricing\Price\MinimalPriceCalculatorInterface;
use Magento\ConfigurableProduct\Pricing\Price\ConfigurableOptionsProviderInterface;
use Magento\Framework\Pricing\Price\PriceInterface;
use Magento\Framework\Pricing\Render\RendererPool;
use Magento\Framework\Pricing\SaleableInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Catalog\model\Product;
use Deki\FlashSale\Model\FlashSaleService;
use Deki\FlashSale\Pricing\Price\FlashSalePrice;
use Magento\Catalog\Pricing\Price\FinalPrice;

class FinalPriceBox extends \Magento\ConfigurableProduct\Pricing\Render\FinalPriceBox
{

    /**
     * @var ConfigurableOptionsProviderInterface
     */
    protected $configurableOptionsProvider;

    /**
     * @param Context $context
     * @param SaleableInterface $saleableItem
     * @param PriceInterface $price
     * @param RendererPool $rendererPool
     * @param SalableResolverInterface $salableResolver
     * @param MinimalPriceCalculatorInterface $minimalPriceCalculator
     * @param ConfigurableOptionsProviderInterface $configurableOptionsProvider
     * @param array $data
     */
    public function __construct(
        Context $context,
        SaleableInterface $saleableItem,
        PriceInterface $price,
        RendererPool $rendererPool,
        SalableResolverInterface $salableResolver,
        MinimalPriceCalculatorInterface $minimalPriceCalculator,
        ConfigurableOptionsProviderInterface $configurableOptionsProvider,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $saleableItem,
            $price,
            $rendererPool,
            $salableResolver,
            $minimalPriceCalculator,
            $configurableOptionsProvider,
            $data
        );

        $this->configurableOptionsProvider = $configurableOptionsProvider;
    }

    /**
     * Define if the special price should be shown
     *
     * @return bool
     */
    public function hasFlashSalePrice()
    {
        /** @var SaleableInterface|Product $product */
        $product = $this->getSaleableItem();

        /** @var Product $subProduct */
        foreach ($this->configurableOptionsProvider->getProducts($product) as $subProduct) {
            $displayFlashSalePrice = $subProduct->getPriceInfo()->getPrice(FlashSalePrice::PRICE_CODE)->getValue();
            $finalPrice = $subProduct->getPriceInfo()->getPrice(FinalPrice::PRICE_CODE)->getValue();
            if ($finalPrice == $displayFlashSalePrice) {
                return true;
            }
        }
        return false;
    }

    /**
     * Retrieve Flash sale discount amount
     *
     * @return float
     */
    public function getFlashSaleDicount()
    {
        /** @var SaleableInterface|Product $product */
        $product = $this->getSaleableItem();
        $discountAmounts = [];
        /** @var Product $subProduct */
        foreach ($this->configurableOptionsProvider->getProducts($product) as $subProduct) {
            $discountAmounts[] = (float)$subProduct->getData(FlashSaleService::FLASH_SALE_DISCOUNT);
        }
        return max($discountAmounts);
    }
}
