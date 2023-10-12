<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Model\Source;

use MageWorx\Locations\Model\Source;

class MetaRobots extends Source
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'INDEX, FOLLOW', 'label' => 'INDEX, FOLLOW'],
            ['value' => 'INDEX, NOFOLLOW', 'label' => 'INDEX, NOFOLLOW'],
            ['value' => 'NOINDEX, FOLLOW', 'label' => 'NOINDEX, FOLLOW'],
            ['value' => 'NOINDEX, NOFOLLOW', 'label' => 'NOINDEX, NOFOLLOW'],
            ['value' => 'INDEX, FOLLOW, NOARCHIVE', 'label' => 'INDEX, FOLLOW, NOARCHIVE'],
            ['value' => 'INDEX, NOFOLLOW, NOARCHIVE', 'label' => 'INDEX, NOFOLLOW, NOARCHIVE'],
            ['value' => 'NOINDEX, NOFOLLOW, NOARCHIVE', 'label' => 'NOINDEX, NOFOLLOW, NOARCHIVE'],
        ];
    }

    /**
     * @return array
     */
    public function toOptionArrayWithDefault()
    {
        $res = $this->toOptionArray();
        $add = ['value' => '', 'label' => 'Use Config'];
        array_push($res, $add);

        return $res;
    }
}
