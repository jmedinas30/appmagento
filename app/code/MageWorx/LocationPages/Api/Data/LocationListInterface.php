<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */
declare(strict_types=1);

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
    const LATITUDE  = 'latitude';
    const LONGITUDE = 'longitude';

    const LOCATION_LIST_TABLE   = 'mageworx_location_list';
    const CURRENT_LOCATION_LIST = 'mageworx_locationpages_current_location_list';

    /**
     * @param string $value
     * @return $this
     */
    public function setName(string $value): LocationListInterface;

    /**
     * @param string $value
     * @return $this
     */
    public function setType(string $value): LocationListInterface;

    /**
     * @param int $value
     * @return $this
     */
    public function setParentId(int $value): LocationListInterface;

    /**
     * @param string $value
     * @return $this
     */
    public function setPath(string $value): LocationListInterface;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return int
     */
    public function getParentId(): int;

    /**
     * @return string
     */
    public function getPath(): string;

    /**
     * @return string
     */
    public function getLatitude(): string;

    /**
     * @return string
     */
    public function getLongitude(): string;

    /**
     * @param string $value
     * @return $this
     */
    public function setLatitude($value): LocationListInterface;

    /**
     * @param string $value
     * @return $this
     */
    public function setLongitude($value): LocationListInterface;
}
