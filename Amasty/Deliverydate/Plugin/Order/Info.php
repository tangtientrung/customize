<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */


namespace Amasty\Deliverydate\Plugin\Order;

class Info
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
     * Plugin to abstract class \Magento\Sales\Block\Items\AbstractItems
     *
     * @param \Magento\Sales\Block\Items\AbstractItems $subject
     * @param string $result
     *
     * @return string
     */
    public function afterToHtml(\Magento\Sales\Block\Items\AbstractItems $subject, $result)
    {
        $addToResult = '';

        if ($subject->getOrder() &&
            $subject->getOrder()->getId() &&
            !$subject instanceof \Magento\Sales\Block\Order\Email\Creditmemo\Items
        ) {
            try {
                $addToResult = $subject->getLayout()
                    ->createBlock(
                        \Amasty\Deliverydate\Block\Sales\Order\Info\Deliverydate::class,
                        'deliverydate_info',
                        ['data' => ['order_id' => $subject->getOrder()->getId()]]
                    )
                    ->toHtml();
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->logger->error($e->getLogMessage());
            }
        }

        return $addToResult . $result;
    }
}
