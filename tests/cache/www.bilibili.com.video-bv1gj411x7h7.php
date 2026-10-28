<?php
declare(strict_types = 1);

return [
    'headers' => [
        'date' => [
            'Wed, 28 Oct 2026 00:28:50 GMT'
        ],
        'content-type' => [
            'text/html'
        ],
        'server' => [
            'openresty'
        ],
        'x-sec-request-id' => [
            '07e43c86-e59c-67a3-0000-000031e1594c'
        ],
        'Content-Location' => [
            'https://www.bilibili.com/video/BV1GJ411x7h7'
        ],
        'X-Request-Time' => [
            '1.371 ms'
        ]
    ],
    'statusCode' => 412,
    'reasonPhrase' => 'Precondition Failed',
    'body' => '<!DOCTYPE html>
<html lang="zh-cn">

<head>
    <meta http-equiv="Access-Control-Allow-Origin" content="*" />
    <meta http-equiv="Page-Enter" content="blendTrans(Duration=0.5)">
    <meta http-equiv="Page-Exit" content="blendTrans(Duration=0.5)">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="spm_prefix" content="333.937">
    <title>出错啦! - bilibili.com</title>
    <link rel="shortcut icon" href="//static.hdslb.com/images/favicon.ico">
    <script type="text/javascript" src="//s1.hdslb.com/bfs/static/jinkela/long/js/jquery/jquery1.7.2.min.js"></script>
    <style>
        body {
          margin: 0;
          padding: 0;
          width: 100vw;
          height: 100vh;
        }
       .error-container {
          height: 100%;
          width: 100%;
          display: flex;
          justify-content: center;
          align-items: center;
        }
        .captcha-content {
          text-align: center;
        }
        .txt-wrap {
          margin-top: 15px;
          font-size: 12px;
          line-height: 22px;
          color: #999;
          text-align: left;
        }
        .txt-item.err-code {
          font-size: 28px;
          font-weight: 700;
          text-align: center;
          color: #4c93ff;
          margin-top: 16px;
        }
        
        .captcha-img {
          width: 160px;
          height: 160px;
          margin: auto;
          background: url(\'https://i0.hdslb.com/bfs/static/jinkela/long/412/%E9%A1%B5%E9%9D%A2%E7%8A%B6%E6%80%81%E5%9B%BE.png\') no-repeat center / cover;
        }
        .err-text {
          color: #03a9f4;
        }
        .err-default {
          font-size: 16px;
          margin-top: 10px;
        }
      </style>
</head>

<body>
    <noscript>
          <strong>抱歉！我们需要允许JavaScript才能够正常运行。请打开后重试~</strong>
          <strong>错误：412</strong>
      </noscript>
      <div class="error-container">
        <div class="captcha-content">
          <div class="captcha-img"></div>
          <div class="txt-item err-code">错误号: 412</div>
          <div class="txt-item err-text"></div>
          <div class="txt-item err-default">由于触发哔哩哔哩安全风控策略，该次访问请求被拒绝。 
            <div class="txt-item">The request was rejected because of the bilibili security control policy.</div>
          </div>
          <div class="txt-wrap">
            <div class="txt-item datetime_now"></div>
            <div class="txt-item user_url"></div>
            <div class="txt-item user_ip"></div>
            <div class="txt-item user_id"></div>
          </div>
          <div class="check-input">
              <div class="title"></div>
              <div class="box-pic"></div>
              <div class="box"></div>
              <div class="state"></div>
          </div>
      </div>
    </div>
    <script type="text/javascript" charset="utf-8" src="//security.bilibili.com/static/js/sha256.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="//security.bilibili.com/static/js/js.cookie.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="//security.bilibili.com/static/js/412.js"></script>
</body>
</html>'
];
