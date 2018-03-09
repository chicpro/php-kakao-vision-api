<?php
namespace chicpro\KakaoVision;

class VisionAPI
{
    protected $apiURL;
    protected $apiKey;

    protected $encType;
    protected $request;
    protected $postData = array();

    protected $file;
    protected $image_url;

    protected $endPoint;
    protected $apiHost = 'https://kapi.kakao.com';

    public function __construct($apiKey = '')
    {
        $this->setCredentials($apiKey);
    }

    public function setCredentials($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    protected function setRequest($request)
    {
        $this->request = $request;
    }

    protected function setEncType($encType)
    {
        switch ($encType) {
            case 'multipart/form-data':
            case 'application/x-www-form-urlencoded':
                $this->encType = $encType;
                break;
            default:
                break;
        }        
    }

    protected function checkImageURL($url)
    {
        $ch = curl_init($url);    
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($code == 200) {
            $status = true;
        } else {
            $status = false;
        }
        curl_close($ch);
        
        if (!$status)
            throw new \Exception('Image file does not exist.');
    }

    public function setApiHost($host)
    {
        $this->apiHost = $host;
    }

    public function setEndPoint()
    {
        $this->endPoint = $this->apiHost.$this->request;
    }

    public function setFile($filePath)
    {
        $file = realpath($filePath);

        if (is_file($file)) {
            $size = getimagesize($file);

            if ($size[2] != 2 && $size[2] != 3)
                throw new \Exception('Invalid Image file. Image file only PNG, JPG');

            if (isset($this->postData['image_url']))
                unset($this->postData['image_url']);
            
            $name = basename($file);
            $mime = mime_content_type($file);

            $this->setEncType('multipart/form-data');
            $this->postData['file'] = new \CurlFile($file, $mime, $name);            
        }
    }

    public function setImageURL($imgURL)
    {
        $this->checkImageURL($imgURL);

        if (isset($this->postData['file']))
            unset($this->postData['file']);
        
        $this->setEncType('application/x-www-form-urlencoded');
        $this->postData['image_url'] = $imgURL;
    }    

    protected function sendRequest()
    {
        if (!isset($this->postData['file']) && !isset($this->postData['image_url']))
            throw new \Exception('One of the image file or image url is required.');

        if ($this->encType == 'application/x-www-form-urlencoded')
            $this->postData = http_build_query($this->postData);
        
        $headers = array(
            'Authorization: KakaoAK ' . $this->apiKey,
            'Content-Type: ' . $this->encType
        );

        $this->setEndPoint();

        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $this->endPoint);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->postData);                

        $json = curl_exec($ch);
        
        if ($errno = curl_errno($ch)) {
            $result = new \stdClass;
            $result->errno = $errno;
            $result->error = 'Curl error: ' . curl_error($ch);
        } else {
            $response = json_decode($json);
            $result = $response;
        }

        return $result;
    }
}