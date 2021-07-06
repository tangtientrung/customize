<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */


namespace Amasty\Deliverydate\Model\ResourceModel;

abstract class DateCollectionAbstract extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Get all year from collection and collect it in array for options.
     * Used in Grid
     *
     * @param string $columnName
     *
     * @return array
     */
    public function getYearsAsArray($columnName = 'from_year')
    {
        $years = [['value' => 0, 'label' => __("Each year")]];
        $yearsForCondition = [0];
        foreach ($this as $item) {
            $getter = $this->getFunctionNameByColumn($columnName);
            if (method_exists($item, $getter)) {
                $year = $item->$getter();
            } else {
                $year = $item->getData($columnName);
            }
            if ($year && !in_array($year, $yearsForCondition)) {
                $years[] = ['value' => $year, 'label' => $year];
                $yearsForCondition[] = $year;
            }
        }

        return $years;
    }

    /**
     * convent under_score to CamelCase
     *
     * @param string $columnName
     *
     * @return string
     */
    protected function getFunctionNameByColumn($columnName)
    {
        return 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $columnName)));
    }
}
