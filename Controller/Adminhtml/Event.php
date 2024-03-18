<?php
/**
 * Copyright © (Deki) nooby.dev All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\FlashSale\Controller\Adminhtml;

use Magento\Store\Model\Store;
use Magento\Framework\App\ObjectManager;

abstract class Event extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Deki_FlashSale::top_level';
    
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Deki\FlashSale\Model\EventFactory
     */
    protected $eventFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Store\Model\StoreManagerInterface|null $storeManager
     * @param \Deki\FlashSale\Model\EventFactory $eventFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Store\Model\StoreManagerInterface $storeManager = null,
        \Deki\FlashSale\Model\EventFactory $eventFactory
    ) {
        $this->_coreRegistry = $coreRegistry;
        $this->storeManager = $storeManager ?: ObjectManager::getInstance()->get(
            \Magento\Store\Model\StoreManagerInterface::class
        );
        $this->eventFactory = $eventFactory;
        parent::__construct($context);
    }

    /**
     * Init page
     *
     * @param \Magento\Backend\Model\View\Result\Page $resultPage
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function initPage($resultPage)
    {
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE)
            ->addBreadcrumb(__('Deki'), __('Deki'))
            ->addBreadcrumb(__('Event'), __('Event'));
        return $resultPage;
    }

    /**
     * Initialize requested event and put it into registry.
     *
     * @return \Deki\FlashSale\Model\Event|false
     */
    protected function _initEvent()
    {
        $eventId = $this->resolveEventId();
        $event = $this->_objectManager->create(\Deki\FlashSale\Model\Event::class);

        if ($eventId) {
            $event->load($eventId);
        }

        $this->_coreRegistry->unregister('deki_flashsale_event');
        $this->_coreRegistry->register('deki_flashsale_event', $event);
        
        return $event;
    }

    /**
     * Resolve Event Id (from get or from post)
     *
     * @return int
     */
    private function resolveEventId() : int
    {
        $eventId = (int)$this->getRequest()->getParam('id', false);

        return $eventId ?: (int)$this->getRequest()->getParam('event_id', false);
    }
}
