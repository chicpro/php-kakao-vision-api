# PHP Client for Kakao Vision API #

Kakao Vision API 사용을 위한 PHP Client

MIT licensed.

#### Kakao Vision API ####

- [Vision API](https://developers.kakao.com/docs/restapi/vision)

## 설치 ##

PHP Composer 를 통해 패키지를 설치합니다.

`$ composer require chicpro/php-kakao-vision-api`

## 예제 ##

```
require 'vendor/autoload.php';

$apiKey = 'REST API Key';

use chicpro\KakaoVision\FaceDetect;

$face = new FaceDetect();

$face->setCredentials($apiKey);

$face->setThreshold(0.7);
//$face->setFile('../files/example.jpg');
$face->setImageURL('https://example.com/example.jpg');

$response = $face->send();

print_r($response);
```
