<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */

namespace Amasty\Deliverydate\Cron;

use Magento\Framework\App\ResourceConnection;

class Reminder
{

    /**
     * @var \Amasty\Deliverydate\Model\ResourceModel\Deliverydate\Collection
     */
    protected $deliverydateCollection;
    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $orderFactory;
    /**
     * @var \Magento\Sales\Model\ResourceModel\Order
     */
    protected $order;
    /**
     * @var \Amasty\Deliverydate\Helper\Data
     */
    protected $deliveryHelper;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;
    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $transportBuilder;

    public function __construct(
        \Amasty\Deliverydate\Model\ResourceModel\Deliverydate\Collection $deliverydateCollection,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Sales\Model\ResourceModel\Order $order,
        \Amasty\Deliverydate\Helper\Data $deliveryHelper,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
    ) {

        $this->deliverydateCollection = $deliverydateCollection;
        $this->orderFactory = $orderFactory;
        $this->order = $order;
        $this->deliveryHelper = $deliveryHelper;
        $this->date = $date;
        $this->transportBuilder = $transportBuilder;
    }

    public function execute()
    {
        $collection = $this->deliverydateCollection;
        $collection->addFieldToFilter('reminder', 0);
        $collection->getSelect()->where('`date` <> \'0000-00-00\'');

        if (0 < $collection->getSize()) {
            foreach ($collection as $deliveryDate) {
                $order = $this->orderFactory->create();
                $this->order->load($order, $deliveryDate->getOrderId());
                $storeId = $order->getStoreId();
                if ($this->deliveryHelper->getDefaultScopeValue('reminder/enabled_reminder')) {
                    // 60 min. * 60 sec. = 3600 sec.
                    $now = $this->date->date('U') + 3600 * $this->deliveryHelper->getDefaultScopeValue('general/offset');
                    $timeBefore = 3600 * $this->deliveryHelper->getDefaultScopeValue('reminder/time_before', $storeId);
                    $threshold = $this->date->timestamp($deliveryDate->getDate()) - $timeBefore;
                    if ($now >= $threshold) {
                        // send email
                        $emails = $this->deliveryHelper->getDefaultScopeValue('reminder/recipient_email');
                        $emails = explode(',', $emails);

                        if (!empty($emails)) {
                            $templateId = $this->deliveryHelper->getDefaultScopeValue('reminder/email_template');
                            $templateId = $templateId ? $templateId : 'amdeliverydate_reminder_email_template';

                            foreach ($emails as $email) {
                                $email = trim($email);
                                $vars = [
                                    'delivery' => $deliveryDate,
                                    'order'    => $order,
                                ];
                                $this->transportBuilder
                                    ->setTemplateIdentifier($templateId)
                                    ->setTemplateOptions(['area' => 'frontend', 'store' => $storeId])
                                    ->setTemplateVars($vars)
                                    ->setFrom($this->deliveryHelper->getDefaultScopeValue('reminder/reminder_sender'))
                                    ->addTo($email);

                                $transport = $this->transportBuilder->getTransport();
                                $transport->sendMessage();

                            }
                            $deliveryDate->setReminder(1);
                            $deliveryDate->save();
                        }
                    }
                }
            }
        }
    }
}