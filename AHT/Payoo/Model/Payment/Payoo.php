<?php
namespace AHT\Payoo\Model\Payment;
class Payoo extends  \Magento\Payment\Model\Method\AbstractMethod
{
    /**
     * Payment code
     *
     * @var string
     */
    protected $_code = 'payoo';
    
}