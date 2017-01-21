<?php

namespace Doctrine\ODM\Audit\Common;

/**
 * Description of RevisionChangeSetInfo
 *
 * @author Priyank
 */
class RevisionChangeSetInfo
{
    /**
     *
     * @var object
     */
    private $oldValue;

    /**
     *
     * @var object
     */
    private $newValue;

    /**
     *
     * @var string
     */
    private $fieldName;

    function getOldValue()
    {
        return $this->oldValue;
    }

    function getNewValue()
    {
        return $this->newValue;
    }

    function getFieldName()
    {
        return $this->fieldName;
    }

    function setOldValue($oldValue)
    {
        $this->oldValue = $oldValue;
    }

    function setNewValue($newValue)
    {
        $this->newValue = $newValue;
    }

    function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;
    }
}