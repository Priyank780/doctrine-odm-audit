# Doctrine ODM Audit

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/f2091721c6a343e18396ce85479c61e9)](https://www.codacy.com/app/Priyank780/doctrine-odm-audit?utm_source=github.com&utm_medium=referral&utm_content=Priyank780/doctrine-odm-audit&utm_campaign=badger)

Basic useful feature list:

 * To Store New/old value as audit in database on flush event of ODM Doctrine


You need to implement IAuditHandler interface. Provide this Implemented class object as parameter of constructor.

```php
class OdmEventManager implements IAuditHandler{
public function getPersistantRevisionObject(RevisionInfo $revisionInfo){
$revisionDoc = new RevisionDoc();
//Store revision info details into revision document
//Here you can store other details like action user infromatino in Revision document
return $revisionDoc;
}
public function getNamespaceOfDoctrineObject(){
return "Doctrine\Document";
}
public function isDeleteEventAuditEnabled()
    {
        return true;
    }

    public function isInsertEventAuditEnabled()
    {
        return true;
    }

    public function isRequireToStoreAudit($obj)
    {
        return ($obj instanceof UserDocument);
    }

    public function isUpdateEventAuditEnabled()
    {
        return true;
    }

    public function isUpsertEventAuditEnabled()
    {
        return true;
    }
```

```php
$odmAuditEventManager = new OdmAuditEventManager(new OdmEventManager());
$eventManager               = new EventManager();
$eventManager->addEventListener([Events::onFlush], $odmAuditEventManager);

```
