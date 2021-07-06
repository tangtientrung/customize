<?php
namespace AHT\Checkout\Controller\Index;

class SaveQuote extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_pageFactory;

    /**
     * @param \Magento\Quote\Model\QuoteRepository $quoteRepository
     */
    private $_quoteRepository;

    /**
     * @param \Magento\Framework\Controller\Result\JsonFactory
     */
    private $_jsonFactory;

    /**
     * @param \Magento\Framework\Serialize\Serializer\Json
     */
    private $_json;

    /**
     * @param \Magento\Quote\Model\QuoteFactory
     */
    private $_quoteFactory;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
       \Magento\Framework\App\Action\Context $context,
       \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Quote\Model\QuoteRepository $quoteRepository,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        \Magento\Framework\Serialize\Serializer\Json $json,
        \Magento\Quote\Model\QuoteFactory $quoteFactory
    )
    {
        $this->_pageFactory = $pageFactory;
        $this->_quoteRepository = $quoteRepository;
        $this->_jsonFactory = $jsonFactory;
        $this->_json = $json;
        $this->_quoteFactory = $quoteFactory;
        return parent::__construct($context);
    }
    /**
     * View page action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getContent();
        $response = $this->_json->unserialize($data);
        $quoteId = $response['quoteId'];
        $quote = $this->_quoteRepository->get($quoteId); // Get quote by id
        $quote->setData('delivery_date', $response['date']); // Fill data
        $quote->setData('delivery_comment', $response['comment']); // Fill data
        $this->_quoteRepository->save($quote);
        // $quote = $this->_quoteFactory->create()->load($quoteId);
        // $quote->setDeliveryDate($response['date']);
        // $quote->setDeliveryComment($response['comment']);
        // $quote->save();
    }
}
