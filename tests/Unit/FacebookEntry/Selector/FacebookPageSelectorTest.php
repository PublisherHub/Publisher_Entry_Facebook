<?php

namespace Unit\FacebookEntry\Selector;

use Unit\Publisher\Selector\Parameter\AbstractSelectorTest;
use Publisher\Entry\Facebook\Selector\FacebookPageSelector;
use Publisher\Requestor\RequestorInterface;
use Publisher\Storage\StorageInterface;

class FacebookPageSelectorTest extends AbstractSelectorTest
{
    
    public function getFinalState()
    {
        $choices = array('pageId' => '123');
        $response = array('access_token' => '4567890abc321');
        $parameters = array(
            'pageId' => '123',
            'pageAccessToken' => '4567890abc321'
        );
        
        return array(array($choices, $parameters, json_encode($response)));
    }
    
    /**
     * @runInSeparateProcess
     */
    public function testAddPageSelection()
    {
        $selections = $this->getUpdatedSelection(
                array(),
                $this->getShortenedResponseData()
        );
        
        $expectedChoices = array('foo' => '123', 'bar' => '321');
        
        $this->assertTrue(isset($selections[0]));
        $this->assertEquals('pageId', $selections[0]->getName());
        $this->assertEquals($expectedChoices, $selections[0]->getChoices());
        $this->assertTrue($this->selector->isParameterMissing());
    }
    
    protected function getShortenedResponseData()
    {
        $response = array(
            'data' => array(
                array('id' => '123', 'name' => 'foo'),
                array('id' => '321', 'name' => 'bar')
            )
        );
        
        return json_encode($response);
    }
    
    protected function getSelector(
            RequestorInterface $requestor,
            StorageInterface $storage
    ) {
        return new FacebookPageSelector($requestor, $storage);
    }
    
}