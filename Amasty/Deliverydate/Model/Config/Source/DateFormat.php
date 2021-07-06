<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */

namespace Amasty\Deliverydate\Model\Config\Source;

class DateFormat implements \Magento\Framework\Option\ArrayInterface
{

    public function toOptionArray()
    {
        return [
            [
                'value' => 'yyyy-MM-dd',
                'label' => 'yyyy-mm-dd (' . date('Y-m-d') . ')'
            ],
            [
                'value' => 'MM/dd/yyyy',
                'label' => 'mm/dd/yyyy (' . date('m/d/Y') . ')'
            ],
            [
                'value' => 'dd/MM/yyyy',
                'label' => 'dd/mm/yyyy (' . date('d/m/Y') . ')'
            ],
            [
                'value' => 'd/M/yy',
                'label' => 'd/m/yy (' . date('j/n/y') . ')'
            ],
            [
                'value' => 'd/M/yyyy',
                'label' => 'd/m/yyyy (' . date('j/n/Y') . ')'
            ],
            [
                'value' => 'dd.MM.yyyy',
                'label' => 'dd.mm.yyyy (' . date('d.m.Y') . ')'
            ],
            [
                'value' => 'dd.MM.yy',
                'label' => 'dd.mm.yy (' . date('d.m.y') . ')'
            ],
            [
                'value' => 'd.M.yy',
                'label' => 'd.m.yy (' . date('j.n.y') . ')'
            ],
            [
                'value' => 'd.M.yyyy',
                'label' => 'd.m.yyyy (' . date('j.n.Y') . ')'
            ],
            [
                'value' => 'dd-MM-yy',
                'label' => 'dd-mm-yy (' . date('d-m-y') . ')'
            ],
            [
                'value' => 'yyyy.MM.dd',
                'label' => 'yyyy.mm.dd (' . date('Y.m.d') . ')'
            ],
            [
                'value' => 'dd-MM-yyyy',
                'label' => 'dd-mm-yyyy (' . date('d-m-Y') . ')'
            ],
            [
                'value' => 'yyyy/MM/dd',
                'label' => 'yyyy/mm/dd (' . date('Y/m/d') . ')'
            ],
            [
                'value' => 'yy/MM/dd',
                'label' => 'yy/mm/dd (' . date('y/m/d') . ')'
            ],
            [
                'value' => 'dd/MM/yy',
                'label' => 'dd/mm/yy (' . date('d/m/y') . ')'
            ],
            [
                'value' => 'MM/dd/yy',
                'label' => 'mm/dd/yy (' . date('m/d/y') . ')'
            ],
            [
                'value' => 'dd/MM yyyy',
                'label' => 'dd/mm yyyy (' . date('d/m Y') . ')'
            ],
            [
                'value' => 'yyyy MM dd',
                'label' => 'yyyy mm dd (' . date('Y m d') . ')'
            ],
        ];
    }
}
