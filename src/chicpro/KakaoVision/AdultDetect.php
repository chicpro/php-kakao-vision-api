<?php
namespace chicpro\KakaoVision;

class AdultDetect extends VisionAPI
{
    public function __construct($apiKey = '')
    {
        $this->setCredentials($apiKey);
        $this->setRequest('/v1/vision/adult/detect');
    }
    
    public function send()
    {
        return $this->sendRequest();
    }
}