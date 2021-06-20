<?php
namespace AHT\Payoo\Controller\Index;

use Magento\Framework\HTTP\Client\Curl;

class GetBankCode extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_pageFactory;
     /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;
    protected $_resultRedirectFactory;
     /**
        * @var \Magento\Framework\HTTP\Client\Curl
        */
        protected $_curl;
    /**
     * @param \Magento\Framework\App\Action\Context $context
     */
    protected $_resultJsonFactory;
    protected $_json;
    public function __construct(
       \Magento\Framework\App\Action\Context $context,
       \Magento\Framework\View\Result\PageFactory $pageFactory,
       \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
       \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory,
       Curl $curl,
       \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
       \Magento\Framework\Serialize\Serializer\Json $json
    )
    {
        $this->_json = $json;
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_curl =$curl;
        $this->_resultRedirectFactory = $resultRedirectFactory;
        $this->_scopeConfig = $scopeConfig;
        $this->_pageFactory = $pageFactory;
        return parent::__construct($context);
    }
    /**
     * View page action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        // $business_username = $this->_scopeConfig->getValue('payment/payoo/business_username', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        // $shop_id = $this->_scopeConfig->getValue('payment/payoo/shop_id', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        // $resultRedirect = $this->_resultRedirectFactory->create();
        // $redirectLink = 'https://newsandbox.payoo.com.vn/v2/api/paynow/get-banks-partner?code=Ecommerce&url=http://localhost&id=665&seller=iss_ShopDemo&jsonCallback=0'; 
        // $resultRedirect->setUrl($redirectLink);
        // // var_dump($business_username);
        // // die();
        // return $resultRedirect;
        $response = $this->getResponseValue('https://newsandbox.payoo.com.vn/v2/api/paynow/get-banks-partner?code=Ecommerce&url=http://localhost&id=665&seller=iss_ShopDemo&jsonCallback=0');

        $response = $this->_json->unserialize($response);
        $result = $this->_resultJsonFactory->create();
		return $result->setData($response);
    }
    /**
     * Send SMS
     * @param type $mobile_no
     * @param type $body
     */
    public function getResponseValue($url)
    {
        // $url = urlencode($url);  
        $this->_curl->get($url);
        $response = $this->_curl->getBody();
        // var_dump($response);
        return $response;
    }
}
