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

        if($result) {
            return (array)json_decode($result);
        } else {
            return array();
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


}