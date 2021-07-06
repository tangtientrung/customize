<?php
namespace AHT\Fee\Model\Total;

use Magento\Quote\Model\Quote\Address\Total;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use AHT\Fee\Helper\Data as FeeHelper;
use Magento\Tax\Model\Calculation;

class Fee extends Address\Total\AbstractTotal
{
    /**
     * @var FeeHelper
     */
    protected $_helper;

    /**
     * @var Calculation
     */
    private $taxCalculator;

    /**
     * Fee constructor.
     * @param FeeHelper $helper
     * @param Calculation $taxCalculator
     */
    public function __construct(
        FeeHelper $helper,
        Calculation $taxCalculator
    ) {
        $this->_helper = $helper;
        $this->taxCalculator = $taxCalculator;
    }

    /**
     * @param Quote $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param Total $total
     * @return $this
     */
    public function collect(
        Quote $quote,
        ShippingAssignmentInterface $shippingAssignment,
        Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);

        if (!count($shippingAssignment->getItems())) {
            return $this;
        }

        $fee = 0;
        if ($this->_helper->canApply($quote)) {
            $fee = $this->_helper->getFee($quote);
        }
        

        $total->setFeeAmount($fee);
        $total->setBaseFeeAmount($fee);

        $total->setTotalAmount('payment_fee', $fee);
        $total->setBaseTotalAmount('base_payment_fee', $fee);

        // // Duplicate fee added when this is added
        // $total->setGrandTotal($total->getGrandTotal() + $total->getFeeAmount());
        // $total->setBaseGrandTotal($total->getBaseGrandTotal() + $total->getBaseFeeAmount());
        $total->setGrandTotal($total->getGrandTotal());
        $total->setBaseGrandTotal($total->getBaseGrandTotal());

        // Make sure that quote is also updated
        $quote->setFeeAmount($fee);
        $quote->setBaseFeeAmount($fee);

        return $this;
    }

    /**
     * @param Quote $quote
     * @param Total $total
     * @return array
     */
    public function fetch(Quote $quote, Total $total)
    {
        $fee = $total->getPaymentFee();
        $address = $this->getAddressFromQuote($quote);

        $result = [
            [
                'code' => $this->getCode(),
                'title' => __($this->_helper->getTitle()),
                'value' => $fee
            ]
        ];

        if ($this->_helper->isTaxEnabled() && $this->_helper->displayInclTax()) {
            $result [] = [
                'code' => 'payment_fee_incl_tax',
                'value' => $fee + $address->getPaymentFeeTax()
            ];
        }

        return $result;
    }

    /**
     * @param Quote $quote
     * @return Address
     */
    private function getAddressFromQuote(Quote $quote)
    {
        return $quote->isVirtual() ? $quote->getBillingAddress() : $quote->getShippingAddress();
    }

    /**
     * Get tax request for quote address
     * @param Quote $quote
     * @return \Magento\Framework\DataObject
     */
    private function _getRateTaxRequest(Quote $quote)
    {
        $rateTaxRequest = $this->taxCalculator->getRateRequest(
            $quote->getShippingAddress(),
            $quote->getBillingAddress(),
            $quote->getCustomerTaxClassId(),
            $quote->getStore(),
            $quote->getCustomerId()
        );
        return $rateTaxRequest;
    }

    /**
     * Get label
     * @return \Magento\Framework\Phrase
     */
    public function getLabel()
    {
        return __($this->_helper->getTitle());
    }
}
