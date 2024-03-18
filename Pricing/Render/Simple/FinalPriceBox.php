<?php
/**
 * Copyright © (Deki) nooby.dev All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\FlashSale\Pricing\Render\Simple;

use Deki\FlashSale\Model\FlashSaleService;
use Magento\Catalog\Pricing\Price\FinalPrice;
use Deki\FlashSale\Pricing\Price\FlashSalePrice;

class FinalPriceBox extends \Magento\Catalog\Pricing\Render\FinalPriceBox
{
    /**
     * Define if the flash sale price should be shown
     *
     * @return bool
     */
    public function hasFlashSalePrice()
    {
        $displayFinalPrice = $this->getPriceType(FinalPrice::PRICE_CODE)->getAmount()->getValue();
        $displayFlashSalePrice = $this->getPriceType(FlashSalePrice::PRICE_CODE)->getAmount()->getValue();
        return $displayFinalPrice == $displayFlashSalePrice;
    }

    /**
     * Retrieve Flash sale discount amount
     *
     * @return float
     */
    public function getFlashSaleDicount()
    {
        return (float)$this->getSaleableItem()->getData(FlashSaleService::FLASH_SALE_DISCOUNT);
    }
}
