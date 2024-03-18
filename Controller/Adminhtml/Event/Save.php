<?php
/**
 * Copyright © (Deki) nooby.dev All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\FlashSale\Controller\Adminhtml\Event;

use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{

    protected $dataPersistor;

    /**
     * @var \Deki\FlashSale\Model\EventFactory
     */
    protected $eventFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param \Deki\FlashSale\Model\EventFactory $eventFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Deki\FlashSale\Model\EventFactory $eventFactory
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->eventFactory = $eventFactory;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            $id = $this->getRequest()->getParam('event_id');
        
            $model = $this->eventFactory->create()->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Event no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
        
            $model->setData($data);
            
            /**
             * Process Event Products
             */
            if (isset($data['event_products'])
                && is_string($data['event_products'])
            ) {
                $products = json_decode($data['event_products'], true);
                $model->setPostedProducts($products);
            }

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Event.'));
                $this->dataPersistor->clear('deki_flashsale_event');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['event_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Event.'));
            }
        
            $this->dataPersistor->set('deki_flashsale_event', $data);
            return $resultRedirect->setPath('*/*/edit', ['event_id' => $this->getRequest()->getParam('event_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
