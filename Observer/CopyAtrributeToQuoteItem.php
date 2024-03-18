<?php
namespace Deki\FlashSale\Observer;
 
use Magento\Framework\Event\ObserverInterface;
use Deki\FlashSale\Model\Config;

class CopyAtrributeToQuoteItem implements ObserverInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @param Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->config->isEnabled()) {
            return $this;
        }

        $quoteItem = $observer->getQuoteItem();
        $product = $observer->getProduct();

        $product->getFinalPrice();

        if ($product->getData('is_flash_sale')) {
            $quoteItem->setData('is_flash_sale', true);
            $quoteItem->setData('flash_sale_event_id', $product->getData('flash_sale_event_id'));
            $quoteItem->setData('flash_sale_event_product_id', $product->getData('flash_sale_event_product_id'));
        }
    }
}
