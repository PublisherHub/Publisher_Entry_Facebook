<?php

namespace Publisher\Entry\Facebook\Entity\Mode\Recommendation;

use Publisher\Mode\Recommendation\Entity\AbstractRecommendation;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Publisher\Entry\Facebook\FacebookPageEntry;
use Publisher\Helper\Validator;

class FacebookPageRecommendation extends AbstractRecommendation
{
    
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        parent::loadValidatorMetadata($metadata);
        parent::addDefaultConstraints($metadata);
        // Facebook Pages support scheduled publishing
        self::addDateConstraints($metadata);
    }
    
    protected static function addDateConstraints(ClassMetadata $metadata)
    {
        $metadata->addConstraint(new Assert\Callback('validateSchedule'));
    }
    
    public function validateSchedule(
        ExecutionContextInterface $context,
        $payload
    ) {
        
        $valid = Validator::getScheduleValidation($this->date, 'PT15M', 'P6M');
        
        if ($valid === false)
        {
            $context->buildViolation($this->getScheduleViolationMessage())
                ->atPath('date')
                ->addViolation();
        }
    }
    
    protected function getScheduleViolationMessage()
    {
        return "Choose a date between 15 minutes and 6 months ahead from now (UTC).";
    }
    
    protected function getMaxLengthViolationMessage(int $max)
    {
        return "Message and title combined shouldn't exceed $max characters.";
    }
    
    /**
     * @{inheritdoc}
     */
    protected function getMaxLengthOfMessage()
    {
        return FacebookPageEntry::MAX_LENGTH_OF_MESSAGE;
    }
    
    /**
     * @{inheritdoc}
     */
    protected function createCompleteMessage()
    {
        $message = empty($this->title) ? $this->message : $this->title."\n".$this->message;
        
        return $message;
    }
    
}