<?php
namespace AHT\FastOrder\Controller\Index;

class AddCart extends \Magento\Framework\App\Action\Action
{
    protected $formKey;   
    protected $cart;
    protected $product;
    /**
     * @param \Magento\Framework\Serialize\Serializer\Json
     */
    private $_json;

    /**
     * @param \Magento\Framework\Controller\Result\JsonFactory
     */
    private $_jsonFactory;

    public function __construct(
    \Magento\Framework\App\Action\Context $context,
    \Magento\Framework\Data\Form\FormKey $formKey,
    \Magento\Checkout\Model\Cart $cart,
    \Magento\Catalog\Model\ProductFactory $product,
    \Magento\Framework\Serialize\Serializer\Json $json,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
    array $data = []) {
        $this->_json = $json;
        $this->_jsonFactory = $jsonFactory;
        $this->formKey = $formKey;
        $this->cart = $cart;
        $this->product = $product;      
        parent::__construct($context);
    }

    public function execute()
    {
        $data = $this->getRequest()->getContent();
        $response = $this->_json->unserialize($data);
        // var_dump($response);
        // die();
        foreach ($response as $value) {
            // var_dump($value['product']);
            // die();
            $productId = $value['product'];
            $params = array(
                            'form_key' => $this->formKey->getFormKey(),
                            'product' => $productId, //product Id
                            'qty'   =>$value['qty'] //quantity of product                
                        );              
            //Load the product based on productID   
            $_product = $this->product->create()->load($productId);       
            $this->cart->addProduct($_product, $params);
            
            
        }
        
        $this->cart->save();
    }
}
