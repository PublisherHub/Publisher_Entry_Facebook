<?php

namespace Unit\Publisher\Entry\Facebook\Entity\Mode\Recommendation;

use Unit\Publisher\Mode\Recommendation\Entity\AbstractRecommendationTest;
use Publisher\Entry\Facebook\Entity\Mode\Recommendation\FacebookUserRecommendation;
use Publisher\Entry\Facebook\FacebookUserEntry;

class FacebookUserRecommendationTest extends AbstractRecommendationTest
{
    
    public function getValidData()
    {
        return array(
            array(
                array(
                    'message' => "abcdefghijklmnopqrstToday Unit 123",
                    'title' => "Today Unit 123",
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
            )
        );
    }
    
    public function getInvalidData()
    {
        
        return array(
            array( // Facebook user posts don't support scheduled publishing
                array(
                    'message' => "Today Unit 123",
                    'title' => "Testing",
                    'url' => 'http://www.example.com',
                    'date' => 946684800 // invalid
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
        $messageLength = FacebookUserEntry::MAX_LENGTH_OF_MESSAGE - 10 - 1;
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
        return new FacebookUserRecommendation();
    }
    
}