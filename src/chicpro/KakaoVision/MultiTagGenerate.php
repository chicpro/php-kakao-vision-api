<?php
namespace chicpro\KakaoVision;

class MultiTagGenerate extends VisionAPI
{
    public function __construct($apiKey = '')
    {
        $this->setCredentials($apiKey);
        $this->setRequest('/v1/vision/multitag/generate');
    }
    
    public function send()
    {
        return $this->sendRequest();
    }
}