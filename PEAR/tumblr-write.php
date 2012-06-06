<?php
include 'HTTP/OAuth/Consumer.php';

$account = 'gennitan.tumblr.com';

$consumer_key    = '';
$consumer_secret = '';

$access_token        = '';
$access_token_secret = '';

$http_request = new HTTP_Request2();
$http_request->setConfig('ssl_verify_peer', false);

try {

    $consumer = new HTTP_OAuth_Consumer($consumer_key, $consumer_secret);
    $consumer_request = new HTTP_OAuth_Consumer_Request;
    $consumer_request->accept($http_request);
    $consumer->accept($consumer_request);

// リクエストトークンの発行を依頼
    $consumer->getRequestToken('http://www.tumblr.com/oauth/request_token');

// リクエストトークンを取得
    $request_token = $consumer->getToken();
    $request_token_secret = $consumer->getTokenSecret();

// リクエストトークンをセット
    $consumer->setToken($request_token);
    $consumer->setTokenSecret($request_token_secret);

// 発行済みのアクセストークンをセット
    $consumer->setToken($access_token);
    $consumer->setTokenSecret($access_token_secret);

// Tumblrへ投稿時のオプション
	// 投稿文章
	/*
	$params = array(
			'type' => 'text',
			'title' => $post_title,
			'body' => $post_body
	);
	 */

// OAuthを経由してTumblrの投稿APIへデータを投げる
	$api_url = 'http://api.tumblr.com/v2/blog/'. $account .'/post';
    $response = $consumer->sendRequest($api_url, $params);

} catch (HTTP_OAuth_Consumer_Exception_InvalidResponse $e) {
// リクエストトークンの取得が失敗した場合
    echo $e->getMessage();
    exit;

} catch (HTTP_OAuth_Exception $e) {
// Tumblrからの読み込みが失敗した場合
    echo $e->getMessage();
    exit;
}

