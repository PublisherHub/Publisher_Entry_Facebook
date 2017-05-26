<?php

namespace Publisher\Entry\Facebook\Selection;

use Publisher\Selector\Selection\SelectorDefinition;

class FacebookPageSelectorDefinition extends SelectorDefinition
{
    
    protected function defineDecisionOrder()
    {
        $this->decisionOrder = ['pageId', 'pageAccessToken'];
    }
    
    /**
     * {@inheritDoc}
     * 
     * @link https://developers.facebook.com/docs/pages/access-tokens
     * @link https://developers.facebook.com/docs/pages/getting-started#get_token
     */
    protected function defineSteps()
    {
        $this->steps[0] = function (array $decisions) {
            return new Request('/me/accounts', 'GET'); // get Pages
        };
        $this->steps[1] = function (array $decisions) {
            return new Request('/' . $decisions['pageId'] . '?fields=access_token', 'GET');
        };
    }
    
    /**
     * {@inheritDoc}
     */
    public function getRequiredParameters(array $decisions)
    {
        return [
            'pageAccessToken' => $decisions['pageAccessToken'],
            'pageId' => $decisions['pageId']
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function isDecisionMissing(array $decisions)
    {
        return !($decisions['pageAccessToken'] && $decisions['pageId']);
    }
    
    /**
     * {@inheritDoc}
     */
    public function updateDecisions(
        SelectionCollectionInterface $selectionCollection,
        string $response
    ) {
        $stepId = $selectionCollection->getCurrentStepId();
        
        switch ($stepId) {
            case 0:
                $decisions = $this->parsePageIds($response);
                $selectionCollection->addSelection('pageId', $decisions);
                break;
            case 1:
                $accessToken = $this->parsePageAccessToken($response);
                $selectionCollection->setDecision('pageAccessToken', $accessToken);
                break;
        }
    }
    
    /**
     * @param string $response
     * 
     * @return string
     */
    protected function parsePageIds(string $response)
    {
        return $this->parseNameAndId($response);
    }
    
    /**
     * @param string $response
     * 
     * @return string
     */
    
    protected function parsePageAccessToken(string $response)
    {   
        $response = json_decode($response);
        
        return $response->access_token;
    }
    
    /**
     * @param string $response
     * 
     * @return array associative array
     */
    protected function parseNameAndId(string $response)
    {
        $set = json_decode($response);
        
        $options = array();
        foreach ($set->data as $item) {
            $options[$item->name] = $item->id;
        }
        return $options;
    }

}
