<?php

namespace AHT\CustomLinkProductType\Model\ProductLink\CollectionProvider;

class Mounting
{
    public function getLinkedProducts($product)
    {
        return $product->getMountingProducts();
    }
}
