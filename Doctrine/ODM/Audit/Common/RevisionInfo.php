<?php

namespace Doctrine\ODM\Audit\Common;

/**
 * Description of RevisionInfo
 *
 * @author Priyank
 */
class RevisionInfo
{
    /**
     * @var string
     */
    private $eventType;

    /**
     * @var \DateTime
     */
    private $datetime;

    /**
     * @var string
     */
    private $objectId;

    /**
     * @var string Description
     */
    private $objectType;

    /**
     * @var RevisionChangeSetInfo[] Description
     */
    private $changeSets = [];

    function getEventType()
    {
        return $this->eventType;
    }

    function getDatetime(): \DateTime
    {
        return $this->datetime;
    }

    function getObjectId()
    {
        return $this->objectId;
    }

    function getObjectType()
    {
        return $this->objectType;
    }

    function getChangeSets(): array
    {
        return $this->changeSets;
    }

    function setEventType($eventType)
    {
        $this->eventType = $eventType;
    }

    function setDatetime(\DateTime $datetime)
    {
        $this->datetime = $datetime;
    }

    function setObjectId($objectId)
    {
        $this->objectId = $objectId;
    }

    function setObjectType($objectType)
    {
        $this->objectType = $objectType;
    }

    function setChangeSets($changeSets)
    {
        $this->changeSets = $changeSets;
    }

    function addChangeSets($changeSet)
    {
        $this->changeSets[] = $changeSet;
    }
}