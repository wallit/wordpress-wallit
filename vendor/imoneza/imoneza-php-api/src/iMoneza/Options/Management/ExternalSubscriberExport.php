<?php
/**
 * Requests an external subscriber export (POST)
 *
 * @author Aaron Saray
 */

namespace iMoneza\Options\Management;

use iMoneza\Data\ExternalSubscriberExportResponse;
use iMoneza\Options\ConfigurationTrait;
use iMoneza\Options\OptionsAbstract;

/**
 * Class CallbackResult
 * @package iMoneza\Options\Management
 */
class ExternalSubscriberExport extends OptionsAbstract implements ManagementInterface
{
    use ConfigurationTrait, ManagementConfigurationTrait;

    /**
     * @var string Start Date of the export
     */
    protected $StartDate;

    /**
     * @var string The end date of the export
     */
    protected $EndDate;

    /**
     * @param \DateTime $startDate
     * @return $this
     */
    public function setStartDate(\DateTime $startDate) {
        $this->StartDate = $startDate->format('c');
        return $this;
    }

    /**
     * @param \DateTime $endDate
     * @return $this
     */
    public function setEndDate(\DateTime $endDate) {
        $this->EndDate = $endDate->format('c');
        return $this;
    }

    /**
     * @return string
     */
    public function getEndPoint()
    {
        return "/api/Property/{$this->accessKey}/ExternalSubscriberExport";
    }

    /**
     * @return string
     */
    public function getRequestType()
    {
        return self::REQUEST_TYPE_POST;
    }

    /**
     * @return ExternalSubscriberExportResponse
     */
    public function getDataObject()
    {
        return new ExternalSubscriberExportResponse();
    }
}