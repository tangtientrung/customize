<?php
namespace AHT\Fee\Block\Adminhtml\System\Form\Field;

use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Html\Select;
use Magento\Payment\Model\Config as PaymentModelConfig;
use Magento\Payment\Model\Method\Factory as PaymentMethodFactory;
use Magento\Store\Model\ScopeInterface;

class Methods extends Select
{
    /**
     * Payment methods cache
     *
     * @var array
     */
    protected $methods;

    /**
     * @var PaymentModelConfig
     */
    protected $paymentConfig;

    /**
     * @var PaymentMethodFactory
     */
    protected $paymentMethodFactory;

    /**
     * Methods constructor.
     * @param Context $context
     * @param PaymentModelConfig $config
     * @param PaymentMethodFactory $paymentMethodFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        PaymentModelConfig $config,
        PaymentMethodFactory $paymentMethodFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->paymentConfig = $config;
        $this->paymentMethodFactory = $paymentMethodFactory;
    }

    protected function _getPaymentMethods()
    {
        if ($this->methods === null) {
            $this->methods = $this->paymentConfig->getActiveMethods();
        }
        return $this->methods;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    public function _toHtml()
    {
        if (!$this->getOptions()) {
            foreach ($this->_getPaymentMethods() as $paymentCode => $paymentModel) {
                $paymentTitle = $this->_scopeConfig->getValue('payment/'.$paymentCode.'/title');
                $this->addOption($paymentCode, $paymentTitle);
            }
        }
        return parent::_toHtml();
    }
}