<?php

namespace frontend\modules\netwrk\controllers;

use frontend\components\UtilitiesFunc;
use frontend\components\BaseController;
use yii\helpers\Url;
use yii\db\Query;
use yii\data\Pagination;
use Yii;

class ApiController extends BaseController
{


    public function actionGetZipWeatherData($zipcode = null)
    {
        $zip_code = isset($zipcode) ? $zipcode : $_GET['zip_code'];

        $country = isset($_GET['country']) ? $_GET['country'] : 'US';
        $apiKey = 'd77ea09760491f2fec46d1bbfd6bba3c';

        $url = "api.openweathermap.org/data/2.5/weather?APPID=$apiKey&zip=$zip_code,$country";
        //$url = "http://api.openweathermap.org/data/2.5/weather?APPID=d77ea09760491f2fec46d1bbfd6bba3c&zip=46214,US";
        //return array();
        //$url = "http://boundaries.io/geographies/postal-codes?search=" . urlencode($zip_code);
        $headers[] = 'Accept: application/json';
        $headers[] = 'Connection: Keep-Alive';
        $headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';
        //Initiate curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL, $url);
        // Execute
        $result = curl_exec($ch);
        // Closing
        curl_close($ch);

        $result = (array)json_decode($result);
        //check weather response got 200 response code
        if(!empty($result) && $result['cod'] == 200) {
            return json_encode($result);
        } else {
            return json_encode(array());
        }
    }

    public function actionGetZipJobData($zipcode = null)
    {
        $zip_code = isset($zipcode) ? $zipcode : $_GET['zip_code'];

        $publisherId = '2598759950056025';
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $userIp = $_SERVER['REMOTE_ADDR'];
        $limit = 10 ;

        $queryString = "?publisher=$publisherId&q=$zip_code&userip=$userIp&v=2&format=json&limit=$limit&useragent=".urlencode($userAgent);
        $url = "http://api.indeed.com/ads/apisearch".$queryString;

        $headers[] = 'Accept: application/json';
        $headers[] = 'Connection: Keep-Alive';
        $headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';
        //Initiate curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL, $url);
        // Execute
        $result = curl_exec($ch);
        // Closing
        curl_close($ch);
        return $result;
    }


    public function actionGetCurl($url)
    {
        //$url = "http://boundaries.io/geographies/postal-codes?search=" . urlencode($zip_code);
        $headers[] = 'Accept: application/json';
        $headers[] = 'Connection: Keep-Alive';
        $headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';
        //Initiate curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL, $url);
        // Execute
        $result = curl_exec($ch);

        //var_dump($result);
        // Closing
        curl_close($ch);
        return $result;
    }

    /**
     * Get twitter feeds location wise
     * @return string
     * @throws \Exception
     */
    public function actionGetTweets($geocode = null, $city_lat = null, $city_lng = null, $city_name = null)
    {
        //require_once(__DIR__ . '/../../vendor/j7mbo/twitter-api-php/TwitterAPIExchange.php');
        /** Set access tokens here - see: https://dev.twitter.com/apps/ **/
        $settings = array(
            'oauth_access_token' => "783537327423512577-MJYQV8StssV17Ow9AEGiFsNVmshwnOJ",
            'oauth_access_token_secret' => "jXRL1lt2To1WlJBDg00lRaAbw1gtAq3VCV01TPVwZDOvD",
            'consumer_key' => "FB1YDFCA2yWZg6HDhaPHAL5B9",
            'consumer_secret' => "7NJ50LzhXw8Y7PIaxOmuxUKYTFM4zftZfbsFRlMJqK1tTQsGod"
        );

        //TODO: what will be the defualt query string if user not passed any query string as query is required params.
        $defaultQuery = "";
        $q = isset($_GET['query']) ? $_GET['query'] : $defaultQuery;
        $geoWithRadius =  isset($geocode) ? $geocode : '';
        $resultType = isset($_GET['result_type']) ? $_GET['result_type'] : 'recent';
        $count = isset($_GET['count']) ? $_GET['count'] : 10;

        $lat = floatval($city_lat);
        $lng = floatval($city_lng);
        $radius = '621mi';
        //search by #cityName
        $q = '#'.$city_name;

        $url = 'https://api.twitter.com/1.1/search/tweets.json';
        //$getfield = '?q=in&result_type='.$resultType.'&count='.$count.'&geocode=39.7651,-86.4168,621mi';
        $getfield = '?q='.$q.'&result_type='.$resultType.'&count='.$count.'&geocode='.$lat.','.$lng.','.$radius;

       //var_dump($getfield);
        $requestMethod = 'GET';

        $twitter = new \TwitterAPIExchange($settings);
        $result = $twitter->setGetfield($getfield)
            ->buildOauth($url, $requestMethod)
            ->performRequest();

        return $result;
    }


}