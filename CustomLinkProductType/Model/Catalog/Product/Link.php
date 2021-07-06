<?php

namespace AHT\CustomLinkProductType\Model\Catalog\Product;

class Link extends \Magento\Catalog\Model\Product\Link
{
    const LINK_TYPE_MOUNTING = 10;

    /**
     * @return \Magento\Catalog\Model\Product\Link $this
     */
    public function useMountingLinks()
    {
        $this->setLinkTypeId(self::LINK_TYPE_MOUNTING);
        return $this;
    }

    /**
     * Save data for product relations
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return \Magento\Catalog\Model\Product\Link
     */
    public function saveProductRelations($product)
    {
        parent::saveProductRelations($product);

        $data = $product->getMountingData();
        if (!is_null($data)) {
            $this->_getResource()->saveProductLinks($product->getId(), $data, self::LINK_TYPE_MOUNTING);
        }
        return $this;
    }
}
