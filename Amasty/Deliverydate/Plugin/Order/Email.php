<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */


namespace Amasty\Deliverydate\Plugin\Order;

class Email
{
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * @var \Amasty\Deliverydate\Helper\Data
     */
    protected $deliveryHelper;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Registry $coreRegistry,
        \Amasty\Deliverydate\Helper\Data $deliveryHelper,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->request = $request;
        $this->coreRegistry = $coreRegistry;
        $this->deliveryHelper = $deliveryHelper;
        $this->logger = $logger;
    }

    /**
     * @param \Magento\Sales\Block\Items\AbstractItems $subject
     * @param string $result HTML
     *
     * @return string
     */
    public function afterToHtml(\Magento\Sales\Block\Items\AbstractItems $subject, $result)
    {
        $addToResult = '';

        if ($subject->getOrder() && $subject->getOrder()->getId()) {
            $deliveryDateFields = '';
            if ($subject instanceof \Magento\Sales\Block\Order\Email\Invoice\Items) {
                $deliveryDateFields = $this->deliveryHelper->whatShow('invoice_email', 'include');
            } elseif ($subject instanceof \Magento\Sales\Block\Order\Email\Shipment\Items) {
                $deliveryDateFields = $this->deliveryHelper->whatShow('shipment_email', 'include');
            } elseif ($subject instanceof \Magento\Sales\Block\Order\Email\Items) {
                $deliveryDateFields = $this->deliveryHelper->whatShow('order_email', 'include');
            }

            if ($deliveryDateFields) {
                try {
                    $addToResult = $subject->getLayout()
                        ->createBlock(
                            \Amasty\Deliverydate\Block\Sales\Order\Email\Deliverydate::class,
                            'deliverydate_info',
                            [
                                'data' => [
                                    'order_id' => $subject->getOrder()->getId(),
                                    'fields' => $deliveryDateFields
                                ]
                            ]
                        )
                        ->toHtml();

                } catch (\Magento\Framework\Exception\LocalizedException $e) {
                    $this->logger->error($e->getLogMessage());
                }
            }
        }

        return $addToResult . $result;
    }
}
