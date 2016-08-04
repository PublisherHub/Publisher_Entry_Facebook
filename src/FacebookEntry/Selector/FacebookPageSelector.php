<?php

namespace Publisher\Entry\Facebook\Selector;

use Publisher\Selector\Parameter\AbstractSelector;
use Publisher\Requestor\Request;

use Publisher\Selector\Selection;

/**
 * @link https://developers.facebook.com/docs/pages/publishing
 * @link https://developers.facebook.com/docs/pages/access-tokens
 * @link https://developers.facebook.com/docs/pages/getting-started#get_token
 */
class FacebookPageSelector extends AbstractSelector
{
    
    public function getParameters()
    {
        if ($this->isParameterMissing()) {
            return null;
        } else {
            return $this->results;
        }
    }
    
    public function isParameterMissing()
    {
        return (!isset($this->results['pageAccessToken']) ||
                !isset($this->results['pageId'])
        );
    }
    
    /**
    * @link https://developers.facebook.com/docs/pages/access-tokens
    * @link https://developers.facebook.com/docs/pages/getting-started#get_token
    */
    protected function defineSteps()
    {
        $this->steps[0] = function (array $results) {
            return new Request('/me/accounts', 'GET'); // get Pages
        };
        $this->steps[1] = function (array $results) {
            return new Request('/'.$results['pageId'].'?fields=access_token', 'GET');
        };
    }
    
    protected function matchParameter(array $choices)
    {
        if (isset($choices['pageId'])) {
            $this->setResult(0, 'pageId', $choices['pageId']);
        }
    }
    
    protected function saveResult(int $stepId, string $response)
    {
        switch ($stepId) {
            case 0:
                $this->savePageId($response);
                $this->updateSelections();
                break;
            case 1:
                $this->savePageAccessToken($response);
                $this->updateResults();
                break;
        }
    }
    
    protected function savePageId(string $response)
    {
        $choices = $this->parseNameAndId($response);
        $this->selections[0] = new Selection('pageId', $choices);
    }
    
    protected function savePageAccessToken(string $response)
    {   
        $response = json_decode($response);
        $this->setResult(1, 'pageAccessToken', $response->access_token);
    }
    
    protected function parseNameAndId($response)
    {
        $set = json_decode($response);
        
        $choices = array();
        foreach ($set->data as $item) {
            $choices [$item->name] = $item->id;
        }
        return $choices;
    }
}

