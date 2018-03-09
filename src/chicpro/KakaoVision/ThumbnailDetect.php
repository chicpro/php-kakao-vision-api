<?php
namespace chicpro\KakaoVision;

class ThumbnailDetect extends VisionAPI
{
    protected $width;
    protected $height;

    public function __construct($apiKey = '')
    {
        $this->setCredentials($apiKey);
        $this->setRequest('/v1/vision/thumbnail/detect');
    }
    
    public function setThumbnailSize($width, $height)
    {
        $width  = (int)preg_replace('#[^0-9]#', '', $width);
        $height = (int)preg_replace('#[^0-9]#', '', $height);

        if ($width > 0)
            $this->width = $width;
        else
            throw new \Exception('Please enter only a number greater than 1 in width.');

        if ($height > 0)
            $this->height = $height;
        else
            throw new \Exception('Please enter only a number greater than 1 in height.');
    }

    public function send()
    {
        $this->postData['width']  = $this->width;
        $this->postData['height'] = $this->height;        
        
        return $this->sendRequest();
    }
}