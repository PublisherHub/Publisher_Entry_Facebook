<?php

namespace Unit\Publisher\Entry\Facebook\Entity\Mode\Recommendation;

use Unit\Publisher\Mode\Recommendation\Entity\AbstractRecommendationTest;
use Publisher\Entry\Facebook\Entity\Mode\Recommendation\FacebookPageRecommendation;
use Publisher\Entry\Facebook\FacebookPageEntry;

class FacebookPageRecommendationTest extends AbstractRecommendationTest
{
    
    public function getValidData()
    {
        $date = new \DateTime("now");
        $date->add(new \DateInterval('PT30M'));
        
        return array(
            array(
                array(
                    'message' => 'abcdefghijklmnopqrstToday Unit 123',
                    'title' => 'Today Unit 123',
                    'url' => 'http://www.example.com',
                    'date' => null
                )
            ),
            array(// test special characters
                array(
                    'message' => "#@><´'°~!§%&ßöäüÄÜÖµ\"+-*^$/(\\)=}{[]",
                    'title' => "#@><´'°~!?§%&ßöäüÄÜÖµ\"+-*^$/(\\)=}{[]",
                    'url' => '',
                    'date' => null
                )
            ),
            array(// date as \DateTime instance
                array(
                    'message' => 'required',
                    'title' => '',
                    'url' => '',
                    'date' => $date
                )
            ),
            array(// date as timestamp
                array(
                    'message' => 'required',
                    'title' => '',
                    'url' => '',
                    'date' => $date->getTimestamp()
                )
            )
        );
    }
    
    public function getInvalidData()
    {
        /* Facebook does supports scheduled publishing per API
         * if the timestamp is between 10 minutes an 6 months ahead.
         */
        return array(
            array(
                array(
                    'message' => 'Today Unit 123',
                    'title' => 'Testing',
                    'url' => 'http://www.example.com',
                    'date' => new \DateTime('now') // invalid
                ),
                1
            )
        );
    }
    
    public function getExeecedMessageData()
    {
        $url = 'http://www.example.com'; // max character length unknown
        
        $title = '1234567890';
        /* 
         * Characters arrangement:
         * 10 for title
         * 1 for break between title and message
         */
        $messageLength = FacebookPageEntry::MAX_LENGTH_OF_MESSAGE - 10 - 1;
        $message = '';
        //add one additional character so we exceed maximum message length
        for ($i = 0; $i < $messageLength+1; $i++) {
            $message .= 'c';
        }
        
        return array(
            array(
                array(
                    'message' => $message,
                    'title' => $title,
                    'url' => $url,
                    'date' => null
                )
            ),
            array(
                array( 
                    'message' => $title.'b'.$message.'b', // .'b' => combining break
                    'title' => '',
                    'url' => $url,
                    'date' => null
                )
            )
        );
    }
    
    /**
     * @return AbstractRecommendation
     */
    protected function createRecommendation()
    {
        return new FacebookPageRecommendation();
    }
    
}