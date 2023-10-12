<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Model;

use MageWorx\Locations\Api\Data\LocationInterface;
use MageWorx\Locations\Model\Source\Country;
use MageWorx\Locations\Model\Source\Region;
use MageWorx\Locations\Model\Source\WorkingDay;
use Magento\CatalogRule\Model\Rule;
use Magento\CatalogRule\Model\RuleFactory;
use Magento\Framework\Exception\InputException;
use MageWorx\Locations\Model\MsiResolver\LocationSourceManagement;
use MageWorx\Locations\Model\Source\Timezone as TimezoneOptions;
use Magento\Framework\Stdlib\DateTime;
use MageWorx\Locations\Model\Source\WorkingHoursType as WorkingHoursTypeOptions;

class Location extends \Magento\Framework\Model\AbstractModel implements LocationInterface
{
    /**
     * @var \MageWorx\Locations\Helper\Data
     */
    protected $helper;

    /**
     * @var Country
     */
    protected $country;

    /**
     * @var Rule
     */
    protected $rule;

    /**
     * @var RuleFactory
     */
    protected $ruleFactory;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $timezone;

    /**
     * @var DateTime
     */
    protected $dateTime;

    /**
     * @var WorkingDay
     */
    protected $workingDays;

    /**
     * @var string
     */
    protected $arraySeparator = self::API_ARRAY_SEPARATOR;

    /**
     * @var LocationSourceManagement
     */
    protected $locationSourceManager;

    /**
     * @var WorkingHoursTypeOptions
     */
    protected $workingHoursTypeOptions;

    /**
     * Location constructor.
     *
     * @param DateTime $dateTime
     * @param LocationSourceManagement $locationSourceManager
     * @param DateTime\TimezoneInterface $timezone
     * @param RuleFactory $ruleFactory
     * @param WorkingDay $workingDays
     * @param Country $country
     * @param \MageWorx\Locations\Helper\Data $helper
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param WorkingHoursTypeOptions $workingHoursTypeOptions
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        DateTime $dateTime,
        LocationSourceManagement $locationSourceManager,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        RuleFactory $ruleFactory,
        WorkingDay $workingDays,
        Country $country,
        \MageWorx\Locations\Helper\Data $helper,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        WorkingHoursTypeOptions $workingHoursTypeOptions,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->dateTime                = $dateTime;
        $this->locationSourceManager   = $locationSourceManager;
        $this->ruleFactory             = $ruleFactory;
        $this->helper                  = $helper;
        $this->country                 = $country;
        $this->workingDays             = $workingDays;
        $this->timezone                = $timezone;
        $this->workingHoursTypeOptions = $workingHoursTypeOptions;
    }

    /**
     * Identifier getter
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->_getData($this->_idFieldName);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('MageWorx\Locations\Model\ResourceModel\Location');
    }

    /**
     * @return Rule
     */
    public function getRule()
    {
        if (!$this->rule) {
            $this->rule           = $this->ruleFactory->create();
            $conditionsSerialized = $this->getData('conditions_serialized');
            if ($conditionsSerialized) {
                $this->rule->setConditionsSerialized($conditionsSerialized);
                $this->rule->setId($this->getId());
                $this->rule->setWebsiteIds($this->helper->getWebsiteIdsFromStoreIds($this->getData('store_ids')));
            }
        }

        return $this->rule;
    }

    /**
     * @param Rule $rule
     * @return $this
     */
    public function setRule(Rule $rule)
    {
        $this->rule = $rule;

        return $this;
    }

