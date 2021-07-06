<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */

namespace Amasty\Deliverydate\Controller\Adminhtml\Deliverydate;

use Magento\Framework\Exception\LocalizedException;
use Amasty\Deliverydate\Model\Deliverydate;

class Save extends \Amasty\Deliverydate\Controller\Adminhtml\Deliverydate
{
    public function execute()
    {
        if ($this->getRequest()->getPostValue()) {
            $data = $this->getRequest()->getPostValue();
            $orderId = $this->getRequest()->getParam('order_id');

            try {
                $order = $this->orderFactory->create();
                $this->orderResource->load($order, $orderId);

                if (is_array($data) && !empty($data)) {
                    if (array_key_exists('clear', $data)) {
                        $data['date'] = Deliverydate::RESETED_DATE_VALUE;
                    }

                    $deliveryDate = $this->model->create();
                    $this->resourceModel->load($deliveryDate, $orderId, 'order_id');

                    $wasDate = $deliveryDate->getDate();
                    $wasTime = $deliveryDate->getTime();

                    if ($deliveryDate->prepareForSave($data, $order)) {
                        $this->resourceModel->save($deliveryDate);
                    }

                    $email = $order->getCustomerEmail();
                    if (array_key_exists('notify', $data) && $email) {
                        if ($wasDate != $deliveryDate->getDate() || $wasTime != $deliveryDate->getTime()) {
                            $value = $this->date->date($this->deliveryHelper->getPhpFormat(), $deliveryDate->getDate());
                            $deliveryDate->setDate($value);

                            $templateId = $this->deliveryHelper->getDefaultScopeValue('general/email_template');
                            $templateId = $templateId ? $templateId : 'amdeliverydate_general_email_template';

                            $sender = $this->deliveryHelper->getDefaultScopeValue('general/notification_sender');
                            $storeId = $order->getStoreId();

                            $vars = [
                                'delivery' => $deliveryDate,
                                'was_date' => $wasDate,
                                'was_time' => $wasTime,
                                'order' => $order
                            ];

                            $this->transportBuilder
                                ->setTemplateIdentifier($templateId)
                                ->setTemplateOptions(['area' => 'frontend', 'store' => $storeId])
                                ->setTemplateVars($vars)
                                ->setFrom($sender)
                                ->addTo($email);

                            $transport = $this->transportBuilder->getTransport();
                            $transport->sendMessage();
                        }

                    }
                    $this->messageManager->addSuccessMessage(__('Record has been successfully saved'));
                } else {
                    throw new LocalizedException(__('The wrong date interval is specified.'));
                }
                $this->_redirect('sales/order/view', ['order_id' => $orderId]);
                return;

            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->_redirect('sales/order/view', ['order_id' => $orderId]);
                return;
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(
                    __('Something went wrong while saving data. Please review the error log.')
                );
                $this->logInterface->critical($e);
                $this->session->setPageData($data);
                $this->_redirect('sales/order/view', ['order_id' => $orderId]);
                return;
            }
        }
    }
}