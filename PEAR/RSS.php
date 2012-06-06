<?php

$xml = simplexml_load_file('http://www.nicovideo.jp/ranking/fav/daily/all?rss=2.0');

function c($xml){
	return mb_convert_encoding($xml,'SJIS','UTF-8');
}

$post_title = date('Y-m-d-G');

$post_title .= $xml->channel->title;


$post_body;

foreach($xml->channel->item as $item){
		$post_body .= '<a href="'.$item->link.'">'.$item->title.'</a>';
		$post_body .= '<br/>';
}
$post_body .= $xml->channel->lastBuildDate;

print_r($xml);