    /**
     * @return \Magento\Rule\Model\Condition\Combine
     */
    public function getConditions()
    {
        return $this->getRule()->getConditions();
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _afterLoad()
    {
        $conditionsSerialized = $this->getData('conditions_serialized');
        if ($conditionsSerialized) {
            $this->getRule()->setConditionsSerialized($conditionsSerialized);
        }

        return parent::_afterLoad();
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->getData(LocationInterface::CODE);
    }

    /**
     * Get assign type
     *
     * @return string
     */
    public function getAssignType()
    {
        return $this->getData(LocationInterface::ASSIGN_TYPE);
    }

    /**
     * Get apply by crone
     *
     * @return bool
     */
    public function getApplyByCron()
    {
        return $this->getData(LocationInterface::APPLY_BY_CRON);
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getData(LocationInterface::NAME);
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return html_entity_decode((string)$this->getData(LocationInterface::DESCRIPTION));
    }

    /**
     * Get status
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->getData(LocationInterface::IS_ACTIVE);
    }

    /**
     * @return bool
     */
    public function getIsPickupAvailable(): bool
    {
        return (bool)$this->getData(LocationInterface::IS_PICKUP_AVAILABLE);
    }

    /**
     * Get order
     *
     * @return int
     */
    public function getOrder()
    {
        return $this->getData(LocationInterface::ORDER);
    }

    /**
     * Get image path
     *
     * @return string
     */
    public function getImagePath()
    {
        return $this->getData(LocationInterface::IMAGE_PATH);
    }

    /**
     * @return string
     */
    public function getTimezone(): string
    {
        return (string)$this->getData(LocationInterface::TIMEZONE);
    }

    /**
     * @return string
     */
    public function getCountryId()
    {
        return $this->getData(LocationInterface::COUNTRY_ID);
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        $place = $this->getCountryId();
        if ($place !== null) {
            $option = $this->country->toArray();

            if (!isset($option[$place])) {
                return false;
            }
            $place = $option[$place];
        }

        return $place;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->getData(LocationInterface::REGION);
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->getData(LocationInterface::CITY);
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->getData(LocationInterface::ADDRESS);
    }

    /**
     * @return string
     */
    public function getPostcode()
    {
        return $this->getData(LocationInterface::POSTCODE);
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->getData(LocationInterface::EMAIL);
    }

    /**
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->getData(LocationInterface::PHONE_NUMBER);
    }

    /**
     * @return string
     */
    public function getWebsiteUrl()
    {
        return $this->getData(LocationInterface::WEBSITE_URL);
    }

    /**
     * @return string
     */
    public function getSkype()
    {
        return $this->getData(LocationInterface::SKYPE);
    }

    /**
     * @return string
     */
    public function getWhatsapp()
    {
        return $this->getData(LocationInterface::WHATSAPP);
    }

    /**
     * @return string
     */
    public function getInstagram()
    {
        return $this->getData(LocationInterface::INSTAGRAM);
    }

    /**
     * @return string
     */
    public function getFacebook()
    {
        return $this->getData(LocationInterface::FACEBOOK);
    }

    /**
     * @return string
     */
    public function getLatitude()
    {
        return $this->getData(LocationInterface::LATITUDE);
    }

    /**
     * @return string
     */
    public function getLongitude()
    {
        return $this->getData(LocationInterface::LONGITUDE);
    }

    /**
     * @return string[]
     */
    public function getStoreIds()
    {
        return $this->getData(LocationInterface::STORE_IDS);
    }

    /**
     * @return string[]
     */
    public function getProductSkus()
    {
        $skus = $this->getData(LocationInterface::PRODUCT_SKUS);

        return $skus ?? [];
    }

    /**
     * @return string
     */
    public function getWorkingHoursType()
    {
        return $this->getData(LocationInterface::WORKING_HOURS_TYPE);
    }

    /**
     * @return string[]
     */
    public function getWorkingHours()
    {
        $workingHours = $this->getData(LocationInterface::WORKING_HOURS);

        return is_array($workingHours) ? $workingHours : [];
    }

    /**
     * @return string
     */
    public function getLocationPagePath()
    {
        return (string)$this->getData(LocationInterface::LOCATION_PAGE_PATH);
    }

    /**
     * @return string
     */
    public function getMetaTitle()
    {
        return (string)$this->getData(LocationInterface::META_TITLE);
    }

    /**
     * @return string
     */
    public function getMetaDescription()
    {
        return (string)$this->getData(LocationInterface::META_DESCRIPTION);
    }

    /**
     * @return string
     */
    public function getMetaKeywords()
    {
        return (string)$this->getData(LocationInterface::META_KEYWORDS);
    }

    /**
     * @return string
     */
    public function getMetaRobots()
    {
        return (string)$this->getData(LocationInterface::META_ROBOTS);
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setLocationPagePath($value)
    {
        $this->setData(LocationInterface::LOCATION_PAGE_PATH, $value);

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setCode($value)
    {
        $this->setData(LocationInterface::CODE, $value);

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setAssignType($value)
    {
        $this->setData(LocationInterface::ASSIGN_TYPE, $value);

        return $this;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setApplyByCron($value)
    {
        $this->setData(LocationInterface::APPLY_BY_CRON, $value);

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setName($value)
    {
        $this->setData(LocationInterface::NAME, $value);

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setDescription($value)
    {
        $this->setData(LocationInterface::DESCRIPTION, $value);

        return $this;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setIsActive($value)
    {
        $this->setData(LocationInterface::IS_ACTIVE, $value);

        return $this;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setIsPickupAvailable(bool $value): LocationInterface
    {
        $this->setData(LocationInterface::IS_PICKUP_AVAILABLE, $value);

        return $this;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setArraySeparator($value)
    {
        $this->arraySeparator = $value;

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setOrder($value)
    {
        $this->setData(LocationInterface::ORDER, $value);

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setImagePath($value)
    {
        $this->setData(LocationInterface::IMAGE_PATH, $value);

        return $this;
    }

    /**
     * @param string $value
     * @return LocationInterface
     */
    public function setTimezone(string $value): LocationInterface
    {
        $this->setData(LocationInterface::TIMEZONE, $value);

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setCountryId($value)
    {
        $this->setData(LocationInterface::COUNTRY_ID, $value);

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setRegion($value)
    {
        $this->setData(LocationInterface::REGION, $value);

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setCity($value)
    {
        $this->setData(LocationInterface::CITY, $value);

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setAddress($value)
    {
        $this->setData(LocationInterface::ADDRESS, $value);

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setPostcode($value)
    {
        $this->setData(LocationInterface::POSTCODE, $value);

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setEmail($value)
    {
        $this->setData(LocationInterface::EMAIL, $value);

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setPhoneNumber($value)
    {
        $this->setData(LocationInterface::PHONE_NUMBER, $value);

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setSkype($value)
    {
        $this->setData(LocationInterface::SKYPE, $value);

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setWhatsapp($value)
    {
        $this->setData(LocationInterface::WHATSAPP, $value);

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setInstagram($value)
    {
        $this->setData(LocationInterface::INSTAGRAM, $value);

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setFacebook($value)
    {
        $this->setData(LocationInterface::FACEBOOK, $value);

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setWebsiteUrl($value)
    {
        $this->setData(LocationInterface::WEBSITE_URL, $value);

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setLatitude($value)
    {
        $this->setData(LocationInterface::LATITUDE, $value);

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setLongitude($value)
    {
        $this->setData(LocationInterface::LONGITUDE, $value);

        return $this;
    }

    /**
     * @param int[] $value
     * @return $this
     */
    public function setStoreIds($value)
    {
        $this->setData(LocationInterface::STORE_IDS, $value);

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setMetaTitle(string $value)
    {
        $this->setData(LocationInterface::META_TITLE, $value);

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setMetaDescription(string $value)
    {
        $this->setData(LocationInterface::META_DESCRIPTION, $value);

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setMetaKeywords(string $value)
    {
        $this->setData(LocationInterface::META_KEYWORDS, $value);

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setMetaRobots(string $value)
    {
        $this->setData(LocationInterface::META_ROBOTS, $value);

        return $this;
    }

    /**
     * @param string[] $value
     * @return $this
     */
    public function addProductSkus($value)
    {
        $productSkus = $this->getProductSkus();
        $this->setData(LocationInterface::PRODUCT_SKUS, array_merge($productSkus, $value));

        return $this;
    }

    /**
     * @param string|string[] $value
     * @return $this
     */
    public function setProductSkus($value)
    {
        $value = is_array($value) ? $value : [$value];
        $this->setData(LocationInterface::PRODUCT_SKUS, $value);

        return $this;
    }

    /**
     * @return $this
     */
    public function beforeSave()
    {
        $this->prepareDataForSave();

        $this->setDefaultData();
        $this->checkRequiredData();

        if (!$this->getId()) {
            $this->setDateCreated($this->helper->getCurrentDate());
        }
        $this->setDateModified($this->helper->getCurrentDate());

        if ($this->getAssignType() !== LocationInterface::ASSIGN_TYPE_PRODUCTS_FROM_SOURCE) {
            $this->setSourceCode('');
        } else {
            $locationSourceManager = $this->locationSourceManager->getInstance();
            if ($locationSourceManager) {
                if ($this->getSourceCode() && $this->getOrigData('source_code') !== $this->getSourceCode()) {
                    $locationSourceManager->assignSourceToLocation($this, $this->getSourceCode());
                } else {
                    $locationSourceManager->updateLocationSourceItems($this);
                }
            }
        }

        return parent::beforeSave();
    }

    /**
     * Set default data
     */
    public function setDefaultData()
    {
        if ($this->getAssignType() === null) {
            $this->setAssignType(LocationInterface::ASSIGN_TYPE_ALL);
        }

        if ($this->getIsActive() === null) {
            $this->setIsActive(LocationInterface::ACTIVE);
        }
    }

    /**
     * @throws InputException
     */
    public function checkRequiredData()
    {
        $requiredDataError = [];

        if ($this->getCode() === null) {
            $requiredDataError[] = LocationInterface::CODE;
        }
        if ($this->getName() === null) {
            $requiredDataError[] = LocationInterface::NAME;
        }
        if ($this->getStoreIds() === null) {
            $requiredDataError[] = LocationInterface::STORE_IDS;
        }
        if ($this->getCountryId() === null) {
            $requiredDataError[] = LocationInterface::COUNTRY_ID;
        } else {
            if (!$this->getCountry()) {
                $requiredDataError[] = LocationInterface::COUNTRY_ID;
            }
        }
        if ($this->getRegion() === null) {
            $requiredDataError[] = LocationInterface::REGION;
        }
        if ($this->getCity() === null) {
            $requiredDataError[] = LocationInterface::CITY;
        }
        if ($this->getAddress() === null) {
            $requiredDataError[] = LocationInterface::ADDRESS;
        }

        $this->processRequiredDataError($requiredDataError);
    }

    /**
     * @param string[] $requiredDataError
     * @throws InputException
     */
    protected function processRequiredDataError($requiredDataError)
    {
        if (!empty($requiredDataError)) {
            $exception = new InputException();

            foreach ($requiredDataError as $property) {
                $exception->addError(__('\'%1\' is a required property or value is invalid', $property));
            }

            if ($exception->wasErrorAdded()) {
                throw $exception;
            }
        }
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function prepareDataForSave()
    {
        if (is_string($this->getStoreIds())) {
            $storeIds = explode($this->arraySeparator, $this->getStoreIds());
            $this->setStoreIds($storeIds);
        }

        if (is_string($this->getProductSkus())) {
            $skus = explode($this->arraySeparator, $this->getProductSkus());
            $this->setProductSkus($skus);
        }

        if (is_string($value = $this->getStoreCodes())) {
            $storeIds = [];
            $value    = explode($this->arraySeparator, $value);
            foreach ($value as $storeCode) {
                $id = $this->helper->getStoreIdByCode($storeCode);
                if ($id !== false) {
                    $storeIds[] = $id;
                }
            }

            $this->setStoreIds($storeIds);
        }

        $workingHours = $this->getData('working_hours') ?: [];

        if ($this->getWorkingHoursType() == LocationInterface::WORKING_EVERYDAY) {
            $days = [LocationInterface::WORKING_EVERYDAY => LocationInterface::WORKING_EVERYDAY];
        } elseif ($this->getWorkingHoursType() == LocationInterface::WORKING_PER_DAY_OF_WEEK) {
            $days = $this->workingDays->toArray();
        } else {
            $days = [];
        }

        foreach ($days as $day => $label) {
            $code = 'working_hours_' . $day;
            if ($value = $this->getData($code)) {
                $result = [];
                if ($value) {
                    if ($value == 'off') {
                        $result = [
                            'from'           => '',
                            'to'             => '',
                            'off'            => 1,
                            'lunch_from'     => '',
                            'lunch_to'       => '',
                            'has_lunch_time' => 0
                        ];
                    } else {
                        $times     = explode('||', $value);
                        $time      = explode(' - ', $times[0]);
                        $lunchTime = empty($times[1]) ? [] : explode(' - ', $times[1]);
                        $result    = [
                            'from'           => $time[0] ?? '',
                            'to'             => $time[1] ?? '',
                            'off'            => 0,
                            'has_lunch_time' => empty($lunchTime) ? 0 : 1,
                            'lunch_from'     => empty($lunchTime[0]) ? '' : $lunchTime[0],
                            'lunch_to'       => empty($lunchTime[1]) ? '' : $lunchTime[1]
                        ];
                    }
                }

                $workingHours[$day] = $result;
            }
        }

        $this->setData('working_hours', $workingHours);

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setWorkingHoursType($value)
    {
        $this->setData(LocationInterface::WORKING_HOURS_TYPE, $value);

        return $this;
    }

    /**
     * @param string[] $value
     * @return $this
     */
    public function setWorkingHours($value)
    {
        $this->setData(LocationInterface::WORKING_HOURS, $value);

        return $this;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function isOpenNow()
    {
        if ($this->getWorkingHoursType() === LocationInterface::WORKING_24_HOURS_A_DAY) {
            return true;
        }

        foreach ($this->getWorkingHours() as $day => $data) {
            if ($day == LocationInterface::WORKING_EVERYDAY) {
                return $this->isOpen($data['from'], $data['to']);
            } elseif ($day == strtolower($this->getCurrentDay())) {
                if ($data['off']) {
                    return false;
                }

                return $this->isOpen($data['from'], $data['to']);
            }
        }

        return false;
    }

    /**
     * @param string $fromStr
     * @param string $toStr
     * @return bool
     * @throws \Exception
     */
    protected function isOpen($fromStr, $toStr)
    {
        $from    = strtotime('01/01/1970 ' . $fromStr);
        $to      = strtotime('01/01/1970 ' . $toStr);
        $current = strtotime('01/01/1970 ' . $this->getCurrentTime());

        if ($from < $to) {
            if ($current > $from && $current < $to) {
                return true;
            }
        } else {
            if ($current > $from || $current < $to) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getWorkingHoursInfo()
    {
        if ($this->getWorkingHoursType() === LocationInterface::WORKING_24_HOURS_A_DAY) {
            $workingHoursLabels = $this->workingHoursTypeOptions->toArray();

            return $workingHoursLabels[LocationInterface::WORKING_24_HOURS_A_DAY];
        }

        $workingHours = $this->getWorkingHours();

        if ($this->isOpenNow()) {
            $status   = __('Open');
            $startStr = __('closes');
            if ($this->getWorkingHoursType() == LocationInterface::WORKING_EVERYDAY) {
                $info = $this->getFormattedTime($workingHours[LocationInterface::WORKING_EVERYDAY]['to']);
            } else {
                $info = $this->getFormattedTime($workingHours[strtolower($this->getCurrentDay())]['to']);
            }
        } else {
            $status   = __('Closed');
            $startStr = __('opens');
            if ($this->getWorkingHoursType() == LocationInterface::WORKING_EVERYDAY
                && isset($workingHours[LocationInterface::WORKING_EVERYDAY])) {
                $info = $this->getFormattedTime($workingHours[LocationInterface::WORKING_EVERYDAY]['from']);
            } else {
                $nextDay = false;
                $str     = '';
                $current = strtotime('01/01/1970 ' . $this->getCurrentTime());

                foreach ($workingHours as $day => $data) {
                    if (!$nextDay) {
                        if ($day == strtolower($this->getCurrentDay())) {
                            if ($current < strtotime('01/01/1970 ' . $data['from'])) {
                                $str .= ' ' . $this->getFormattedTime($data['from']);
                                break;
                            } else {
                                $nextDay = true;
                            }
                        }
                    } elseif (!$data['off']) {
                        $str .= ' ' . __(ucfirst($day)) . ' ' . $this->getFormattedTime($data['from']);
                        break;
                    }
                }

                $info = $str;
            }
        }

        if ($info) {
            return $status . ' | ' . $startStr . ' ' . $info;
        }

        return '';
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setSourceCode(string $value): LocationInterface
    {
        $this->setData(LocationInterface::SOURCE_CODE, $value);

        return $this;
    }

    /**
     * @return string
     */
    public function getSourceCode(): ?string
    {
        return $this->getData(LocationInterface::SOURCE_CODE);
    }

    /**
     * @return $this
     * @throws InputException
     */
    public function validateRequiredFields()
    {
        foreach (LocationInterface::REQUIRED_FIELDS as $field) {
            if ($this->getData($field) == null) {
                throw new InputException(__('Field "%1" is required and cannot be empty.', $field));
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getPreparedDataForCustomer()
    {
        $data['mw_store_' . LocationInterface::NAME]          = $this->getName();
        $data['mw_store_' . LocationInterface::DESCRIPTION]   = $this->getDescription();
        $data['mw_store_' . 'prepared_address']               = $this->getPreparedAddress();
        $data['mw_store_' . LocationInterface::PHONE_NUMBER]  = $this->getPhoneNumber();
        $data['mw_store_' . LocationInterface::SKYPE]         = $this->getSkype();
        $data['mw_store_' . LocationInterface::EMAIL]         = $this->getEmail();
        $data['mw_store_' . LocationInterface::WEBSITE_URL]   = $this->getWebsiteUrl();
        $data['mw_store_' . LocationInterface::WHATSAPP]      = $this->getWhatsapp();
        $data['mw_store_' . LocationInterface::WORKING_HOURS] = $this->getPreparedWorkingHours();
        $data['mw_store_' . LocationInterface::FACEBOOK]      = $this->getPageFullUrl();

        return $data;
    }

    /**
     * @return bool
     */
    public function getIsWorkingHoursEnabled(): bool
    {
        return (bool)$this->getData(LocationInterface::IS_WORKING_HOURS_ENABLED);
    }

    /**
     * @param boolean $value
     * @return $this
     */
    public function setIsWorkingHoursEnabled(bool $value): LocationInterface
    {
        $this->setData(LocationInterface::IS_WORKING_HOURS_ENABLED, $value);

        return $this;
    }

    /**
     * @return array
     */
    public function getFormattedWorkingHours(): array
    {
        $result = [];

        foreach ($this->getWorkingHours() as $day => $time) {
            if (!$time['off']) {
                $time['from'] = $this->getFormattedTime($time['from']);
                $time['to']   = $this->getFormattedTime($time['to']);

                if ($time['has_lunch_time']) {
                    $time['lunch_from'] = $this->getFormattedTime($time['lunch_from']);
                    $time['lunch_to']   = $this->getFormattedTime($time['lunch_to']);
                }
            }

            $result[ucfirst($day)] = $time;
        }

        return $result;
    }

    /**
     * @return string
     */
    protected function getPreparedAddress()
    {
        $region  = $this->getRegion() == Region::NO_REGIONS ? '' : $this->getRegion();
        $address = $this->getAddress() . ', ' . $this->getCity() . ', ' .
            $region . ', ' . $this->getPostcode() . ', ' . $this->getCountry();

        return $address;
    }

    /**
     * @return string
     */
    protected function getPreparedWorkingHours()
    {
        $result = '';

        foreach ($this->getFormattedWorkingHours() as $day => $time) {
            $result .= __($day) . ': ';
            if ($time['off']) {
                $result .= __('Closed');
            } else {
                $result .= $time['from'] . ' - ' . $time['to'];
            }
            $result .= '; ';
        }

        return $result;
    }

    /**
     * @param string $format
     * @return string
     * @throws \Exception
     */
    protected function getCurrentTime(string $format = 'g:i a'): string
    {
        return $this->getFormattedCurrentDateTime($format);
    }

    /**
     * @param string $format
     * @return string
     * @throws \Exception
     */
    protected function getCurrentDay(string $format = 'l'): string
    {
        return $this->getFormattedCurrentDateTime($format);
    }

    /**
     * @param string $format
     * @return string
     * @throws \Exception
     */
    protected function getFormattedCurrentDateTime(string $format): string
    {
        if ($this->getTimezone() && $this->getTimezone() !== TimezoneOptions::USE_DEFAULT) {
            $timezone = $this->getTimezone();
        } else {
            $timezone = $this->timezone->getConfigTimezone();
        }

        return (new \DateTime('now', new \DateTimeZone($timezone)))->format($format);
    }

    /**
     * @param string $time
     * @return string
     */
    protected function getFormattedTime(string $time): string
    {
        if ($this->dateTime->isEmptyDate($time)) {
            return $time;
        }

        return $this->timezone->formatDateTime(
            $time,
            \IntlDateFormatter::NONE,
            \IntlDateFormatter::SHORT,
            null,
            $this->timezone->getDefaultTimezone()
        );
    }
}
