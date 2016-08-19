<?php

namespace Publisher\Entry\Facebook;

use Publisher\Entry\AbstractEntry;
use Publisher\Mode\Recommendation\RecommendationInterface;
use Publisher\Helper\Validator;

/**
 * @link https://developers.facebook.com/docs/graph-api/reference/v2.6/user/feed
 * @link https://developers.facebook.com/docs/graph-api/common-scenarios
 * 
 * Even so the documentation says otherwise,
 * it is possible to tag someone without giving a place.
 */
class FacebookUserEntry extends AbstractEntry implements RecommendationInterface
{
    
    const MAX_LENGTH_OF_MESSAGE = 63205; 
    
    public static function getPublisherScopes()
    {
        return array('publish_actions');
    }
    
    protected function defineRequestProperties()
    {
        $this->request->setPath('/me/feed');
        $this->request->setMethod('POST');
    }
    
    protected function validateBody(array $body)
    {
        Validator::checkAnyRequiredParameter($body, array('message', 'link', 'place'));
        if (isset($body['message'])) {
            Validator::validateMessageLength($body['message'], self::MAX_LENGTH_OF_MESSAGE);
        }
    }
    
    // Implementation of MonitoredInterface
    
    public static function succeeded($response)
    {
        $object = json_decode($response);
        return (isset($object->id));
    }
    
    // Implementation of RecommendationInterface
    
    public function setRecommendationParameters(
        string $message,
        string $url = '',
        string $title = '',
        $date = null
    ) {
        $body = array();
        
        $body['message'] = empty($title) ? $message : $title."\n".$message;
        
        if (!empty($url)) {
            $body['link'] = $url;
        }
        
        $this->setBody($body);
    }
}