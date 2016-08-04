<?php

namespace Unit\FacebookEntry\FacebokPageEntryTest;

use Unit\Publisher\Entry\EntryTest;

class FacebookPageEntryTest extends EntryTest
{
    
    protected function getEntryClass()
    {
        return 'Publisher\\Entry\\Facebook\\FacebookPageEntry';
    }
    
    public function getValidBody()
    {
        $return =  array(
            array(array('message' => 'foo')),
            array(array('link' => 'foo')),
            array(array('place' => 'foo')),
            array(array('message' => 'foo', 'link' => 'foo', 'place' => 'foo'))
        );
        
        // add required parameters
        $parameters = $this->getParameters();
        for($i = 0; $i < count($return); $i++) {
            $return[$i][] = $parameters;
        }
        
        return $return;
    }
    
    public function getInvalidBody()
    {
        return array(
            array(array()),
            array(array('notRequired' => 'foo'))
        );
    }
    
    public function getBodyWithExceededMessage()
    {
        return array(
            array(
                array('message' => $this->getExceededMessage()),
                $this->getParameters()
            )
        );
    }
    
    protected function getParameters()
    {
        return array('pageId' => 'foo', 'pageAccessToken' => 'bar');
    }

}

