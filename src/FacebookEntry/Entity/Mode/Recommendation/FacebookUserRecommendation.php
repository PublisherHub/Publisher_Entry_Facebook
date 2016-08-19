<?php

namespace Publisher\Entry\Facebook\Entity\Mode\Recommendation;

use Publisher\Mode\Recommendation\Entity\AbstractRecommendation;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Publisher\Entry\Facebook\FacebookUserEntry;

class FacebookUserRecommendation extends AbstractRecommendation
{
    
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        parent::loadValidatorMetadata($metadata);
        parent::addDefaultConstraints($metadata);
        parent::addScheduleNotSupportedConstraint($metadata);
    }
    
    /**
     * @{inheritdoc}
     */
    protected function getMaxLengthOfMessage()
    {
        return FacebookUserEntry::MAX_LENGTH_OF_MESSAGE;
    }
    
    /**
     * @{inheritdoc}
     */
    protected function createCompleteMessage()
    {
        $message = empty($this->url) ? $this->message : $this->message."\n".$this->url;
        
        return $message;
    }
    
}