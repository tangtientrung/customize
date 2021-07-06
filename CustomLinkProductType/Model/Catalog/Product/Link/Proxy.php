<?php

namespace AHT\CustomLinkProductType\Model\Catalog\Product\Link;

class Proxy extends \Magento\Catalog\Model\Product\Link\Proxy
{
    /**
     * {@inheritdoc}
     */
    public function useMountingLinks()
    {
        return $this->_getSubject()->useMountingLinks();
    }
}
