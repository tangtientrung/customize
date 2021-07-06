<?php

namespace AHT\CustomLinkProductType\Plugin;

use Magento\Catalog\Api\ProductLinkRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\Related as RelatedParent;
use Magento\Eav\Api\AttributeSetRepositoryInterface;
use Magento\Framework\UrlInterface;
use Magento\Ui\Component\Form\Fieldset;

class Related extends RelatedParent
{
    const GROUP_RELATED = 'related';
    const DATA_SCOPE_MOUNTING = 'mounting';
    private $priceModifier;
    protected $product;

    public function afterModifyMeta($modify, $result) {
        if (isset($result[static::GROUP_RELATED]['children'])) {
            $result[static::GROUP_RELATED]['children'][$modify->scopePrefix . static::DATA_SCOPE_MOUNTING] = $this->getMountingFieldset($modify);
        }
        return $result;
    }
    /**
     * Get price modifier
     *
     * @return \Magento\Catalog\Ui\Component\Listing\Columns\Price
     * @deprecated 101.0.0
     */
    private function getPriceModifier($modify) {
        if (!$this->priceModifier) {
            $this->priceModifier = \Magento\Framework\App\ObjectManager::getInstance()->get(
                \Magento\Catalog\Ui\Component\Listing\Columns\Price::class
            );
        }
        return $this->priceModifier;
    }
    /**
     * Prepares config for the Related products fieldset
     *
     * @return array
     * @since 101.0.0
     */
    protected function getMountingFieldset($modify) {
        $content = __(
            'My Mounting Product.'
        );
        return [
            'children' => [
                'button_set' => $modify->getButtonSet(
                    $content, __('Add Mounting Product'), $modify->scopePrefix . static::DATA_SCOPE_MOUNTING
                ),
                'modal' => $this->getGenericModal(
                    __('Add Mounting Product'), $modify->scopePrefix . static::DATA_SCOPE_MOUNTING
                ),
                static::DATA_SCOPE_MOUNTING => $this->getGrid($modify->scopePrefix . static::DATA_SCOPE_MOUNTING),
            ],
            'arguments' => [
                'data' => [
                    'config' => [
                        'additionalClasses' => 'admin__fieldset-section',
                        'label' => __('Mounting Product'),
                        'collapsible' => false,
                        'componentType' => Fieldset::NAME,
                        'dataScope' => '',
                        'sortOrder' => 11,
                    ],
                ],
            ]
        ];
    }
    public function afterModifyData($modify , $data)
    {
        $product = $modify->locator->getProduct();
        $productId = $product->getId();

        if (!$productId) {
            return $data;
        }
        $priceModifier = $this->getPriceModifier($modify);
        /**
         * Set field name for modifier
         */
        $priceModifier->setData('name', 'price');
        $dataScopes = $this->getDataScopes();
        $dataScopes[] = static::DATA_SCOPE_MOUNTING;
        foreach ($dataScopes as $dataScope) {
            if($dataScope == static::DATA_SCOPE_MOUNTING){
                $data[$productId]['links'][$dataScope] = [];
                foreach ($modify->productLinkRepository->getList($product) as $linkItem) {
                    if ($linkItem->getLinkType() !== $dataScope) {
                        continue;
                    }

                    /** @var \Magento\Catalog\Model\Product $linkedProduct */
                    $linkedProduct = $modify->productRepository->get(
                        $linkItem->getLinkedProductSku(),
                        false,
                        $modify->locator->getStore()->getId()
                    );
                    $data[$productId]['links'][$dataScope][] = $this->fillData($linkedProduct, $linkItem);
                }
                if (!empty($data[$productId]['links'][$dataScope])) {
                    $dataMap = $priceModifier->prepareDataSource([
                        'data' => [
                            'items' => $data[$productId]['links'][$dataScope]
                        ]
                    ]);
                    $data[$productId]['links'][$dataScope] = $dataMap['data']['items'];
                }
            }
        }

        return $data;
    }
    public function beforeGetLinkedProducts($provider, $product) {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->product = $objectManager->create('AHT\CustomLinkProductType\Model\Product');
        $currentProduct = $this->product->load($product->getId());
        return [$currentProduct];
    }
}
