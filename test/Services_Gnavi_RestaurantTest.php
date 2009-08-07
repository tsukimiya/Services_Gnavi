<?php

require_once(dirname(__FILE__).'/t/t.php');

$t = new lime_test(null, new lime_output_color());

try {
  $t->diag('不正なパラメータテスト');
  $gnavi = new Services_Gnavi(ACCESS_KEY);
  $gnavi->searchRestaurant();
  $t->fail('不正なパラメータで処理を完了してしまった');
} catch (Exception $ex) {
  $t->pass('不正なパラメータで想定通りに失敗');
}

$gnavi = new Services_Gnavi(ACCESS_KEY);

$t->diag('restaurant search test: エリア・県・idで絞り込み');
$params = array(
  'area' => 'AREA110',
  'pref' => 'PREF13',
  'id'   => 'g144600',
  'sort' => '1',
);
$rs = $gnavi->searchRestaurant($params);

$t->is($rs->getTotalHitCount(), 1, '該当件数');
$t->is($rs->getHitPerPage(), 10, '表示件数');
$t->is($rs->getPageOffset(), 1, '表示ページ');


foreach($rs->getResults() as $row){
  $t->is((string)$row->id, 'g144600', '取得に使ったIDのデータがとれたか');
}

$t->diag('restaurant search test: エリア・県・id2件での取得');

$params = array(
  'area' => 'AREA110',
  'pref' => 'PREF13',
  'id'   => 'g144600,g912449',
  'sort' => '1',
);
$rs = $gnavi->searchRestaurant($params);

$res = $rs->getResults();
$t->is((string)$res[0]->id, 'g912449', '1番目のデータIDチェック');
$t->is((string)$res[1]->id, 'g144600', '2番目のデータIDチェック');

$t->diag('restaurant search test: 不正な店idを指定');

try {
  $params = array(
    'area' => 'AREA110',
    'pref' => 'PREF13',
    'id'   => 'abcde',
    'sort' => '1',
  );
  $rs = $gnavi->searchRestaurant($params);
  $t->fail('不正なidで処理を完了してしまった');
} catch (Exception $ex) {
  $t->is($ex->getMessage(), 'Invalid Shop Number', 'Exception');
}

$t->diag('Restaurant search test: 30件取得');

$params = array(
  'latitude'     => '35.657784',
  'longitude'    => '139.704037',
  'range'        => '5',
  'hit_per_page' => '30',
  'sort' => '1',
);

$rs = $gnavi->searchRestaurant($params);

$res = $rs->getResults();

$t->is(count($res), 30, '30件取得できているか');

try {
  $params = array(
  //  'area' => 'AREA110',
  //  'pref' => 'PREF13',
    'hit_per_page' => '999',
    'sort' => '1',
  );
  
  $rs = $gnavi->searchRestaurant($params);
  $res = $rs->getResults();
  $t->diag('count: '.count($res));
  $t->diag('page'.$rs->getTotalHitCount());
} catch (Exception $ex) {
  $t->is($ex->getMessage(), 'Invalid Shop Number', 'Exception');
}