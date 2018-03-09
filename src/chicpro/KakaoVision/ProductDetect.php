<?php
namespace chicpro\KakaoVision;

class ProductDetect extends VisionAPI
{
    protected $threshold;

    public function __construct($apiKey = '')
    {
        $this->setCredentials($apiKey);
        $this->setRequest('/v1/vision/product/detect');
    }
    
    public function setThreshold($threshold)
    {
        $threshold  = (float)preg_replace('#[^0-9\.]#', '', $threshold);
        
        if ($threshold < 0 || $threshold > 1)
            throw new \Exception('Threshold must be between 0 and 1.0');
        
        $this->threshold = $threshold;
    }

    public function send()
    {
        $this->postData['threshold']  = $this->threshold;
        
        return $this->sendRequest();
    }
}