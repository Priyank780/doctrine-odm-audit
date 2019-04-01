<?php

namespace Doctrine\ODM\Audit\EventManager;

use Doctrine\ODM\Audit\Common\RevisionInfo;

/**
 *
 * @author Priyank
 */
interface IAuditHandler
{

    /**
     *
     * @param RevisionInfo $revisionInfo
     * @return mixed Return PersistentObject
     */
    function getPersistantRevisionObject(RevisionInfo $revisionInfo);

    /**
     * Check Doctrine Object is require to store audit
     * @param object $obj
     * @return boolean
     */
    function isRequireToStoreAudit($obj);

    /**
     * @return boolean Is Update Event Audit Enabled
     */
    function isUpdateEventAuditEnabled();

    /**
     * @return boolean Is Insert Event Audit Enabled
     */
    function isInsertEventAuditEnabled();

    /**
     * @return boolean Is Delete Event Audit Enabled
     */
    function isDeleteEventAuditEnabled();

    /**
     * @return boolean Is Upsert Event Audit Enabled
     */
    function isUpsertEventAuditEnabled();

    /**
     * @return string
     */
    function getNamespaceOfDoctrineObject();
}