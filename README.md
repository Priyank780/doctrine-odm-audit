# Doctrine ODM Audit

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/f2091721c6a343e18396ce85479c61e9)](https://www.codacy.com/app/Priyank780/doctrine-odm-audit?utm_source=github.com&utm_medium=referral&utm_content=Priyank780/doctrine-odm-audit&utm_campaign=badger)
[![Latest Stable Version](https://poser.pugx.org/priyank/doctrine-odm-audit/v/stable)](https://packagist.org/packages/priyank/doctrine-odm-audit)
[![Total Downloads](https://poser.pugx.org/priyank/doctrine-odm-audit/downloads)](https://packagist.org/packages/priyank/doctrine-odm-audit)
[![Latest Unstable Version](https://poser.pugx.org/priyank/doctrine-odm-audit/v/unstable)](https://packagist.org/packages/priyank/doctrine-odm-audit)
[![License](https://poser.pugx.org/priyank/doctrine-odm-audit/license)](https://packagist.org/packages/priyank/doctrine-odm-audit)
[![composer.lock](https://poser.pugx.org/priyank/doctrine-odm-audit/composerlock)](https://packagist.org/packages/priyank/doctrine-odm-audit)

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
