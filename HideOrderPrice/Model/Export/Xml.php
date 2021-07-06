<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace AHT\HideOrderPrice\Model\Export;

use Magento\Framework\Api\Search\DocumentInterface;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Convert\Excel;
use Magento\Framework\Convert\ExcelFactory;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Ui\Model\Export\MetadataProvider;
use Magento\Ui\Model\Export\SearchResultIteratorFactory;

/**
 * Class ConvertToXml
 */
class Xml
{
    /**
     * @var DirectoryList
     */
    protected $directory;

    /**
     * @var MetadataProvider
     */
    protected $metadataProvider;

    /**
     * @var ExcelFactory
     */
    protected $excelFactory;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var SearchResultIteratorFactory
     */
    protected $iteratorFactory;

    /**
     * @var array
     */
    protected $fields;

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @param Filesystem $filesystem
     * @param Filter $filter
     * @param MetadataProvider $metadataProvider
     * @param ExcelFactory $excelFactory
     * @param SearchResultIteratorFactory $iteratorFactory
     * @throws FileSystemException
     */
    public function __construct(
        Filesystem $filesystem,
        Filter $filter,
        MetadataProvider $metadataProvider,
        ExcelFactory $excelFactory,
        SearchResultIteratorFactory $iteratorFactory
    ) {
        $this->filter = $filter;
        $this->directory = $filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        $this->metadataProvider = $metadataProvider;
        $this->excelFactory = $excelFactory;
        $this->iteratorFactory = $iteratorFactory;
    }

    /**
     * Returns Filters with options
     *
     * @return array
     */
    protected function getOptions()
    {
        if (!$this->options) {
            $this->options = $this->metadataProvider->getOptions();
        }
        return $this->options;
    }

    /**
     * Returns DB fields list
     *
     * @return array
     * @throws LocalizedException
     */
    protected function getFields()
    {
        if (!$this->fields) {
            $component = $this->filter->getComponent();
            $this->fields = $this->metadataProvider->getFields($component);
        }
        return $this->fields;
    }

    /**
     * Returns row data
     *
     * @param DocumentInterface $document
     * @return array
     * @throws LocalizedException
     */
    public function getRowData(DocumentInterface $document)
    {
        return $this->metadataProvider->getRowData($document, $this->getFields(), $this->getOptions());
    }

    /**
     * Returns XML file
     *
     * @return array
     * @throws LocalizedException
     */
    public function aroundGetXmlFile(\Magento\Ui\Model\Export\ConvertToXml $subject, callable $proceed)
    {
        $component = $this->filter->getComponent();

        if($component->getName()!=='sales_order_grid') {
            return $proceed();
        }

        $name = md5(microtime());
        $file = 'export/'. $component->getName() . $name . '.xml';

        $this->filter->prepareComponent($component);
        $this->filter->applySelectionOnTargetProvider();

        $component->getContext()->getDataProvider()->setLimit(0, 0);

        /** @var SearchResultInterface $searchResult */
        $searchResult = $component->getContext()->getDataProvider()->getSearchResult();

        /** @var DocumentInterface[] $searchResultItems */
        $searchResultItems = $searchResult->getItems();

        $this->prepareItems($component->getName(), $searchResultItems);

        if($component->getName()=='sales_order_grid') {
            $fieldsVal = $searchResultItems;
            $removeFieldsArr = array('grand_total','base_grand_total','subtotal','total_refunded','base_total_paid','total_paid','shipping_and_handling');
            foreach($fieldsVal as $result)
            {
                $data = $result->getData();
                foreach ($data as $key => $val)
                {
                     if(in_array($key, $removeFieldsArr)) {
                         unset($data[$key]);
                     }

                }
                $result->setData($data);
            }
            $fields  = $fieldsVal;

            $removeHeadersArr = array('Grand Total (Base)','Grand Total (Purchased)','Subtotal','Total Refunded','Shipping and Handling');
            $headersVal = $this->metadataProvider->getHeaders($component);

            foreach($headersVal as $key=>$val){
                if(in_array($val,$removeHeadersArr)){
                    unset($headersVal[$key]);
                }

            }
            $headers = array_values($headersVal);


        }else{
            $headers = $this->metadataProvider->getHeaders($component);
            $fields = $searchResultItems;
        }

        /** @var SearchResultIterator $searchResultIterator */
        $searchResultIterator = $this->iteratorFactory->create(['items' => $fields]);

        /** @var Excel $excel */
        $excel = $this->excelFactory->create(
            [
                'iterator' => $searchResultIterator,
                'rowCallback'=> [$this, 'getRowData'],
            ]
        );

        $this->directory->create('export');
        $stream = $this->directory->openFile($file, 'w+');
        $stream->lock();

        $excel->setDataHeader($headers);
        $excel->write($stream, $component->getName() . '.xml');

        $stream->unlock();
        $stream->close();

        return [
            'type' => 'filename',
            'value' => $file,
            'rm' => true  // can delete file after use
        ];
    }

    /**
     * @param string $componentName
     * @param array $items
     * @return void
     */
    protected function prepareItems($componentName, array $items = [])
    {
        foreach ($items as $document) {
            $this->metadataProvider->convertDate($document, $componentName);
        }
    }
}
