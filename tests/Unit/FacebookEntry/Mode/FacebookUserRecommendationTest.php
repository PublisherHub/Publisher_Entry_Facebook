<?php

namespace Unit\FacebookEntry\Mode;

use Unit\Publisher\Mode\Recommendation\RecommendationInterfaceTest;

class FacebookUserRecommendationTest extends RecommendationInterfaceTest
{
    
    protected function getEntryName()
    {
        return 'Publisher\\Entry\\Facebook\\FacebookUserEntry';
    }
    
    public function getValidRecommendationParameters()
    {
        return array(
            array('message', '', '', null),
            array('message', 'title', 'url@foo.com', time() + (0 * 0 * 10 * 0))
        );
    }
    
    public function getRecommendationParametersAndResult()
    {
        return array(
            array(
                'message',
                'url@foo.com',
                'title',
                time() + (0 * 0 * 10 * 0),
                array(
                    'message' => "title\nmessage",
                    'link' => 'url@foo.com'
                )
            )
        );
    }
}