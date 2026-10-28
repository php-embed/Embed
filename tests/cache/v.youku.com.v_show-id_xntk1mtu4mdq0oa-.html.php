<?php
declare(strict_types = 1);

return [
    'headers' => [
        'date' => [
            'Wed, 28 Oct 2026 00:28:51 GMT'
        ],
        'content-type' => [
            'text/html;charset=UTF-8'
        ],
        'vary' => [
            'Accept-Encoding'
        ],
        'cache-control' => [
            'no-store'
        ],
        'access-control-allow-credentials' => [
            'true'
        ],
        'bxpunish' => [
            '1'
        ],
        'server' => [
            'Tengine/Aserver'
        ],
        'eagleeye-traceid' => [
            '2100c81c17931473316557483e7cd2',
            '2100c81c17931473316557483e7cd2'
        ],
        'strict-transport-security' => [
            'max-age=31536000'
        ],
        'timing-allow-origin' => [
            '*'
        ],
        's-rt' => [
            '86'
        ],
        'content-encoding' => [
            'gzip'
        ],
        'Content-Location' => [
            'https://v.youku.com/v_show/id_XNTk1MTU4MDQ0OA==.html'
        ],
        'X-Request-Time' => [
            '1.475 ms'
        ]
    ],
    'statusCode' => 200,
    'reasonPhrase' => 'OK',
    'body' => '
<a id="a-link"
    href="https://bixi.alicdn.com/punish/punish:resource:template:heyiSpace:default_39608305.html?qrcode=PdXOg8kYCHUJrjh8zAMDTQ|auFBww|idwAgg_0|cloud_ip_bl|0&uuid=3dd5ce83c918087509ae387ccc03034d&action=deny&origin=https%3A%2F%2Fv.youku.com%3A443%2Fv_show%2Fid_XNTk1MTU4MDQ0OA%3D%3D.html"></a>
<script>
var host = location.host;
var parts = host.split(\'.\');
if (parts.length > 2){
  host = parts.pop();
  host = "." + parts.pop() + "." + host;
}
var exp = new Date();
var maxAge = -100;
exp.setTime(exp.getTime() + maxAge);
var cookie = "x5secdata=;maxAge=" + maxAge + ";expires=" + exp.toUTCString() + ";path=/;domain=" + host + ";";
document.cookie = cookie;
document.cookie = cookie + \'Secure;SameSite=None\';
document.getElementById("a-link").click();
 window._config_ = {
   "action": "deny",
   "url": "https://bixi.alicdn.com/punish/punish:resource:template:heyiSpace:default_39608305.html?qrcode=PdXOg8kYCHUJrjh8zAMDTQ|auFBww|idwAgg_0|cloud_ip_bl|0&uuid=3dd5ce83c918087509ae387ccc03034d&action=deny&origin=https%3A%2F%2Fv.youku.com%3A443%2Fv_show%2Fid_XNTk1MTU4MDQ0OA%3D%3D.html"
};
</script>
<!--rgv587_flag:sm-->'
];
