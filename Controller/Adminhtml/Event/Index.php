<?php
/**
 * Copyright © (Deki) nooby.dev All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\FlashSale\Controller\Adminhtml\Event;

class Index extends \Magento\Backend\App\Action
{

    protected $resultPageFactory;

    /**
     * @var \Deki\FlashSale\Model\EventFactory
     */
    protected $eventFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Deki\FlashSale\Model\EventFactory $eventFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Deki\FlashSale\Model\EventFactory $eventFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->eventFactory = $eventFactory;
        parent::__construct($context);
    }

    /**
     * Index action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
            $resultPage->getConfig()->getTitle()->prepend(
                __("Flash Sale Event")
            );
            return $resultPage;
    }
}
