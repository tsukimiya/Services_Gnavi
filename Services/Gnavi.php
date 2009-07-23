<?php

/**
 * Webservices for GOURMET NAVIGATOR
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy
 * the PHP License and are unable to obtain it through the web,
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category  Services
 * @package   Services_Gnavi
 * @author    Kiryu Tsukimiya <tsukimiya@gmail.com>
 * @copyright 2009 Kiryu Tsukimiya
 * @license   http://www.php.net/license/3_01.txt The PHP License, version 3.01
 * @version   0.1.0
 * @link      http://angelicwing.net/
 * @see       http://api.gnavi.co.jp/api/manual.htm
 */

class Services_Gnavi
{
  /**
   * Version
   */
  private static $version = "0.1.0";
  
  /**
   * API baseurl
   * 
   */
  private $baseurl = 'http://api.gnavi.co.jp/ver1/';
  
  /**
   * API actions
   * 
   */
  private $actions = array(
    'restaurant_search'     => 'RestSearchAPI',
    'area_search'           => 'AreaSearchAPI',
    'preference_search'     => 'PrefSearchAPI',
    'category_large_serach' => 'CategoryLargeSearchAPI',
    'category_small_search' => 'CategorySmallSearchAPI',
  );
  
  /**
   * 半径種別
   * 
   * @var integer
   */
  const RANGE_300  = 1;
  const RANGE_500  = 2;
  const RANGE_1000 = 3;
  const RANGE_2000 = 4;
  const RANGE_3000 = 5;
  
  /**
   * API access key
   * 
   * @var string
   */
  private $access_key;
  
  /**
   * constructer
   * 
   * @param $access_key
   * @return unknown_type
   */
  public function __construct($access_key)
  {
    $this->access_key = $access_key;
  }
  
  public function searchRestaurant($parameters = array())
  {
    include_once('Gnavi/ResultSet.php');
    
    $xml = $this->sendRequest('restaurant_search', $parameters);
    return new Services_Gnavi_ResultSet($xml);
  }
  
  /**
   * エリアマスタを取得する
   * 
   * @return array
   */
  public function getAreaMaster()
  {
    $xml = $this->sendRequest('area_search');
    $results = array();
    foreach($xml as $obj) {
      $results[(string)$obj->area_code] = (string)$obj->area_name;
    }
    
    return $results;
  }
  
  public function getPreferenceMaster()
  {
    $xml = $this->sendRequest('preference_search');
    $results = array();
    foreach($xml as $obj) {
      $results[(string)$obj->pref_code] = array(
        'pref_name' => (string)$obj->pref_name,
        'area_code' => (string)$obj->area_code,
      );
    }
    
    return $results;
  }
  
  /**
   * 大カテゴリマスタ一覧を取得する
   * 
   * @return array
   */
  public function getCategoryLargeMaster()
  {
    $xml = $this->sendRequest('category_large_serach');
    $results = array();
    foreach($xml as $obj) {
      $results[(string)$obj->category_l_code] = (string)$obj->category_l_name;
    }
    
    return $results;
  }
  
  /**
   * 小カテゴリマスタ一覧を取得する
   * 
   * @return array
   */
  public function getCategorySmallMaster()
  {
    $xml = $this->sendRequest('category_small_search');
    $results = array();
    foreach($xml as $obj) {
      $results[(string)$obj->category_s_code] = array(
        'category_s_name' => (string)$obj->category_s_name,
        'category_l_code' => (string)$obj->category_l_code,
      );
    }
    
    return $results;
  }
  
  /**
   * gnaviに問いあわせを行い、xmlを取得する
   * 
   * @param string $action
   * @param array  $parameters
   * @return SimpleXml
   */
  protected function sendRequest($action, $parameters = array())
  {
    $parameters = array_merge(array('keyid' => $this->access_key), $parameters);
    $url = $this->baseurl . $this->actions[$action] .'/?';
    
    foreach($parameters as $key => $val) {
      if ($key == 'id') {
        // idだけはURLエンコードしたらだめ
        $query_strings[] = sprintf("%s=%s", $key, $val);
      } else {
        $query_strings[] = sprintf("%s=%s", $key, urlencode($val));
      }
    }
    
    $url = $url . implode('&', $query_strings);
    
    $opts = array(
      'http' => array(
        'method' => 'GET',
        'user_agent' => __CLASS__ . '/' . self::$version
      )
    );
    
    $context = stream_context_create($opts);
    
    $fp = @fopen($url, 'r', false, $context);
    if (!$fp) {
      throw new Exception("Problem with ".$url);
    }
    
    $response = @stream_get_contents($fp);
    
    if ($response === false) {
      throw new Exception ("Problem reading data from ".$url);
    }
    
    $xml = simplexml_load_string($response);
    
    // エラーチェック
    if (isset($xml->error)) {
      $code = (int)$xml->error->code;
      throw new Exception($this->getErrorMessage($code), $code);
    }
    
    return $xml;
  }
  
  private function getErrorMessage($code)
  {
    $messages = array(
      '600' => 'NoShop',
      '601' => 'Invalid Access',
      '602' => 'Invalid Shop Number',
      '603' => 'Invalid Type',
      '604' => 'Internal Server Error',
    );
    return $messages[$code];
  }
}