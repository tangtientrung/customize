<?php


namespace AHT\CustomLinkProductType\Model;

class Product extends \Magento\Catalog\Model\Product
{
    const LINK_TYPE_MOUNTING = 10;

    public function getMountingProducts()
    {
        if (!$this->hasMountingProducts()) {

            $products = [];
            $collection = $this->getMountingProductCollection();
            foreach ($collection as $product) {
                $products[] = $product;
            }
            $this->setMountingProducts($products);
        }
        return $this->getData('mounting_products');
    }

    public function getMountingProductIds()
    {
        if (!$this->hasMountingProductIds()) {
            $ids = [];
            foreach ($this->getMountingProducts() as $product) {
                $ids[] = $product->getId();
            }
            $this->setMountingProductIds($ids);
        }
        return [$this->getData('mounting_product_ids')];
    }

    public function getMountingProductCollection()
    {
        $collection = $this->getLinkInstance()->setLinkTypeId(static::LINK_TYPE_MOUNTING)->getProductCollection()->setIsStrongMode();
        $collection->setProduct($this);
        return $collection;
    }

}
