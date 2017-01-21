<?php

namespace Doctrine\ODM\Audit\EventManager;

use DateTime;
use Doctrine\ODM\Audit\Common\RevisionChangeSetInfo;
use Doctrine\ODM\Audit\Common\RevisionInfo;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ODM\MongoDB\PersistentCollection;
use Doctrine\ODM\MongoDB\UnitOfWork;

/**
 * Description of OdmAuditEventManager
 *
 * @author Priyank
 */
class OdmAuditEventManager
{
    private $auditHandler;

    const NOT_APPLICABLE = "Not Applicable";

    public function __construct(IAuditHandler $auditHandler)
    {
        $this->auditHandler = $auditHandler;
    }

    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $dm  = $eventArgs->getDocumentManager();
        $uow = $dm->getUnitOfWork();
        if ($this->auditHandler->isUpdateEventAuditEnabled()) {
            foreach ($uow->getScheduledDocumentUpdates() as $obj) {
                $this->persistRevisionObject($obj, $dm, "Update");
            }
        }
        if ($this->auditHandler->isInsertEventAuditEnabled()) {
            foreach ($uow->getScheduledDocumentInsertions() as $obj) {
                $this->persistRevisionObject($obj, $dm, "Insert");
            }
        }
        if ($this->auditHandler->isDeleteEventAuditEnabled()) {
            foreach ($uow->getScheduledDocumentDeletions() as $obj) {
                $this->persistRevisionObject($obj, $dm, "Delete");
            }
        }
        if ($this->auditHandler->isUpsertEventAuditEnabled()) {
            foreach ($uow->getScheduledDocumentUpserts() as $obj) {
                $this->persistRevisionObject($obj, $dm, "Upsert");
            }
        }
        $uow->computeChangeSets();
    }

    /**
     *
     * @param type $obj
     * @param DocumentManager $dm
     * @param string $eventType
     */
    private function persistRevisionObject($obj, DocumentManager $dm, $eventType)
    {
        if ($this->auditHandler->isRequireToStoreAudit($obj)) {
            $revisionObject = $this->getPersistentRevisionObject($eventType, $obj, $dm->getUnitOfWork());
            if (!is_null($revisionObject)) {
                $dm->persist($revisionObject);
            }
        }
    }

    /**
     *
     * @param type $eventType
     * @param type $obj
     * @param UnitOfWork $unitOfWork
     */
    private function getPersistentRevisionObject($eventType, $obj, UnitOfWork $unitOfWork)
    {
        $revisionInfo = new RevisionInfo();
        $revisionInfo->setEventType($eventType);
        $revisionInfo->setObjectType(get_class($obj));
        $revisionInfo->setObjectId($unitOfWork->getDocumentIdentifier($obj));
        $revisionInfo->setDatetime(new DateTime());
        foreach ($unitOfWork->getDocumentChangeSet($obj) as $fieldName => $changeSet) {
            $changeSetInfo = new RevisionChangeSetInfo();
            $changeSetInfo->setFieldName($fieldName);
            $oldValue      = isset($changeSet[0]) ? $this->getUpdatedValue($changeSet[0], true, $unitOfWork) : null;
            $newValue      = isset($changeSet[1]) ? $this->getUpdatedValue($changeSet[1], false, $unitOfWork) : null;
            $changeSetInfo->setOldValue($oldValue);
            $changeSetInfo->setNewValue($newValue);
            $revisionInfo->addChangeSets($changeSetInfo);
        }
        return $this->auditHandler->getPersistantRevisionObject($revisionInfo);
    }

    /**
     *
     * @param mixed $value
     * @param boolean $isOldValue
     */
    private function getUpdatedValue($value, $isOldValue, UnitOfWork $unitOfWork)
    {
        if ($value instanceof PersistentCollection) {
            if ($isOldValue) {
                $value = self::NOT_APPLICABLE;
            } else {
                $value = $this->getPersistentCollectionToArray($value, $unitOfWork);
            }
        } else if ($value instanceof DateTime) {
            $value = CalendarUtility::getStandardDateFormat($value);
        } else if (is_object($value)) {
            $value = $this->updateRecursively($value, $unitOfWork);
        }
        return $value;
    }

    /**
     *
     * @param type $value
     * @param UnitOfWork $unitOfWork
     * @return string
     */
    private function updateRecursively($value, UnitOfWork $unitOfWork)
    {
        if (is_object($value)) {
            if ($this->stringContains(get_class($value), $this->auditHandler->getNamespaceOfDoctrineObject())) {
                $value = $unitOfWork->getDocumentActualData($value);
            } else {
                $value = self::NOT_APPLICABLE;
            }
        }
        if (is_array($value)) {
            foreach ($value as &$val) {
                $val = $this->updateRecursively($val, $unitOfWork);
            }
        }
        return $value;
    }

    /**
     *
     * @param PersistentCollection $value
     * @param UnitOfWork $unitOfWork
     * @return mixed
     */
    private function getPersistentCollectionToArray(PersistentCollection $value, UnitOfWork $unitOfWork)
    {
        $valueArray = [];
        foreach ($value->getValues() as $obj) {
            $valueArray[] = $unitOfWork->getDocumentActualData($obj);
        }
        return $valueArray;
    }

    /**
     *
     * @param string $string
     * @param string $contains
     * @return boolean
     */
    private function stringContains($string, $contains)
    {
        return (strpos($string, $contains) !== false);
    }
}