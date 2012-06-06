<?php
date_default_timezone_set('Asia/Tokyo');
$post_title = date('Y-m-d') .' ';
$post_title .= 'ニコニコ動画まとめ';
$post_tag = 'デイリー';
$post_body = '';

$rank_num = 5;
$voca_num = 3;
$hatebu_num = 3;

//管理人マイリスまとめ
$xml = simplexml_load_file('http://www.nicovideo.jp/mylist/30467188?rss=2.0');
$post_body .= '<p><strong>昨日のマイリスした動画</strong><br/>';
$day_date = date('D, d M Y',strtotime('-1 day'));
$mylist;
foreach($xml->channel->item as $item){
    if(preg_match("/".$day_date."/",$item->pubDate)){
        $mylist .= '<a href="'.$item->link.'">'.$item->title.'</a><br/>';
    }
}
if($mylist){
    $post_body .= $mylist;
}else{
    $post_body .= 'No Data.<br/>';
}
$post_body .= $day_date.'<br/>';
$post_body .= '（<a href="'.$xml->channel->link.'">マイリスト</a>から）</p><br/>';


//カテゴリ合算デイリーランキング
$xml = simplexml_load_file('http://www.nicovideo.jp/ranking/fav/daily/all?rss=2.0');
$post_body .= '<p><strong>'.$xml->channel->title.'</strong><br/>';
$count = 0;
foreach($xml->channel->item as $item){
    $count++;
    $post_body .= '<a href="'.$item->link.'">'.$item->title.'</a>';
    $post_body .= '<br/>';
    if($count == $rank_num){break;}
}
$post_body .= $xml->channel->lastBuildDate .'<br/>';
$post_body .= '（<a href="'.$xml->channel->link.'/'.date("Ymd").'">デイリーランキング</a>から）</p><br/>';

//VOCALOIDデイリーランキング
$xml = simplexml_load_file('http://www.nicovideo.jp/ranking/fav/daily/vocaloid?rss=2.0');
$post_body .= '<p><strong>'.$xml->channel->title.'</strong><br/>';
$count = 0;
foreach($xml->channel->item as $item){
    $count++;
    $post_body .= '<a href="'.$item->link.'">'.$item->title.'</a>';
    $post_body .= '<br/>';
    if($count == $voca_num){break;}
}
$post_body .= $xml->channel->lastBuildDate .'<br/>';
$post_body .= '（<a href="'.$xml->channel->link.'/'.date("Ymd").'">VOCALOIDランキング</a>から）</p><br/>';

//はてブ上位エントリー
$xml = simplexml_load_file('http://b.hatena.ne.jp/entrylist?sort=hot&threshold=10&url=http%3A%2F%2Fwww.nicovideo.jp%2Fwatch%2F&mode=rss');

$post_body .= '<p><strong>昨日のはてなブックマーク10users以上 - 上位エントリー -</strong><br/>';

$day_date = date('Y-m-d',strtotime('-1 day'));
foreach($xml->item as $item){
    if(preg_match('/'.$day_date.'/',$item->children('http://purl.org/dc/elements/1.1/')->date)){
        $post_date[] = $item->children('hatena', true)->bookmarkcount.
            'users <a href="'.$item->link.'">'.$item->title.'</a>';
    }
}
arsort($post_date);
$i = 0;
foreach($post_date as $date){
    $post_body .= $date.'<br/>';
    $i++;
    if($i == $hatebu_num){break;}
}
if(!$post_date){
    $post_body .= 'No data.<br/>';
}
$post_body .= $day_date.' (users数は'.date(c).'更新時)<br/>';
$post_body .= '（<a href="'.$xml->channel->link.'">はてなブックマーク</a>から）</p>';

$params = array(
    'type' => 'text',
    'tags' => $post_tag,
    'title' => $post_title,
    'body' => $post_body
);
//print_r($params);

require_once 'tumblr-write.php';