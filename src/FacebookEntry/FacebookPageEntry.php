<?php

namespace Publisher\Entry\Facebook;

use Publisher\Entry\AbstractEntry;
use Publisher\Helper\Validator;

/**
 * @link https://developers.facebook.com/docs/pages/publishing
 * @link https://developers.facebook.com/docs/graph-api/reference/v2.6/page/feed
 * @link https://developers.facebook.com/docs/graph-api/common-scenarios
 * 
 * Even so the documentation says otherwise,
 * it is possible to tag someone without giving a place.
 */
class FacebookPageEntry extends AbstractEntry
{
    
    const MAX_LENGTH_OF_MESSAGE = 63205; 
    
    public static function getPublisherScopes()
    {
        return array('manage_pages', 'publish_pages');
    }
    
    protected function defineRequestProperties()
    {
        $this->request->setPath('/?/feed');
        $this->request->setMethod('POST');
    }
    
    protected function setParameters(array $parameters)
    {
        Validator::checkRequiredParametersAreSet(
                $parameters,
                array('pageId', 'pageAccessToken')
        );
        $this->addPageIdToPath($parameters['pageId']);
        $this->addPageAccessTokenToHeaders($parameters['pageAccessToken']);
    }
    
    protected function addPageIdToPath($pageId)
    {
        $incompletePath = $this->request->getPath();
        $path = preg_replace('/(\?)/', $pageId, $incompletePath);
        $this->request->setPath($path);
    }
    
    protected function addPageAccessTokenToHeaders(string $pageAccessToken)
    {
        $this->request->addHeaders(array(
            'Authorization' => 'OAuth '.$pageAccessToken
        ));
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
    
}