<?php

namespace Meetanshi\GA4\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory as AttributeCollection;

class ProductAtrribute implements ArrayInterface
{
    /**
     * @var AttributeCollection
     */
    protected $productAttributes;

    /**
     * @param AttributeCollection $productAttributes
     */
    public function __construct(AttributeCollection $productAttributes)
    {
        $this->productAttributes = $productAttributes;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $attributes = [];
        try {
            $attributeInfo = $this->productAttributes->create();
            foreach ($attributeInfo as $items) {
                array_push($attributes,
                    ['value' => $items->getData('attribute_code'), 'label' => $items->getData('attribute_code')]);
            }
        } catch (\Exception $e) {
            \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->info($e->getMessage());
        }
        return $attributes;
    }
}
