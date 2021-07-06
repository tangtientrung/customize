<?php
 
namespace AHT\ProductQuestion\Ui\Component\Listing\Column;
 
use Magento\Ui\Component\Listing\Columns\Column;
 
class Action extends Column
{
    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        $obj = \Magento\Framework\App\ObjectManager::getInstance();
        $store = $obj->create('\Magento\Store\Model\StoreManagerInterface');
        $url = $store->getStore()->getBaseUrl();
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $item[$this->getData('name')] = [
                    'answer' => [
                        'href' => $url.'admin/product_question/index/answer/question_id/'.$item["entity_id"],
                        'label' => __('Answer')
                    ],
                    'delete' => [
                        'href' => $url.'admin/product_question/index/delete/id/'.$item["entity_id"],
                        'label' => __('Delete')
                    ]
                ];
               
            }
        }
        return $dataSource;
    }
}
 