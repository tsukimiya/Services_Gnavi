<?php
include_once( dirname(__FILE__) . '/t.php');

$lime = new lime_harness(null);
$lime->register_glob(dirname(__FILE__).'/../*Test.php');
$lime->run();