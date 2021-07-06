<?php
namespace AHT\Wholesale\Block\Adminhtml\Order;

class Order extends \Magento\Framework\View\Element\Template
{
    const ADMIN_RESOURCE = 'Magento_Sales::actions_edit';
    /**
     * Address form template
     *
     * @var string
     */
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    protected $_checkoutSession;

    /**
     * @param \Magento\Sales\Model\Order
     */
    private $_order;

    /**
     * @param \Magento\Framework\Data\Form\FormKey
     */
    private $_formKey;

    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Sales\Model\OrderFactory $order,
        \Magento\Framework\Data\Form\FormKey $formKey,
        array $data = []
    ) {
        $this->_order = $order;
        $this->_formKey = $formKey;
        $this->_checkoutSession = $checkoutSession;
        parent::__construct($context, $data);
    }

    public function getOrder()
    {
        $id = $this->getOrderId();
        $order = $this->_order->create()->load($id);
        return $order;
    }

    public function getOrderId()
    {
        return $this->getRequest()->getParam('order_id');
    }

    public function getFormKey()
    {
        return $this->_formKey->getFormKey();
    }

}
