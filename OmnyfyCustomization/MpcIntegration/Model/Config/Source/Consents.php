<?php
namespace OmnyfyCustomization\MpcIntegration\Model\Config\Source;

class Consents implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @param \Magento\Framework\Serialize\Serializer\Json
     */
    private $_json;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Serialize\Serializer\Json $json
    )
    {
        $this->_scopeConfig = $scopeConfig;
        $this->_json = $json;
    }
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];
        $json = $this->_scopeConfig->getValue('omnyfy/omnyfy/json', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if($json)
        {
            $json = $this->_json->unserialize($json);
            foreach ($json as $key => $value)
            {
                if($key == 'consents')
                {
                    foreach ($value as $k => $val)
                    {
                        $format = explode("_",$k);
                        $format = implode(" ", $format);
                        $format = ucwords($format);
                        $options[] = [
                            'label' => $format,
                            'value' => $k,
                        ];
                    }
                }

            }

        }
        return $options;
    }
}
