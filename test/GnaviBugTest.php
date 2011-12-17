<?php

require_once(dirname(__FILE__).'/t/t.php');

$t = new lime_test(null, new lime_output_color());

try {
  $gnavi = new Services_Gnavi(ACCESS_KEY);

  $t->diag('北海道を50件目から10件取得');
  $params = array(
    'hit_per_page' => 10,
    'offset' => 50,
    'pref' => 'PREF01',
    'sort' => '1',
  );
  $rs = $gnavi->searchRestaurant($params);

  $t->is(count($rs->getResults()), 10, '50件目から10件取得');
} catch(Exception $ex) {
  $t->fail($ex->getMessage());
}

try {
  $gnavi = new Services_Gnavi(ACCESS_KEY);

  $t->diag('北海道を999件目から1件取得');
  $params = array(
    'hit_per_page' => 1,
    'offset' => 999,
    'pref' => 'PREF01',
    'sort' => '1',
  );
  $rs = $gnavi->searchRestaurant($params);

  $t->is(count($rs->getResults()), 1, '999件目から1件取得');
} catch(Exception $ex) {
  $t->fail($ex->getMessage());
}

try {
  $gnavi = new Services_Gnavi(ACCESS_KEY);

  $t->diag('北海道を998件目から2件取得');
  $params = array(
    'hit_per_page' => 2,
    'offset' => 998,
    'pref' => 'PREF01',
    'sort' => '1',
  );
  $rs = $gnavi->searchRestaurant($params);

  $t->is(count($rs->getResults()), 2, '998件目から2件取得');
} catch(Exception $ex) {
  $t->fail($ex->getMessage());
}

try {
  $gnavi = new Services_Gnavi(ACCESS_KEY);

  $t->diag('北海道を998件目から3件取得');
  $params = array(
    'hit_per_page' => 3,
    'offset' => 998,
    'pref' => 'PREF01',
    'sort' => '1',
  );
  $rs = $gnavi->searchRestaurant($params);

  $t->is(count($rs->getResults()), 1, '998件目から3件取得');
} catch(Exception $ex) {
  $t->fail($ex->getMessage());
}

try {
  $gnavi = new Services_Gnavi(ACCESS_KEY);

  $t->diag('北海道を1000件目から1件取得');
  $params = array(
    'hit_per_page' => 1,
    'offset' => 1000,
    'pref' => 'PREF01',
    'sort' => '1',
  );
  $rs = $gnavi->searchRestaurant($params);

  $t->is(count($rs->getResults()), 1, '1000件目から1件取得');
} catch(Exception $ex) {
  $t->fail($ex->getMessage());
}

try {
  $gnavi = new Services_Gnavi(ACCESS_KEY);

  $t->diag('東京を1000件目から1件取得');
  $params = array(
    'hit_per_page' => 1,
    'offset' => 1000,
    'pref' => 'PREF13',
    'sort' => '1',
  );
  $rs = $gnavi->searchRestaurant($params);

  $t->is(count($rs->getResults()), 1, '1000件目から1件取得');
} catch(Exception $ex) {
  $t->fail($ex->getMessage());
}
