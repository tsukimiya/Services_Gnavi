<?php

require_once(dirname(__FILE__).'/t/t.php');

$t = new lime_test(null, new lime_output_color());

try {
  $t->diag('不正なACCESS KEYテスト');
  $gnavi = new Services_Gnavi('test');
  $gnavi->getAreaMaster();
  $t->fail('不正なアクセスキーで処理を完了してしまった');
} catch (Exception $ex) {
  $t->pass('不正なアクセスキーで想定通りに失敗');
}

$gnavi = new Services_Gnavi(ACCESS_KEY);

$t->diag('Area master test');
$ret = $gnavi->getAreaMaster();
$t->is($ret['AREA110'], '関東', '内容確認');
$t->is(count($ret), 10, 'エリア件数確認');

$t->diag('Preference master test');

$ret = $gnavi->getPreferenceMaster();
$t->is($ret['PREF13']['pref_name'], '東京都', '県名取得');
$t->is($ret['PREF13']['area_code'], 'AREA110', 'エリアコード取得');

$t->diag('Category Large master test');

$ret = $gnavi->getCategoryLargeMaster();
$t->is($ret['CTG650'], 'バー・パブ', '大カテゴリ取得');

$t->diag('Category Small master test');
$ret = $gnavi->getCategorySmallMaster();
$t->is($ret['CTG102']['category_s_name'], '会席', '小カテゴリ名取得');
$t->is($ret['CTG102']['category_l_code'], 'CTG100', '所属大カテゴリコード取得');
