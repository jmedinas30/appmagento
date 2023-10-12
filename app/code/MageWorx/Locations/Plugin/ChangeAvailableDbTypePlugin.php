<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Plugin;

use MageWorx\GeoIP\Model\Source\DbType;

class ChangeAvailableDbTypePlugin
{
    /**
     * @param DbType $subject
     * @param array $result
     * @return array
     */
    public function afterToOptionArray(DbType $subject, $result)
    {
        foreach ($result as $key => $data) {
            if ($data['value'] == DbType::GEOIP_COUNTRY_DATABASE) {
                unset($result[$key]);
            }
        }

        return $result;
    }
}
