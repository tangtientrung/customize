<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace AHT\HideOrderPrice\Model\Export;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Ui\Model\Export\MetadataProvider;
/**
 * Class ConvertToCsv
 */
class Csv
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
     * @var int|null
     */
    protected $pageSize = null;

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @param Filesystem $filesystem
     * @param Filter $filter
     * @param MetadataProvider $metadataProvider
     * @param int $pageSize
     * @throws FileSystemException
     */
    public function __construct(
        Filesystem $filesystem,
        Filter $filter,
        MetadataProvider $metadataProvider,
        $pageSize = 200
    ) {
        $this->filter = $filter;
        $this->directory = $filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        $this->metadataProvider = $metadataProvider;
        $this->pageSize = $pageSize;
    }

    /**
     * Returns CSV file
     *
     * @return array
     * @throws LocalizedException
     */
    public function aroundGetCsvFile(\Magento\Ui\Model\Export\ConvertToCsv $subject, callable $proceed)
    {
        $component = $this->filter->getComponent();

        if($component->getName()!=='sales_order_grid') {
            return $proceed();
        }

        $name = md5(microtime());
        $file = 'export/'. $component->getName() . $name . '.csv';

        $this->filter->prepareComponent($component);
        $this->filter->applySelectionOnTargetProvider();
        $dataProvider = $component->getContext()->getDataProvider();

        if($component->getName()=='sales_order_grid') {
            $fieldsVal = $this->metadataProvider->getFields($component);
            $removeFieldsArr = array('grand_total','base_grand_total','subtotal','total_refunded','shipping_and_handling');
            $removeHeadersArr = array('Grand Total (Base)','Grand Total (Purchased)','Subtotal','Total Refunded','Shipping and Handling');
            $headersVal = $this->metadataProvider->getHeaders($component);

            foreach($fieldsVal as $key=>$val){
                if(in_array($val,$removeFieldsArr)){
                    unset($fieldsVal[$key]);
                }
            }

            foreach($headersVal as $key=>$val){
                if(in_array($val,$removeHeadersArr)){
                    unset($headersVal[$key]);
                }
            }

            $headers = array_values($headersVal);
            $fields  = array_values($fieldsVal);

        }else{
            $headers = $this->metadataProvider->getHeaders($component);
            $fields = $this->metadataProvider->getFields($component);
        }

        $options = $this->metadataProvider->getOptions();

        $this->directory->create('export');
        $stream = $this->directory->openFile($file, 'w+');
        $stream->lock();
        $stream->writeCsv($headers);
        $i = 1;
        $searchCriteria = $dataProvider->getSearchCriteria()
            ->setCurrentPage($i)
            ->setPageSize($this->pageSize);
        $totalCount = (int) $dataProvider->getSearchResult()->getTotalCount();
        while ($totalCount > 0) {
            $items = $dataProvider->getSearchResult()->getItems();
            foreach ($items as $item) {
                $this->metadataProvider->convertDate($item, $component->getName());
                $stream->writeCsv($this->metadataProvider->getRowData($item, $fields, $options));
            }
            $searchCriteria->setCurrentPage(++$i);
            $totalCount = $totalCount - $this->pageSize;
        }
        $stream->unlock();
        $stream->close();

        return [
            'type' => 'filename',
            'value' => $file,
            'rm' => true  // can delete file after use
        ];
    }
}
