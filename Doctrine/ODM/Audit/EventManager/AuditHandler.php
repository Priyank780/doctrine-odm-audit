<?php

namespace Doctrine\ODM\Audit\EventManager;

use Doctrine\ODM\Audit\Common\RevisionInfo;

/**
 * Description of AuditHandler
 *
 * @author Priyank
 */
class AuditHandler implements IAuditHandler
{

    public function getPersistantRevisionObject(RevisionInfo $revisionInfo)
    {
        return null;
    }

    public function isRequireToStoreAudit($obj)
    {
        return true;
    }

    public function isDeleteEventAuditEnabled()
    {
        return true;
    }

    public function isInsertEventAuditEnabled()
    {
        return true;
    }

    public function isUpdateEventAuditEnabled()
    {
        return true;
    }

    public function isUpsertEventAuditEnabled()
    {
        return true;
    }

    public function getNamespaceOfDoctrineObject()
    {
        return "Doctrine\Document";
    }
}