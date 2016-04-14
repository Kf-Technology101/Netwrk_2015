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
        $return = [];
        $zip_code = $zipcode ? $zipcode : $_GET['zip_code'];
        $country = $_GET['country'];

        return array();

        $url = 'api.openweathermap.org/data/2.5/weather?zip='.$zip_code.','.$country;

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
        return (array)json_decode($result);

        //Todo: on ajax call
        //$result = $this->actionGetCurl($url);
        //return $result;
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