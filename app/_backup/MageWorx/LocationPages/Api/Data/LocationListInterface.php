<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\LocationPages\Api\Data;

/**
 * Location Page interface
 *
 * @api
 */
interface LocationListInterface
{
    const NAME      = 'name';
    const TYPE      = 'type';
    const PARENT_ID = 'parent_id';
    const PATH      = 'path';

    const LOCATION_LIST_TABLE   = 'mageworx_location_list';
    const CURRENT_LOCATION_LIST = 'mageworx_locationpages_current_location_list';

    /**
     * @param string $value
     * @return $this
     */
    public function setName($value);

    /**
     * @param string $value
     * @return $this
     */
    public function setType($value);

    /**
     * @param int $value
     * @return $this
     */
    public function setParentId($value);

    /**
     * @param string $value
     * @return $this
     */
    public function setPath($value);

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getType();

    /**
     * @return int
     */
    public function getParentId();

    /**
     * @return string
     */
    public function getPath();
}
