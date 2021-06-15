<?php
namespace AHT\Checkout\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_pageFactory;
    protected $checkoutSession;
    private $quote;
    /**
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
       \Magento\Framework\App\Action\Context $context,
       \Magento\Framework\View\Result\PageFactory $pageFactory,
       \Magento\Checkout\Model\Session $checkoutSession,
       \Magento\Quote\Model\Quote $quote
    )
    {
        $this->checkoutSession = $checkoutSession;
        $this->quote = $quote;
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
        // return $this->_pageFactory->create();
        $a="aaa";
        $b="bbb";
        var_dump($a);
        // var_dump($this->checkoutSession->getQuote()->getId());
        // $allItems = $this->checkoutSession->getQuote()->getAllVisibleItems();
        // foreach ($allItems as $item) {
        //     var_dump($item->getStatus());
        // }
        // var_dump($this->quote->getId());
        // die;
    }
}
