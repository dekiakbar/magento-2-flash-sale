<?php
/**
 * Copyright © (Deki) nooby.dev All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\FlashSale\Controller\Adminhtml\Event;

class Edit extends \Deki\FlashSale\Controller\Adminhtml\Event
{

    protected $resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Deki\FlashSale\Model\EventFactory $eventFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Deki\FlashSale\Model\EventFactory $eventFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct(
            $context,
            $coreRegistry,
            $storeManager,
            $eventFactory
        );
    }

    /**
     * Edit action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('event_id');
        $model = $this->eventFactory->create();
        
        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Event no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('deki_flashsale_event', $model);
        
        // 3. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Event') : __('New Event'),
            $id ? __('Edit Event') : __('New Event')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Events'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Event %1', $model->getName()) : __('New Event'));
        return $resultPage;
    }
}
