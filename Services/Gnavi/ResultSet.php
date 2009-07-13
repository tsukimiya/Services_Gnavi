<?php

/**
 * Webservices for Gurunavi
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
 * @package   Services_Hanako
 * @author    Kiryu Tsukimiya <tsukimiya@gmail.com>
 * @copyright 2009 Kiryu Tsukimiya
 * @license   http://www.php.net/license/3_01.txt The PHP License, version 3.01
 * @version   0.1.0
 * @link      http://angelicwing.net/
 * @see       http://api.gnavi.co.jp/api/manual.htm
 */

class Services_Gnavi_ResultSet
{
  public $xml; 
  
  public function __construct(SimpleXMLElement $xml)
  {
    $this->xml = $xml;
  }
  
  /**
   * 該当件数
   * 
   * @return integer
   */
  public function getTotalHitCount()
  {
    return (int)$this->xml->total_hit_count;
  }
  
  /**
   * 表示件数
   * 
   * @return integer
   */
  public function getHitPerPage()
  {
    return (int)$this->xml->hit_per_page;
  }
  
  /**
   * 表示ページ
   * 
   * @return unknown_type
   */
  public function getPageOffset()
  {
    return (int)$this->xml->page_offset;
  }
  
  public function getResults()
  {
    return $this->xml->rest;
  }
}