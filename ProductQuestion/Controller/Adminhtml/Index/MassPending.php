<?php

namespace AHT\ProductQuestion\Controller\Adminhtml\Index;

use Magento\Framework\Controller\ResultFactory;


class MassPending extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'AHT_Question::question';

    /**
     * @var $_filter
     * @var $_collectionFactory
     */
    protected $_filter;
    protected $_collectionFactory;

    /**
     * @param \Magento\Framework\App\CacheInterface
     */
    private $_cache;

    /**
     * @param \AHT\ProductQuestion\Helper\Data
     */
    private $_helperData;

    /**
     * @param \Magento\Backend\App\Action\Context
     * @param \Magento\Ui\Component\MassAction\Filter
     * @param \AHT\Question\Model\ResourceModel\Question\CollectionFactory
     * @param \Magento\Framework\App\CacheInterface
     * @param \AHT\ProductQuestion\Helper\Data
	 */
    public function __construct(
        \Magento\Backend\App\Action\Context $context, 
        \Magento\Ui\Component\MassAction\Filter $filter, 
        \AHT\ProductQuestion\Model\ResourceModel\ProductQuestion\CollectionFactory $collectionFactory,
        \Magento\Framework\App\CacheInterface $cache,
        \AHT\ProductQuestion\Helper\Data $helperData)
    {
        $this->_filter = $filter;
        $this->_collectionFactory = $collectionFactory;
        $this->_cache = $cache;
        $this->_helperData = $helperData;
        parent::__construct($context);
    }

    public function execute()
    {
        // Get collection
        $collection = $this->_filter->getCollection($this->_collectionFactory->create());

        // Update status to pending
        foreach ($collection as $item) {
            $item->setStatus(0);
            $item->save();

            // //flush cache
            // $this->_cache->clean('catalog_product_' .$item->getEntityId());
        }

        //flush cache
        $this->_helperData->flushCache();

        // Display success message
        $this->messageManager->addSuccess(__('A total of %1 record(s) have been pending.', $collection->getSize()));

        // Redirect to List page
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        
        return $resultRedirect;
    }
}