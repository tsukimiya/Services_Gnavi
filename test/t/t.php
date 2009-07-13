<?php 

define('ACCESS_KEY', file_get_contents(dirname(__FILE__).'/accesskey.txt'));

require_once (dirname(__FILE__).'/lime.php');
require_once (dirname(__FILE__).'/../../Services/Gnavi.php');