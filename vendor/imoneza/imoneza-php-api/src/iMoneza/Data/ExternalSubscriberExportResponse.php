<?php
/**
 * ExternalSubscriberExportResponse
 *
 * @author Aaron Saray
 */

namespace iMoneza\Data;

/**
 * Class ExternalSubscriberExportResponse
 * @package iMoneza\Data
 */
class ExternalSubscriberExportResponse extends DataAbstract
{
    /**
     * @var string the status of queued
     */
    const STATUS_QUEUED = 'Queued';

    /**
     * @var string the id of this request (NOT the callback ID)
     */
    protected $id;

    /**
     * @var string email notification address 
     * @deprecated
     */
    protected $notificationEmailAddress;

    /**
     * @var string the status
     */
    protected $status;

    /**
     * @var \DateTime when to query
     */
    protected $startDate;

    /**
     * @var \DateTime when to query
     */
    protected $endDate;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return ExternalSubscriberExportResponse
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     * @deprecated 
     */
    public function getNotificationEmailAddress()
    {
        return $this->notificationEmailAddress;
    }

    /**
     * @param string $notificationEmailAddress
     * @return ExternalSubscriberExportResponse
     * @deprecated 
     */
    public function setNotificationEmailAddress($notificationEmailAddress)
    {
        $this->notificationEmailAddress = $notificationEmailAddress;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return ExternalSubscriberExportResponse
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param string $startDate
     * @return ExternalSubscriberExportResponse
     */
    public function setStartDate($startDate)
    {
        $this->startDate = new \DateTime($startDate);
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param string $endDate
     * @return ExternalSubscriberExportResponse
     */
    public function setEndDate($endDate)
    {
        $this->endDate = new \DateTime($endDate);
        return $this;
    }
}
