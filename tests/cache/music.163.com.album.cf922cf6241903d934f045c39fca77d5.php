<?php
declare(strict_types = 1);

return [
    'headers' => [
        'server' => [
            'nginx'
        ],
        'date' => [
            'Wed, 28 Oct 2026 00:28:55 GMT'
        ],
        'content-type' => [
            'text/html; charset=utf8'
        ],
        'vary' => [
            'Accept-Encoding'
        ],
        'content-security-policy' => [
            'upgrade-insecure-requests'
        ],
        'x-custom-music-web' => [
            'no'
        ],
        'x-traceid-v2' => [
            '72daa22b186eba2d3f350d15bb10ad63^1790043134004^-15324424752'
        ],
        'mconfig-bucket' => [
            '999999'
        ],
        'x-traceid' => [
            '000001a17fe8f18f057c0a7bbde50007'
        ],
        'cache-control' => [
            'no-cache',
            'no-store'
        ],
        'expires' => [
            'Thu, 01 Jan 1970 00:00:00 GMT'
        ],
        'gw-thread' => [
            '-837227'
        ],
        'gw-time' => [
            '1793147335056'
        ],
        'x-via' => [
            'MusicServer'
        ],
        'content-encoding' => [
            'br'
        ],
        'Content-Location' => [
            'https://music.163.com/album?id=18905'
        ],
        'X-Request-Time' => [
            '1.462 ms'
        ]
    ],
    'statusCode' => 200,
    'reasonPhrase' => 'OK',
    'body' => '<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="baidu_ssp_verify" content="39f14c78c537175eb4b5192c72d002c1" />
<meta name="baidu-site-verification" content="cNhJHKEzsD" />
<meta name="360-site-verification" content="e37aef53e3922913e2a6a4682e479b84" />
<meta name="sogou_site_verification" content="7zFjYjJaMq"/>
<meta name="msvalidate.01" content="0CA3171633345524D8CBED5E95C75FFF" />
<meta name="google-site-verification" content="sjYNllmC9P6a0ifNR5upDlNEhcgU_QP5pB3pJPyYeSc" />
<meta name="shenma-site-verification" content="12da4afc02bfe908ed0667f287167d11_1555581349" />
<meta property="qc:admins" content="27354635321361636375" />
<meta name="robots" content="nofollow">
<meta name="applicable-device" content="pc">
<title>网易云音乐</title>
<meta itemprop="images" content="http://p3.music.126.net/9z9CeujRSPOPm7Rq2DFw_g==/6674035581283071.jpg?param=200y200" />
<meta property="og:title" content="网易云音乐" />
<meta property="og:site_name" content="网易云音乐"/>
<script type="text/javascript">
"1669737600000";
"1670410800000";
"1793147335076";
"false";
</script>
<script>
var _hmt=_hmt || [];
(function() {
var hm=document.createElement("script");
hm.src="https://hm.baidu.com/hm.js?1483fb4774c02a30ffa6f0e2945e9b70";
var s=document.getElementsByTagName("script")[0];
s.parentNode.insertBefore(hm, s);
})();
</script>
<script type="text/javascript">
if (location.pathname.indexOf("outchain") === -1) {
if (document.domain.match(/music.163.com$/)) {
document.domain = "music.163.com";
} else {
document.domain = document.domain;
}
}
var GDownloadLink="";
var GDevice = "phone";
var GFrom="";
var GClient="";
var GPlatform="other";
var GRef = \'\';
var GInApp = false;
var GMobile = false;
var GAbroad = false;
var GUser={};
var GAllowRejectComment = false;
var GEnc = true;
var GEnvType = "online";
var GWebpSupport = "1";
var vipWebCashierRedirect = "1";
window.NEJ_CONF = {p_csrf:{cookie:\'__csrf\',param:\'csrf_token\'}};
GUtil = {
getBase:function(){
return location.protocol+\'//\'+location.hostname;
},
getPathAndHash:function(_url){//获取URL path 之后的所有内容,并将/#/替换成/m/使之成为path的一部分
if(!_url) return \'\';
var _reg0 = /^https?:\\/\\/.*?\\//i,
_reg1 = /\\/?#\\/?/i;
return _url.replace(_reg0,\'/\').replace(_reg1,\'/m/\');
},
composeRefer:function(_url,_ref){//对所有的页面请求都加上ref参数表示被嵌套的来源
if(!_ref) return _url;
var _hi = _url.indexOf(\'#\'),
_si = _url.indexOf(\'?\');
if(_si>0&&(_si<_hi||_hi<0)){
return _url.substring(0,_si+1)+\'ref=\'+_ref+\'&\'+_url.substring(_si+1);
}else if(_hi>0&&(_si<0||_si>_hi)){
return _url.substring(0,_hi)+\'?ref=\'+_ref+_url.substring(_hi);
}else{
return _url+\'?ref=\'+_ref;
}
}
};(function(){
var _ua = window.navigator.userAgent,
_isMobile = /(mobile|mobi|wap|iphone)/i.test(_ua),
_isAndroid = /android/i.test(_ua),
_isIpad = /(ipad)/i.test(_ua),
_igList = [/^\\/xiami$/,/^\\/live$/],//不需要以单页面打开的列表，比如某些活动页面
_pn = location.pathname,
_ydomainPath = [\'home\', \'activity\',\'album\',\'artist\',\'djradio\',\'radio\',\'dj\',\'login\',\'mv\',\'playlist\',\'program\',\'song\',\'tiktoksong\', \'uniplaylist\',\'unisong\',\'user\',\'video\',\'event\',\'discover/toplist\',\'user/home\'],
_idx = _pn.lastIndexOf(\'/\'),
_pReg = /\\s*(\\w+)\\s*=\\s*(\\d+)\\s*/,
_isOnCDN = function(type) {
return _ydomainPath.indexOf(type) !== -1;
},
_isOnline = function() {
return location.host.indexOf(\'music.163.com\') !== -1
},
_getBase = function(type) {
return location.protocol + \'//\' + ((_isOnCDN(type) && _isOnline()) ? \'y.music.163.com\' : location.hostname);
},
_redirect2mobile = function() {
var _type,_murl,
_id = 0,
_hash = location.hash,
_mReg = /^#\\/?m?\\/(share|song|playlist|djradio|dj|program|album|mv|artist|topic|radio|zysf|drqp|qp|activity|store|user|event|video|discover\\/toplist|login)(\\/(\\d+))?/,
_base = location.protocol+\'//\'+location.hostname,
_sindex = _hash.lastIndexOf(\'?\'),
_search = _sindex>-1?_hash.substring(_sindex+1):\'\',
_match = _mReg.exec(_hash);
// 用户等级页特殊处理
if (_hash === \'#/user/level\') {
location.href = _base + \'/store/m/gain/mylevel\';
return;
}
// wiki不做跳转
if (window.location.pathname.indexOf(\'/wiki\') === 0 || _hash.indexOf(\'#/wiki\') === 0) {
return;
}
// 网易音乐人手册特殊处理
if (/^#\\/series\\b/.test(_hash)) {
var _seriesQuery = _search.replace(\'id\', \'seriesId\');
location.href = _base + \'/m/topic/all?\' + _seriesQuery;
return;
}
// 无hash || 不匹配 || 匹配但是商品之外不带参数 || 匹配且是排行榜
if (!_hash.length || !_match || (_match[1] != \'store\' && !_search) || /share|discover\\/toplist/.test(_match[1])) {
// 有hash && (没有参数 || 排行榜)
if ((!_search || /share|discover\\/toplist/.test(_match[1])) && _hash.length) {
location.href = ((_match && _match[1]) ? _getBase(_match[1]) : _base) + \'/\' + _hash.replace(\'#\', \'m\');
} else {
location.href = _getBase(\'home\') + \'/m/\';
}
return;
}
_type = _match[1];
_id = _match[3];
if (_type == \'dj\') _type = \'program\';
if (_type == \'store\') {
_murl = /^#\\/store\\/(product|concert)\\/detail/.test(_hash) ? _hash.replace(\'#/store\', \'/store/m\') : \'/store/m/product/index\';
} else {
_murl = \'/\' + _type + \'?\' + (_id ? \'id=\' + _id + \'&\' : \'\') + _search;
}
location.href = _getBase(_type) + (_isOnCDN(_type) ? \'/m\' : \'\') + _murl;
};
if(_isMobile || _isAndroid || _isIpad){
_redirect2mobile();
return;
}
if(!_pn||_pn==\'/\') return;
for(var i in _igList){
if(_igList[i].test(_pn)) return;
}
if(top==self){
location.href = \'/#\'+GUtil.getPathAndHash(location.href);
return;
}
//搜索引擎过来的内容页连接
if(top==self&&/^\\/static\\/(song|playlist|album|artist)/i.test(_pn)){
location.href = \'/#\'+_pn.substring(0,_idx).replace(\'/static/\',\'/\')+\'?id=\'+_pn.substring(_idx+1);
}
})();
(function(){
var _addEvent = function(_node,_type,_cb){
if(_node.addEventListener){
_node.addEventListener(_type,_cb);
}else if(_node.attachEvent){
_node.attachEvent(\'on\'+_type,_cb);
}
},
_pathPrefixArray = [
\'/store/\', // 商城
\'/m/at/\', // 活动
\'/prime/m/\', // 会员移动端页面
\'/oauth2/\', // 授权
\'/m/oauth2/\', // 授权
\'/octave/\', // 新数字专辑
\'/v/\', // 新数字专辑
\'/st/\', // 静态页面
\'/nmusician/\',// 音乐人
\'/nact/\', // 新活动
\'/m/topic/\', // 专栏移动端
\'/show/m/\', //演出移动端
],
_isNotMainsitePagePath = function(_pagePath){
// 对于非主站内的页面的跳转 需要排除
var _path = (_pagePath||\'\').replace(/^https?:\\/\\/.*?\\//i, \'/\').split(/[?#]/)[0];
for(var i=0;i<_pathPrefixArray.length;i++){
if(_path.indexOf(_pathPrefixArray[i])===0) return true;
}
return false;
},
_onAnchorClick = function(_event){//截获所有<a>标签的点击事件，自定义页面的跳转
_event = _event||window.event;
var _el = _event.target||_event.srcElement,
_base = location.protocol+\'//\'+location.host;
while(_el&&_el!=document){
if(_el.tagName&&_el.tagName.toLowerCase()==\'a\'){
//fix ie6下有时javascript:;不能阻止默认事件的bug.
if(_el.href.indexOf(\'javascript:\')>=0){
!!_event.preventDefault
? _event.preventDefault()
: _event.returnValue = !1;
return;
}
if(_event.button==2) return;//ff 右键会触发click事件
//商城有独立地顶栏了，排除掉。但会员、数字专辑、单曲的商品、订单页仍保持主站frame，
//这些url往往是通过/vip2, /payfee这样的地址跳转的，也没有问题，如果真的有，URL用#配置就好了
if(_isNotMainsitePagePath(_el.href)) return;
//新窗口打开的链接、云音乐单页面形式的链接、站外的链接不做拦截处理。
if(_el.target==\'_blank\'
||_el.target==\'blank\'
||_isNotSameHost(_el.href)
||_el.href==_base
||_el.href.indexOf(_base+\'/#\')>=0) return;
!!_event.preventDefault
? _event.preventDefault()
: _event.returnValue = !1;
location.dispatch2(_el.href);
break;
}else{
_el = _el.parentNode;
}
}
},
_isNotSameHost = function(_href){
var _same = true;
if(_href.charAt(0)!=\'/\'){
var _index = _href.indexOf(\'//\'+location.hostname);
if(_index > 0){
var _index2 = _href.indexOf(\'?\');
if(_index2 > 0 && _index2 < _index){
_same = false;
}
}else{
_same = false;
}
}
return !_same;
};
_addEvent(document,\'click\',_onAnchorClick);
//扩展一个js中直接使用的页面跳转的方法，以拦截js中的页面跳转行为
location.dispatch2 = function(_url,_replace){
var delegate = false;
try{
delegate = !!top.GDispatcher;
}catch(e){
delegate = false;
}
// 处理对于非主站内的页面的跳转
if(_isNotMainsitePagePath(_url)){
if(delegate && top.location && top.location.href){
top.location.href = _url;
}else{
location.href = _url;
}
return;
}
if(delegate){
top.GDispatcher.dispatch(_url,_replace);
}else{
_url = GUtil.composeRefer(_url,GRef);
//邮箱音乐盒中，每次链接的跳转都要将proxy.html的地址合并到hash中
if(GRef&&GRef==\'mail\'){
var _hindex,_sindex,
_reg = /(https?:\\/\\/.+\\/proxy.html)/,
_hreg = /#(.*?)$/,
_href = decodeURIComponent(location.href);
if(!_reg.test(decodeURIComponent(_url))&&_reg.test(_href)){
_hindex = _url.indexOf(\'#\');
_sindex = _url.lastIndexOf(\'?\');
if(_hindex>0){
_url = _url+(_sindex>_hindex?\'&\':\'?\')+\'proxy=\'+encodeURIComponent(RegExp.$1);
}else{
_url = _url+\'#proxy=\'+encodeURIComponent(RegExp.$1);
}
}
}
if(_replace){
location.replace(_url);
}else{
location.href = _url;
}
}
};
})();
(function start() {
var targetUrl = \'https://music.163.com\';
// 首先检测hash规则, 在白名单内才进行跳转
var hashWhite = /^(\\/discover|\\/download|\\/login)/ig;
// 如果当前域不是163域名，那么强制跳转到163.com
var siteReg = /^(https?:\\/\\/)?([a-zA-Z0-9]+(-?[a-zA-Z0-9])*\\.){1,}?(163\\.com)$/ig;
if(hashWhite.test(window.location.pathname) && !siteReg.test(window.location.hostname)){
top.location.href = targetUrl;
}
})();(function(){
if(window.addEventListener){
window.addEventListener(\'scroll\', onScroll)
}else{
window.attachEvent(\'onscroll\', onScroll)
}
try{
top.scrollTopbar(0);
}catch(e){
}
function onScroll(){
try{
top.scrollTopbar(Math.max(document.body.scrollTop, document.documentElement.scrollTop));
}catch(e){
//ignore
}
};
})();</script>
<base href="//music.163.com/" target="_top">
<link rel="shortcut icon" href="//s1.music.126.net/style/favicon.ico?v20180823" />
<link href="//s2.music.126.net/web/s/core_de54d012d81d9248d366b0f56d21f9d9.css?de54d012d81d9248d366b0f56d21f9d9" type="text/css" rel="stylesheet"/><link href="//s2.music.126.net/web/s/pt_frame_dde58b16b37b2d88c7b2b199d2c7eb7f.css?dde58b16b37b2d88c7b2b199d2c7eb7f" type="text/css" rel="stylesheet"/>
</head>
<body>
<div data-module="discover" data-sub="other" id="g_top" class="m-top">&nbsp;</div>
<div id="g_nav" class="m-subnav">&nbsp;</div>
<script>
try{
top.matchNav("discover", "other");
}catch(e){
}
</script>
<div class="g-bd7 f-cb empty-login" id="album-empty-login">
<img src="https://p5.music.126.net/obj/wonDlsKUwrLClGjCm8Kx/60672821133/a070/bf56/a0d8/57094ce949c5d6bfdb57d2e0216dfe71.png" />
<div>登录后查看更多信息</div>
</div>
<div class="g-ft ">
<div class="m-ft">
<div class="wrap f-cb">
<ul class="enter">
<li class="unit">
<a class="logo logonew logo-trade f-tid" href="//music.163.com/st/web-sublicense/home" target="_blank"></a>
<span class="tt">云村交易所</span>
</li>
<li class="unit">
<a class="logonew logo-xstudio f-tid" href="https://xstudio.music.163.com" target="_blank"></a>
<span class="tt">X StudioAI歌手</span>
</li>
<li class="unit">
<a class="logo logonew logo-auth f-tid" href="//music.163.com/st/userbasic#/auth" target="_blank"></a>
<span class="tt">用户认证</span>
</li>
<li class="unit">
<a class="logo logonew logo-musician f-tid" href="https://tianyin.music.163.com" target="_blank"></a>
<span class="tt">天音AI写歌</span>
</li>
<li class="unit">
<a class="logonew logo-cloudsong f-tid" href="https://music.163.com/st/ad-song" target="_blank"></a>
<span class="tt">云推歌</span>
</li>
<li class="unit">
<a class="logo logonew logo-reward f-tid" href="//music.163.com/web/reward" target="_blank"></a>
<span class="tt">赞赏</span>
</li>
</ul>
<div class="copy">
<p class="link" id="music-link">
<a href="//st.music.163.com/official-terms/service" target="_blank" class="item s-fc3">服务条款</a><span class="line">|</span>
<a href="//st.music.163.com/official-terms/privacy" target="_blank" class="item s-fc3">隐私政策</a><span class="line">|</span>
<a href="//st.music.163.com/official-terms/children" target="_blank" class="item s-fc3">儿童隐私政策</a><span class="line">|</span>
<a href="//music.163.com/st/staticdeal/complaints.html" target="_blank" class="item s-fc3">版权投诉</a><span class="line">|</span>
<a href="http://ir.music.163.com" target="_blank" class="item s-fc3">投资者关系</a><span class="line">|</span>
<a href="https://music.163.com/ui/resource" rel="noopener noreferrer" target="_blank" class="item s-fc3">广告合作</a>
<span class="line">|</span>
<a onclick="nm.x.biLog(\'click\', {mspm:\'61f261f4b99b0d5882ce3a78\',page:\'web_netease_music_homepage\',target:\'base_fast_access\'});return true;" href="//mp.music.163.com/600948c936c13f4d09752e73/contact-us-web/index.html?source=Music-Main-Station" target="_blank" class="s-fc3">联系我们</a>
</p>
<p class="right s-fc3">
<a href="https://jubao.163.com" target="_blank" class="sep alink s-fc3">廉正举报</a>
<span class="sep span">不良信息举报邮箱: 51jubao@service.netease.com</span>
</p>
<p class="right s-fc3">
<span class="span">互联网宗教信息服务许可证：浙（2022）0000120</span>
<span class="span">增值电信业务经营许可证：浙B2-20150198</span>
<a href="https://beian.miit.gov.cn/#/Integrated/index" rel="noopener noreferrer" target="_blank" class="alink s-fc3">粤B2-20090191-18&nbsp;&nbsp;浙ICP备15006616号-4&nbsp;&nbsp;工业和信息化部备案管理系统网站</a>
</p>
<p class="right s-fc3">
<span class="sep span">网易公司版权所有©1997-2026</span><span class="span">杭州乐读科技有限公司运营：</span><a href="https://p6.music.126.net/obj/wonDlsKUwrLClGjCm8Kx/34942157981/2e30/30c1/ad1f/7be053a28e91dd8bafe49bdf6455cb2a.png" target="_blank" class="alink s-fc3">浙网文[2024] 0900-042号</a>
<a href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=33010902002564" rel="noopener noreferrer" target="_blank" class="alink s-fc3 police-link">
<span class="police-logo"></span>
<span class="police-text">浙公网安备 33010802013307号</span>
</a>
<a href="https://y.music.163.com/m/at/661f2af6e36f7c50ead8994b" rel="noopener noreferrer" target="_blank" class="alink s-fc3 police-link">
<span class="police-text">算法服务公示信息</span>
</a>
</p>
</div>
</div>
</div>
</div>
<a title="回到顶部" class="m-back" href="#" id="g_backtop" hidefocus="true" style="display:none;">回到顶部</a>
<div id="template-box" style="display:none;"> <div id="template-box" style="display:none;"> <textarea name="jst" id="ntp-login-nav" style="display:none;"><div class="lyct lyct-1 f-pr">
{if degrade}
<div class="login-list" style="min-height:332px">
<div class="n-log2 n-log2-1 f-cb">
<div class="u-main">
<div class="u-plt"></div>
<div class="f-mgt10">
<a href="javascript:;" data-action="login" data-type="mobile" class="u-btn2 u-btn2-2"><i>手机号登录</i></a>
</div>
<div class="f-mgt10">
<a href="javascript:;" class="u-btn2 u-btn2-1" data-action="reg"><i>注&#12288;册</i></a>
</div>
</div>
<div class="u-alt">
<ul>
<li><a href="https://music.163.com/api/sns/authorize?snsType=10&clientType=web2&callbackType=Login&forcelogin=true" target="_blank" data-action="login" data-type="thirdparty"><i class="u-mlg2 u-mlg2-wx"></i>微信登录</a></li>
<li><a href="https://music.163.com/api/sns/authorize?snsType=5&clientType=web2&callbackType=Login&forcelogin=true" target="_blank" data-action="login" data-type="thirdparty"><i class="u-mlg2 u-mlg2-qq"></i>QQ登录</a></li>
<li><a href="https://music.163.com/api/sns/authorize?snsType=2&clientType=web2&callbackType=Login&forcelogin=true" target="_blank" data-action="login" data-type="thirdparty"><i class="u-mlg2 u-mlg2-sn"></i>微博登录</a></li>
<li><a href="javascript:;" data-action="login" data-type="netease"><i class="u-mlg2 u-mlg2-wy"></i>网易邮箱账号登录</a></li>
</ul>
</div>
<div class="u-official-terms">
<input type="checkbox" id="j-official-terms">
<label for="j-official-terms" style="margin-left: 2px;">同意</label>
<a href="http://st.music.163.com/official-terms/service" target="_blank" style="color:#507DAF">《服务条款》</a>
<a href="http://st.music.163.com/official-terms/privacy" target="_blank" style="color:#507DAF">《隐私政策》</a>
<a href="https://st.music.163.com/official-terms/children" target="_blank" style="color:#507DAF">《儿童隐私政策》</a>
</div>
</div>
</div>
{else}
<div class="login-list f-hide" style="min-height:332px">
<div class="n-log2 n-log2-1 f-cb">
<div class="u-main">
<div class="u-plt"></div>
<div class="f-mgt10">
<a href="javascript:;" data-action="login" data-type="mobile" class="u-btn2 u-btn2-2"><i>手机号登录</i></a>
</div>
<div class="f-mgt10">
<a href="javascript:;" class="u-btn2 u-btn2-1" data-action="reg"><i>注&#12288;册</i></a>
</div>
</div>
<div class="u-alt">
<ul>
<li><a href="https://music.163.com/api/sns/authorize?snsType=10&clientType=web2&callbackType=Login&forcelogin=true" target="_blank" data-action="login" data-type="thirdparty"><i class="u-mlg2 u-mlg2-wx"></i>微信登录</a></li>
<li><a href="https://music.163.com/api/sns/authorize?snsType=5&clientType=web2&callbackType=Login&forcelogin=true" target="_blank" data-action="login" data-type="thirdparty"><i class="u-mlg2 u-mlg2-qq"></i>QQ登录</a></li>
<li><a href="https://music.163.com/api/sns/authorize?snsType=2&clientType=web2&callbackType=Login&forcelogin=true" target="_blank" data-action="login" data-type="thirdparty"><i class="u-mlg2 u-mlg2-sn"></i>微博登录</a></li>
<li><a href="javascript:;" data-action="login" data-type="netease"><i class="u-mlg2 u-mlg2-wy"></i>网易邮箱账号登录</a></li>
</ul>
</div>
<div class="u-official-terms">
<input type="checkbox" id="j-official-terms">
<label for="j-official-terms" style="margin-left: 2px;">同意</label>
<a href="http://st.music.163.com/official-terms/service" target="_blank" style="color:#507DAF">《服务条款》</a>
<a href="http://st.music.163.com/official-terms/privacy" target="_blank" style="color:#507DAF">《隐私政策》</a>
<a href="https://st.music.163.com/official-terms/children" target="_blank" style="color:#507DAF">《儿童隐私政策》</a>
</div>
</div>
<div class="n-scan" data-action="scan"></div>
</div>
<div class="lg n-login-scan" id="login-qrcode" style="min-height:332px">
<div class="cnt">
<div class="main j-flag">
<div class="f-cb">
<div class="phone"></div>
<div class="right">
<div class="title">扫码登录</div>
<div class="qr">
<div class="tip f-dn f-link j-flag f-hide">
<p>二维码已失效</p>
<a class="u-btn2" href="javascript:;" data-action="refresh">点击刷新</a>
</div>
<div class="canvas f-pen j-flag"></div>
</div>
<p class="txt j-flag">使用&nbsp;<a class="download-link" href="/download" target="_blank">网易云音乐APP</a>&nbsp;扫码登录</p>
</div>
</div>
</div>
<div class="suc j-flag f-hide">
<div class="suc-icon"></div>
<p class="suc-txt">扫描成功</p>
</div>
<p class="confirm j-flag f-hide">请在手机上确认登录</p>
<div id="otherbtn" class="otherbtn">
<a class="u-btn2 other f-hide" data-action="switch">选择其他登录模式</a>
</div>
</div>
</div>
{/if}
</div>
</textarea>
<textarea name="ntp" id="ntp-login-mobile" style="display:none;"><div class="lyct lyct-1">
<div class="n-log2 n-log2-2">
<div class="j-mob"></div>
<div class="pwdloginwrapper f-hide">
<div class="f-mgt10 inputbox">
<a href="javascript:;" class="f-fr s-fc3 forget" data-action="forget">忘记密码？</a>
<input type="password" name="pw" id="pw" class="j-pwd u-txt password" placeholder="请输入密码" autocomplete="new-password">
</div>
<div class="j-err u-err" style="display:none;"><i class="u-icn u-icn-25"></i><span></span></div>
<div class="f-mgt10 actionbox">
<a href="javascript:;" class="f-fr s-fc3" data-action="captchalogin">短信登录</a>
<label class="s-fc3"><input type="checkbox" checked="checked" class="j-auto u-auto">自动登录</label>
</div>
</div>
<div class="captchaloginwrapper">
<div class="f-mgt10">
<input style="box-sizing: content-box;" type="text" class="j-captcha js-input u-txt u-txt2" placeholder="请输入验证码" autocomplete="off" value="">
<span class="js-cd u-cd f-hide"></span>
<a href="javascript:;" class="js-send u-btn2 u-btn2-1" data-action="send"><i>获取验证码</i></a>
</div>
<div class="j-err u-err" style="display:none;"><i class="u-icn u-icn-25"></i><span></span></div>
<div class="f-mgt10 actionbox">
<a href="javascript:;" class="f-fr s-fc3 f-hide pwdlogin" data-action="pwdlogin">密码登录</a>
<label class="s-fc3"><input type="checkbox" checked="checked" class="j-auto u-auto">自动登录</label>
</div>
</div>
<div class="f-mgt20">
<div id="pwd-captcha"></div>
<a class="j-primary u-btn2 u-btn2-2" hidefocus="true" href="#" data-action="login"><i>登&#12288;录</i></a>
</div>
</div>
<div class="js-btmbar n-loglink2 f-cb">
<a href="javascript:;" data-action="select" class="f-fl s-primary">&lt;&nbsp;&nbsp;其他登录方式</a>
<a href="javascript:;" data-action="reg" class="f-fr">没有账号？免费注册&nbsp;&nbsp;&gt;</a>
</div>
</div>
</textarea>
<textarea name="ntp" id="ntp-login-netease" style="display:none;"><div class="lyct lyct-1 f-cb">
<div class="n-log2 n-log2-2">
<div class="f-pr" style="z-index:5;">
<input type="text" name="e" id="e" class="js-input u-txt" placeholder="请输入账号" autocomplete="off">
<ul class="js-suggest u-fill" style="visibility:hidden;"></ul>
</div>
<div class="f-mgt10">
<input type="password" name="epw" id="epw" class="js-input u-txt" placeholder="请输入密码" autocomplete="new-password">
</div>
<div class="js-captcha"></div>
<div class="ScapTcha js-scaptcha"></div>
<div class="u-err" style="display:none;"><i class="u-icn u-icn-25"></i><span></span></div>
<div class="f-mgt10">
<label class="s-fc3"><input type="checkbox" checked="checked" class="u-auto">自动登录</label>
<a href="//reg.163.com/getpasswd/RetakePassword.jsp" target="_blank" class="f-fr s-fc3">忘记密码？</a>
</div>
<div class="f-mgt20">
<a class="js-primary u-btn2 u-btn2-2" hidefocus="true" href="#" data-action="login"><i>登&#12288;录</i></a>
</div>
</div>
<div class="n-loglink2"><a href="javascript:;" data-action="select" class="s-primary">&lt;&nbsp;&nbsp;其他登录方式</a></div>
</div>
</textarea>
<textarea name="jst" id="jst-login-suggest" style="display:none;">{list suggests as item}
<li class="f-thide"><a href="#" data-action="suggest" title="${item|escape}">${item|escape}</a></li>
{/list}
</textarea>
<textarea name="ntp" id="ntp-reg-mobile" style="display:none;"><div class="lyct lyct-1 f-cb">
<div class="n-log2 n-log2-2">
<div class="s-fc3"><label>手机号：</label></div>
<div class="j-mob f-mgt10"></div>
<div class="f-mgt10 s-fc3"><label>密码：</label></div>
<div class="f-mgt10">
<input type="password" class="j-pwd u-txt" placeholder="设置登录密码，不少于8位" autocomplete="new-password">
</div>
<div class="j-err u-err"><i class="u-icn u-icn-25"></i><span></span></div>
<div class="pwd-validation f-hide">
<div class="j-err u-err j-pwd-valid"><i class="u-icn"></i><span>密码不能包含空格</span></div>
<div class="j-err u-err j-pwd-valid"><i class="u-icn"></i><span>包含字母、数字、符号中至少两种</span></div>
<div class="j-err u-err j-pwd-valid"><i class="u-icn"></i><span>密码长度为8-20位</span></div>
</div>
<div class="f-mgt20">
<a class="j-btn j-nextstep u-btn2 u-btn2-2" hidefocus="true" href="javascript:;" data-action="ok"><i>下一步</i></a>
</div>
</div>
<div class="n-loglink2"><a href="javascript:;" data-action="back" class="s-primary">&lt;&nbsp;&nbsp;返回登录</a></div>
</div>
</textarea>
<textarea name="ntp" id="ntp-verifycaptcha" style="display:none;"><div class="lyct lyct-1 f-cb">
<div class="n-log2 n-log2-2">
<p class="js-tip f-hide u-tip">云音乐将不再支持 <strong class="s-fc1">腾讯微博</strong> 登录方式，<br/>请绑定手机号，以免后续无法使用该账号</p>
<div class="js-mobwrap f-hide f-pdb20">
<p class="s-fc3">你的手机号：<strong class="s-fc1">+<span class="js-code"></span>&nbsp;<span class="js-mob"></span></strong></p>
<p class="s-fc4 f-mgt5">为了安全，我们会给你发送短信验证码</p>
</div>
<div class="js-mobwrap f-hide f-pdb10">
<div class="s-fc3"><label class="js-lbl"></label></div>
<div class="j-mob f-mgt10"></div>
<div class="s-fc3 f-mgt10"><label>验证码：</label></div>
</div>
<div id="captcha-input">
</div>
<div class="f-mgt20">
<a class="js-next u-btn2 u-btn2-2" hidefocus="true" href="#" data-action="next"><i></i></a>
</div>
</div>
<div class="js-btmbar n-loglink2 f-cb f-hide">
<a href="javascript:;" data-action="back" class="js-back f-hide f-fl s-primary">&lt;&nbsp;&nbsp;返回登录</a>
</div>
</div>
</textarea>
<textarea name="ntp" id="ntp-oldphonecheck" style="display:none;"><div class="lyct lyct-1 f-cb">
<div class="n-log2 n-log2-2" style="width:250px;">
<p class="js-tip f-hide u-tip">云音乐将不再支持 <strong class="s-fc1">腾讯微博</strong> 登录方式，<br/>请绑定手机号，以免后续无法使用该账号</p>
<div class="js-mobwrap f-hide f-pdb20">
<p class="s-fc3">你的手机号：<strong class="s-fc1">+<span class="js-code"></span>&nbsp;<span class="js-mob"></span></strong></p>
<p class="s-fc4 f-mgt5">为了安全，我们会给你发送短信验证码</p>
</div>
<div class="js-mobwrap f-pdb10">
<div class="s-fc3">
<label class="js-lbl">输入要解绑的完整手机号，用于验证您的身份</label>
</div>
<div class="f-mgt10 clearfix">
<div class="u-txtwrap">
<input type="text" class="js-txt u-txt" placeholder="请输入手机号" style="padding: 5px 6px;float: none;width: 100%">
</div>
</div>
<div class="u-err f-hide f-fl">
<i class="u-icn u-icn-25"></i><span class="errTxt"></span>
</div>
</div>
<div class="f-mgt20 f-tc">
<a class="js-next u-btn2 u-btn2-2" hidefocus="true" href="javascript:void(0);" data-action="next"><i>下一步</i></a>
</div>
</div>
<div class="js-btmbar n-loglink2 f-cb f-hide">
<a href="javascript:;" data-action="back" class="js-back f-hide f-fl s-primary">&lt;&nbsp;&nbsp;返回登录</a>
<a href="javascript:;" data-action="skip" class="js-skip f-hide f-fr">跳过&nbsp;&nbsp;&gt;</a>
</div>
</div>
</textarea>
<textarea name="jst" id="m-verifycaptcha-input" style="display:none;"><div class="f-cb">
<input style="box-sizing: content-box;" type="text" class="js-input u-txt u-txt2" placeholder="请输入验证码" value="">
<span class="js-cd u-cd f-hide"></span>
<a href="#" class="js-send u-btn2 u-btn2-1 f-hide" data-action="send"><i>获取验证码</i></a>
<div class="u-err"><i class="u-icn u-icn-25"></i><span></span></div>
</div>
</textarea>
<textarea name="jst" id="m-verifycaptcha-input2" style="display:none;"><div class="f-cb">
<div class="u-word">
<input type="text" class="js-input u-txt u-txt3" value="" maxlength="1" data-index="0">
</div>
<div class="u-word">
<input type="text" class="js-input u-txt u-txt3" value="" maxlength="1" data-index="1">
</div>
<div class="u-word">
<input type="text" class="js-input u-txt u-txt3" value="" maxlength="1" data-index="2">
</div>
<div class="u-word">
<input type="text" class="js-input u-txt u-txt3" value="" maxlength="1" data-index="3">
</div>
</div>
<div class="send">
<span class="js-cd u-btn u-btn2 f-hide"></span>
<a href="#" class="js-send u-btn f-hide" data-action="send" style="height:31px;"><i>获取验证码</i></a>
</div>
<div class="u-err"><i class="u-icn u-icn-25"></i><span></span></div>
</textarea>
<textarea name="ntp" id="ntp-setnickname" style="display:none;"><div class="lyct lyct-1 f-cb">
<div class="n-log2 n-log2-3">
<p class="s-fc1 f-tc">取一个昵称，让大家记住你</p>
<div class="f-mgt20">
<input type="text" class="js-flag u-txt" placeholder="昵称不少于4个字母或2个汉字">
</div>
<div class="f-cb ScapTcha js-flag" style="margin-top:10px;"></div>
<div class="u-err js-flag" class="f-hide"><i class="u-icn u-icn-25"></i><span></span></div>
<div class="f-mgt20">
<a class="u-btn2 u-btn2-2 js-flag" hidefocus="true" href="#" data-action="ok"><i>完成注册，开启云音乐</i></a>
</div>
</div>
</div>
</textarea>
<textarea name="ntp" id="ntp-reg-setting" style="display:none;"><div class="lyct lyct-1 f-cb">
<div class="n-log2 n-log2-3">
<p class="js-tip s-fc1 f-tc f-mg20">取一个昵称，让大家记住你</p>
<div class="f-mgt20">
<input type="text" class="js-input u-txt" placeholder="昵称不少于4个字母或2个汉字">
</div>
<div class="u-err" class="f-hide"><i class="u-icn u-icn-25"></i><span></span></div>
<div class="f-mgt20">
<a class="js-primary u-btn2 u-btn2-2" hidefocus="true" href="#" data-action="ok"><i>完成注册，开启云音乐</i></a>
</div>
</div>
</div>
</textarea>
<textarea name="ntp" id="ntp-setpassword" style="display:none;"><div class="lyct lyct-1 f-cb">
<div class="n-log2 n-log2-3">
<p class="js-tip u-tip f-hide">云音乐将不再支持 <strong class="s-fc1">腾讯微博</strong> 登录方式，<br/>设置登录密码，以后可以使用手机号登录</p>
<p class="js-tip s-fc3 f-hide">你的手机号：<strong class="s-fc1">+<span class="js-code"></span>&nbsp;<span class="js-mob"></span></strong></p>
<p class="js-tip s-fc3 f-mg20 f-tc f-hide">设置密码后，可以直接用该手机号+密码登录</p>
<div class="f-mgt10">
<input type="password" class="js-input u-txt f-mgt10" placeholder="设置登录密码，不少于8位" autocomplete="new-password">
</div>
<div class="u-err f-hide"><i class="u-icn u-icn-25"></i><span></span></div>
<div class="pwd-validation f-hide">
<div class="j-err u-err j-pwd-valid"><i class="u-icn"></i><span>密码不能包含空格</span></div>
<div class="j-err u-err j-pwd-valid"><i class="u-icn"></i><span>包含字母、数字、符号中至少两种</span></div>
<div class="j-err u-err j-pwd-valid"><i class="u-icn"></i><span>密码长度为8-20位</span></div>
</div>
<div class="f-mgt20">
<a class="js-primary u-btn2 u-btn2-2" hidefocus="true" href="#" data-action="ok"><i></i></a>
</div>
</div>
<div class="js-btmbar n-loglink2 f-cb f-hide">
<a href="javascript:;" data-action="skip" class="f-fr">跳过&nbsp;&nbsp;&gt;</a>
</div>
</div>
</textarea>
<textarea name="txt" id="txt-login-captcha" style="display:none;"><div class="f-mgt10">
<input id="captcha" type="text" class="u-txt u-code j-flag" placeholder="请输入验证码">
<img class="u-captcha j-flag" src="">
</div>
</textarea>
<textarea name="ntp" id="m-captcha-layer" style="display:none;"><div class="wrap">
<p class="s-fc3">如果你不是机器人输入验证码一定没问题！</p>
<p class="input f-cb j-flag">
</p>
<div class="u-err f-hide j-flag"><i class="u-icn u-icn-25"></i>账号或密码错误</div>
<div class="btnwrap">
<a data-action="ok" class="u-btn2 u-btn2-2 u-btn2-w2" hidefocus="true" href="javascript:;"><i>确 定</i></a>
<a data-action="cc" class="u-btn2 u-btn2-1 u-btn2-w2" hidefocus="true" href="javascript:;"><i>取消</i></a>
</div>
</div>
</textarea>
<textarea name="ntp" id="wgt-phone-input" style="display:none;"><div class="u-phonewrap">
<a class="current" href="javascript:;" data-action="toggle">
<span class="j-code">+86</span>
<span class="icn u-icn2 u-icn2-17"></span>
</a>
<div class="txtwrap">
<input style="box-sizing: content-box;" type="text" name="p" id="p" class="j-phone txt u-txt" placeholder="请输入手机号" autocomplete="off">
</div>
<ul class="j-list options f-hide"></ul>
</div>
</textarea>
<textarea name="jst" id="wgt-countrycode-item" style="display:none;">{list countries as x}
<li class="itm f-cb" data-action="select" data-index="${x_index}">
<span class="lt">${x.zh}</span>
<span class="rt">+${x.code}</span>
</li>
{/list}
</textarea>
<textarea name="ntp" id="ntp-loginverify" style="display:none;"><div class="lyct lyct-1 f-cb">
<div class="n-log2 n-log2-3">
<div class="pic"></div>
<p class="s-fc1 f-tc">
由于你在非受信任的设备上登录，需要进行短信验证(<span class="js-mobile"></span>)
</p>
<div class="f-mgt20">
<a class="u-btn2 u-btn2-2 js-flag" hidefocus="true" data-action="ok"><i>通过短信验证身份</i></a>
</div>
</div>
</div>
</textarea>
</div>
<textarea name="ntp" id="m-wgt-selector" style="display:none;"><div class="u-slt f-pr"><span class="curr f-thide"></span><i class="btn"></i><ul></ul></div>
</textarea>
<textarea name="jst" id="m-wgt-selector-list" style="display:none;">{list data as x}<li class="f-thide"><a href="#" data-value="${x.v}" title="${x.t}">${x.t}</a></li>{/list}
</textarea>
<textarea name="ntp" id="m-wgt-create" style="display:none;"><div class="lyct m-crgd f-cb f-tc">
<p>歌单名：<input type="text" class="u-txt j-flag"></p>
<div class="u-err f-vhide j-flag"><i class="u-icn u-icn-25"></i>错误提示</div>
<p class="tip s-fc4">可通过“收藏”将音乐添加到新歌单中</p>
<div class="btn">
<a href="javascript:;" class="u-btn2 u-btn2-2 u-btn2-w2 j-flag" hidefocus="true"><i>新 建</i></a>
<a href="javascript:;" class="u-btn2 u-btn2-1 u-btn2-w2 j-flag" hidefocus="true"><i>取 消</i></a>
</div>
</div>
</textarea>
<textarea name="ntp" id="m-wgt-comment" style="display:none;"><div class="u-title u-title-1">
<h3><span class="f-ff2">评论</span></h3><span class="sub s-fc3">共<span class="j-flag">0</span>条评论</span>
</div>
<div class="m-cmmt">
<div class="iptarea">
<div class="head"><img src="http://s4.music.126.net/style/web2/img/default/default_avatar.jpg?param=50y50"></div>
<div class="j-flag"></div>
</div>
<div class="cmmts j-flag"></div>
<div class="j-flag"></div>
<div class="anon-comment j-flag">
<div class="des">
<span
class="title"
data-type="login">
登录
</span>
<span>
后查看精彩评论
</span>
</div>
<a
class="button"
href="javascript:void(0)"
data-type="login">
立即登录
</a>
</div>
</div>
</textarea>
<textarea name="ntp" id="m-wgt-comment2" style="display:none;"><div class="m-dynamic">
<div class="dbox dbox-cmt">
<span class="darr"><i class="bd">◆</i><i class="bg">◆</i></span>
<div class="m-cmmt m-cmmt-s">
<div class="iptarea j-flag">
</div>
<div class="cmmts">
<div class="j-flag"></div>
<div class="dmore dmore-cmt f-cb">
<div class="dhas s-fc3">后面还有<span class="j-flag">0</span>条评论，<a data-type="viewmore" class="s-fc3 f-ff1" href="javascript:void(0)">查看更多&gt;</a></div>
<a data-type="cc" class="dtoggle" href="javascript:void(0)">收起<i data-type="cc" class="u-icn u-icn-61"></i></a>
</div>
</div>
</div>
</div>
</div>
</textarea>
<textarea name="ntp" id="m-wgt-comment3" style="display:none;"><div class="dcmt">
<p><span class="f-fw1">评论</span> (<span class="j-flag"></span>)</p>
<div class="m-cmmt m-cmmt-s">
<div class="iptarea j-flag">
</div>
<div class="cmmts j-flag">
</div>
<div class="j-flag">
</div>
</div>
</div>
</textarea>
<textarea name="jst" id="m-wgt-comment-item" style="display:none;"> {list beg..end as y}
{var x=xlist[y]}
{if !!x}
<div id="${x.commentId|seed}" class="itm" data-id="${x.commentId}">
<div class="head">
<a href="/user/home?id=${x.user.userId}"><img src="${x.user.avatarUrl}?param=50y50"></a>
</div>
<div class="cntwrap">
<div class="">
<div class="cnt f-brk">
<a href="/user/home?id=${x.user.userId}" class="s-fc7">${escape(x.user.nickname)}</a>
{if x.user.avatarDetail && x.user.avatarDetail.identityIconUrl}
<img style="margin-right:5px;vertical-align:-2px;" width=13 height=13 src="${x.user.avatarDetail.identityIconUrl}" />
{/if}
{if x.user.vipRights}
{if x.user.vipRights.redplus && x.user.vipRights.redplus.vipCode === 300 && x.user.vipRights.redplus.rights && x.user.vipRights.redplus.iconUrl}
<img src="${x.user.vipRights.redplus.iconUrl}"
class="brand-tag brand-svip" />
{elseif x.user.vipRights.associator && x.user.vipRights.associator.rights && x.user.vipRights.redVipLevel}
{if x.user.vipRights.associator.iconUrl}
<img src="${x.user.vipRights.associator.iconUrl}"
class="brand-tag brand-vip" />
{elseif x.user.vipRights.redVipLevel == 1}
{if useNewVipIcon}
<img src="//p5.music.126.net/obj/wonDlsKUwrLClGjCm8Kx/31289771075/9cde/206c/1521/ae97069bf19817f1fff4e3afda1d3998.png"
class="brand-tag brand-vip" />
{else}
<img src="//p6.music.126.net/obj/wo3DlcOGw6DClTvDisK1/4213922817/9124/a83c/7eb7/6d7d81b608bfb56d7fb286bd8eb72346.png"
class="brand-tag brand-vip" />
{/if}
{elseif x.user.vipRights.redVipLevel == 2}
{if useNewVipIcon}
<img src="//p5.music.126.net/obj/wonDlsKUwrLClGjCm8Kx/31289779981/0735/9a76/996b/2b858ffcf51cb298412b566407c4cc75.png"
class="brand-tag brand-vip" />
{else}
<img src="//p5.music.126.net/obj/wo3DlcOGw6DClTvDisK1/4213922900/5a1b/446b/b722/ec5a532c258824e8b59a45c166195e90.png"
class="brand-tag brand-vip" />
{/if}
{elseif x.user.vipRights.redVipLevel == 3}
{if useNewVipIcon}
<img src="//p5.music.126.net/obj/wonDlsKUwrLClGjCm8Kx/31289796623/21a9/2cb2/8817/596f81c8bb28d1bca5f332ac3dc9a79e.png"
class="brand-tag brand-vip" />
{else}
<img src="//p6.music.126.net/obj/wo3DlcOGw6DClTvDisK1/4213922957/d393/4206/8928/a082dd9a7e7bb69e84b138b8df7bbcd0.png"
class="brand-tag brand-vip" />
{/if}
{elseif x.user.vipRights.redVipLevel == 4}
{if useNewVipIcon}
<img src="//p5.music.126.net/obj/wonDlsKUwrLClGjCm8Kx/31289814330/170d/189e/70cb/75a12e81f2f6f92407419e417e9777b0.png"
class="brand-tag brand-vip" />
{else}
<img src="//p6.music.126.net/obj/wo3DlcOGw6DClTvDisK1/4213923065/dc4e/2b9c/7677/20a6644c6e3a093d7accce919ae7b169.png"
class="brand-tag brand-vip" />
{/if}
{elseif x.user.vipRights.redVipLevel == 5}
{if useNewVipIcon}
<img src="//p5.music.126.net/obj/wonDlsKUwrLClGjCm8Kx/31289819871/cae8/cbb4/63e2/feee66e7a731f20d2ce7aab9e92d1f68.png"
class="brand-tag brand-vip" />
{else}
<img src="//p5.music.126.net/obj/wo3DlcOGw6DClTvDisK1/4213923094/81eb/9288/68a5/a427a0dbf899d616c3f715272a71ee59.png"
class="brand-tag brand-vip" />
{/if}
{elseif x.user.vipRights.redVipLevel == 6}
{if useNewVipIcon}
<img src="//p5.music.126.net/obj/wonDlsKUwrLClGjCm8Kx/31289839236/54ce/9c06/9eae/861f11a2e2666f34ad7f201e001d9221.png"
class="brand-tag brand-vip" />
{else}
<img src="//p6.music.126.net/obj/wo3DlcOGw6DClTvDisK1/4213923139/f08a/c6ea/10ee/f7e2deef21937a1042e370c47525c956.png"
class="brand-tag brand-vip" />
{/if}
{elseif x.user.vipRights.redVipLevel == 7}
{if useNewVipIcon}
<img src="//p5.music.126.net/obj/wonDlsKUwrLClGjCm8Kx/31289847457/5230/7279/6543/ee2a0c6b2941a9647669e3ca522c350a.png"
class="brand-tag brand-vip" />
{else}
<img src="//p5.music.126.net/obj/wo3DlcOGw6DClTvDisK1/4213923212/f4f1/83be/e735/2099233f0f7b80e35aff0ab77374ee41.png"
class="brand-tag brand-vip" />
{/if}
{/if}
{elseif x.user.vipRights.musicPackage && x.user.vipRights.musicPackage.rights}
{if x.user.vipRights.musicPackage.iconUrl}
<img src="${x.user.vipRights.musicPackage.iconUrl}"
class="brand-tag brand-package" />
{else}
<img src="//p1.music.126.net/G2KYG9JjrGGP5grSaXOZaw==/109951163309837705.png"
class="brand-tag brand-package" />
{/if}
{elseif x.user.vipRights.redVipAnnualCount >= 1}
{if useNewVipIcon}
<img src="//p6.music.126.net/obj/wonDlsKUwrLClGjCm8Kx/31290261228/d8c6/b0fb/b236/ccc907aabf076e224ac6f2ae76d045e3.png" class="brand-tag brand-vip"/>
{else}
<img src="//p1.music.126.net/y8pM-M1mytg6B1ThedCbJA==/109951163709550847.png" class="brand-tag brand-vip"/>
{/if}
{elseif x.user.vipRights.associator && x.user.vipRights.associator.rights}
{if useNewVipIcon}
<img src="//p6.music.126.net/obj/wonDlsKUwrLClGjCm8Kx/31289879300/0ce6/0791/894f/2d90cb8ac138e4e4eda83f13d7979e88.png"
class="brand-tag brand-vip" />
{else}
<img src="//p1.music.126.net/iOnYL-pAvH2LuQfStGOjfQ==/109951163709553273.png"
class="brand-tag brand-vip" />
{/if}
{/if}
{/if}
{if !!x.beRepliedUser}
&nbsp;回复&nbsp;<a href="/user/home?id=${x.beRepliedUser.userId}" class="s-fc7">${escape(x.beRepliedUser.nickname)}</a>
${getAuthIcon(x.beRepliedUser)}
{if x.beRepliedUser.vipRights}
{if x.beRepliedUser.vipRights.redplus && x.beRepliedUser.vipRights.redplus.vipCode === 300 && x.beRepliedUser.vipRights.redplus.rights && x.beRepliedUser.vipRights.redplus.iconUrl}
<img src="${x.beRepliedUser.vipRights.redplus.iconUrl}"
class="brand-tag brand-svip" />
{elseif x.beRepliedUser.vipRights.associator && x.beRepliedUser.vipRights.associator.rights}
{if x.beRepliedUser.vipRights.redVipAnnualCount >= 1}
{if useNewVipIcon}
<img src="//p6.music.126.net/obj/wonDlsKUwrLClGjCm8Kx/31290261228/d8c6/b0fb/b236/ccc907aabf076e224ac6f2ae76d045e3.png" class="brand-tag brand-vip"/>
{else}
<img src="//p1.music.126.net/y8pM-M1mytg6B1ThedCbJA==/109951163709550847.png" class="brand-tag brand-vip"/>
{/if}
{elseif x.beRepliedUser.vipRights.associator.iconUrl}
<img src="${x.beRepliedUser.vipRights.associator.iconUrl}"
class="brand-tag brand-vip" />
{else}
{if useNewVipIcon}
<img src="//p6.music.126.net/obj/wonDlsKUwrLClGjCm8Kx/31289879300/0ce6/0791/894f/2d90cb8ac138e4e4eda83f13d7979e88.png"
class="brand-tag brand-vip" />
{else}
<img src="//p1.music.126.net/iOnYL-pAvH2LuQfStGOjfQ==/109951163709553273.png"
class="brand-tag brand-vip" />
{/if}
{/if}
{elseif x.beRepliedUser.vipRights.musicPackage && x.beRepliedUser.vipRights.musicPackage.rights}
{if x.beRepliedUser.vipRights.musicPackage.iconUrl}
<img src="${x.beRepliedUser.vipRights.musicPackage.iconUrl}"
class="brand-tag brand-package" />
{else}
<img src="//p1.music.126.net/G2KYG9JjrGGP5grSaXOZaw==/109951163309837705.png"
class="brand-tag brand-package" />
{/if}
{/if}
{/if}
{/if}
：${getRichText(escape(x.content),\'s-fc7\')}
{if !!x.expressionUrl}
<div class="u-expression"><img src="${x.expressionUrl}?param=70y70"></img></div>
{/if}
</div>
</div>
{if x.beReplied&&x.beReplied.length}
{var replied = x.beReplied[0]}
<div class="que f-brk f-pr s-fc3">
<span class="darr"><i class="bd">◆</i><i class="bg">◆</i></span>
{if (replied && replied.status>=0) && (replied.content || replied.expressionUrl || replied.contentPicUrl)}
<a class="s-fc7" href="/user/home?id=${replied.user.userId}">${replied.user.nickname}${getAuthIcon(replied.user)}</a>
{if replied.user.vipRights}
{if replied.user.vipRights.redplus && replied.user.vipRights.redplus.vipCode === 300 && replied.user.vipRights.redplus.rights && replied.user.vipRights.redplus.iconUrl}
<img src="${replied.user.vipRights.redplus.iconUrl}" class="brand-tag brand-svip" />
{elseif replied.user.vipRights.associator && replied.user.vipRights.associator.rights}
{if replied.user.vipRights.redVipAnnualCount >= 1}
{if useNewVipIcon}
<img src="//p6.music.126.net/obj/wonDlsKUwrLClGjCm8Kx/31290261228/d8c6/b0fb/b236/ccc907aabf076e224ac6f2ae76d045e3.png" class="brand-tag brand-vip"/>
{else}
<img src="//p1.music.126.net/y8pM-M1mytg6B1ThedCbJA==/109951163709550847.png" class="brand-tag brand-vip"/>
{/if}
{elseif replied.user.vipRights.associator.iconUrl}
<img src="${replied.user.vipRights.associator.iconUrl}"
class="brand-tag brand-vip" />
{else}
{if useNewVipIcon}
<img src="//p6.music.126.net/obj/wonDlsKUwrLClGjCm8Kx/31289879300/0ce6/0791/894f/2d90cb8ac138e4e4eda83f13d7979e88.png"
class="brand-tag brand-vip" />
{else}
<img src="//p1.music.126.net/iOnYL-pAvH2LuQfStGOjfQ==/109951163709553273.png"
class="brand-tag brand-vip" />
{/if}
{/if}
{elseif replied.user.vipRights.musicPackage && replied.user.vipRights.musicPackage.rights}
{if replied.user.vipRights.musicPackage.iconUrl}
<img src="${replied.user.vipRights.musicPackage.iconUrl}"
class="brand-tag brand-package" />
{else}
<img src="//p1.music.126.net/G2KYG9JjrGGP5grSaXOZaw==/109951163309837705.png"
class="brand-tag brand-package" />
{/if}
{/if}
{/if}
：${getRichText(escape(replied.content),\'s-fc7\')}
{if !!replied.expressionUrl}
<div class="u-expression"><img src="${replied.expressionUrl}?param=70y70"></img></div>
{elseif !!replied.contentPicUrl}
[图片]
{/if}
{else}
该评论已删除
{/if}
</div>
{/if}
<div class="rp">
<div class="time s-fc4">${timeformat(x.time)}</div>
{if x.topCommentId}<span class="top">音乐人置顶</span>{/if}
{if canTop()&&GUser&&GUser.userId&&(GUser.userId==x.user.userId)}
<span class="dlt">{if x.topCommentId}<a href="javascript:void(0)" class="s-fc3" data-id="${x.commentId}" data-tid="${x.topCommentId}" data-type="canceltop">解除置顶</a>{else}<a href="javascript:void(0)" class="s-fc3" data-id="${x.commentId}" data-type="gotop">置顶评论</a>{/if}<span class="sep">|</span></span>
{/if}
{if GUser&&GUser.userId&&(GUser.userId==x.user.userId||GUser.userId==resUserId)}
<span class="dlt"><a href="javascript:void(0)" class="s-fc3" data-id="${x.commentId}" {if x.topCommentId}data-tid="${x.topCommentId}" {/if}data-type="delete">删除</a><span class="sep">|</span></span>
{else}
<span class="dlt"><span style="display: none;" class="j-delete-comment"><a href="javascript:void(0)" class="s-fc3" data-id="${x.commentId}" {if x.topCommentId}data-tid="${x.topCommentId}" {/if}data-type="delete">删除</a><span class="sep">|</span></span></span>
{/if}
{if GAllowRejectComment}
{if hot||!x.isRemoveHotComment}
<span class="dlt"><a href="javascript:void(0)" class="s-fc3" data-id="${x.commentId}" data-type="reject">移除精彩评论</a><span class="sep">|</span></span>
{else}
<span class="s-fc3">已移除精彩评论</span><span class="sep">|</span>
{/if}
{/if}
{if !x.topCommentId}<a data-id="${x.commentId}" data-type="{if !x.liked}like{else}unlike{/if}" href="javascript:void(0)"><i class="zan u-icn2 u-icn2-{if x.liked}13{else}12{/if}"></i>{if x.likedCount} (${getPlayCount(x.likedCount)}){/if}</a>
<span class="sep">|</span>{/if}
<a href="javascript:void(0)" class="s-fc3" data-id="${x.commentId}" data-type="reply">回复</a>
</div>
</div>
</div>
{/if}
{/list}
</textarea>
<textarea name="jst" id="m-wgt-comment-item-2" style="display:none;"> {list beg..end as y}
{var x=xlist[y]}
<div class="itm" data-id="${x.commentId}">
<div class="head">
<a href="/user/home?id=${x.user.userId}"><img src="${x.user.avatarUrl}?param=50y50"></a>
</div>
<div class="cntwrap">
<div class="cnt2 f-brk">
<a href="/user/home?id=${x.user.userId}" class="s-fc7">${escape(x.user.nickname)}</a>
{if x.user.avatarDetail && x.user.avatarDetail.identityIconUrl}
<img style="margin-right:5px;vertical-align:-2px;" width=13 height=13 src="${x.user.avatarDetail.identityIconUrl}" />
{/if}
{if x.user.vipRights}
{if x.user.vipRights.redplus && x.user.vipRights.redplus.vipCode === 300 && x.user.vipRights.redplus.rights && x.user.vipRights.redplus.iconUrl}
<img src="${x.user.vipRights.redplus.iconUrl}"
class="brand-tag brand-svip" />
{elseif x.user.vipRights.associator && x.user.vipRights.associator.rights}
{if x.user.vipRights.associator.iconUrl}
<img src="${x.user.vipRights.associator.iconUrl}"
class="brand-tag brand-vip" />
{elseif x.user.vipRights.redVipLevel == 1}
<img src="//p6.music.126.net/obj/wo3DlcOGw6DClTvDisK1/4213922817/9124/a83c/7eb7/6d7d81b608bfb56d7fb286bd8eb72346.png"
class="brand-tag brand-vip" />
{elseif x.user.vipRights.redVipLevel == 2}
<img src="//p5.music.126.net/obj/wo3DlcOGw6DClTvDisK1/4213922900/5a1b/446b/b722/ec5a532c258824e8b59a45c166195e90.png"
class="brand-tag brand-vip" />
{elseif x.user.vipRights.redVipLevel == 3}
<img src="//p6.music.126.net/obj/wo3DlcOGw6DClTvDisK1/4213922957/d393/4206/8928/a082dd9a7e7bb69e84b138b8df7bbcd0.png"
class="brand-tag brand-vip" />
{elseif x.user.vipRights.redVipLevel == 4}
<img src="//p6.music.126.net/obj/wo3DlcOGw6DClTvDisK1/4213923065/dc4e/2b9c/7677/20a6644c6e3a093d7accce919ae7b169.png"
class="brand-tag brand-vip" />
{elseif x.user.vipRights.redVipLevel == 5}
<img src="//p5.music.126.net/obj/wo3DlcOGw6DClTvDisK1/4213923094/81eb/9288/68a5/a427a0dbf899d616c3f715272a71ee59.png"
class="brand-tag brand-vip" />
{elseif x.user.vipRights.redVipLevel == 6}
<img src="//p6.music.126.net/obj/wo3DlcOGw6DClTvDisK1/4213923139/f08a/c6ea/10ee/f7e2deef21937a1042e370c47525c956.png"
class="brand-tag brand-vip" />
{elseif x.user.vipRights.redVipLevel == 7}
<img src="//p5.music.126.net/obj/wo3DlcOGw6DClTvDisK1/4213923212/f4f1/83be/e735/2099233f0f7b80e35aff0ab77374ee41.png"
class="brand-tag brand-vip" />
{/if}
{elseif x.user.vipRights.musicPackage && x.user.vipRights.musicPackage.rights}
{if x.user.vipRights.musicPackage.iconUrl}
<img src="${x.user.vipRights.musicPackage.iconUrl}"
class="brand-tag brand-package" />
{else}
<img src="//p1.music.126.net/G2KYG9JjrGGP5grSaXOZaw==/109951163309837705.png"
class="brand-tag brand-package" />
{/if}
{/if}
{/if}
{if !!x.beRepliedUser}
&nbsp;回复&nbsp;<a href="/user/home?id=${x.beRepliedUser.userId}" class="s-fc7">${escape(x.beRepliedUser.nickname)}</a>
${getAuthIcon(x.beRepliedUser)}
{if x.beRepliedUser.vipRights}
{if x.beRepliedUser.vipRights.redplus && x.beRepliedUser.vipRights.redplus.vipCode === 300 && x.beRepliedUser.vipRights.redplus.rights && x.beRepliedUser.vipRights.redplus.iconUrl}
<img src="${x.beRepliedUser.vipRights.redplus.iconUrl}" class="brand-tag brand-svip" />
{elseif x.beRepliedUser.vipRights.associator && x.beRepliedUser.vipRights.associator.rights}
{if x.beRepliedUser.vipRights.redVipAnnualCount >= 1}
<img src="//p1.music.126.net/y8pM-M1mytg6B1ThedCbJA==/109951163709550847.png" class="brand-tag brand-vip"/>
{elseif x.beRepliedUser.vipRights.associator.iconUrl}
<img src="${x.beRepliedUser.vipRights.associator.iconUrl}"
class="brand-tag brand-vip" />
{else}
<img src="//p1.music.126.net/iOnYL-pAvH2LuQfStGOjfQ==/109951163709553273.png"
class="brand-tag brand-vip" />
{/if}
{elseif x.beRepliedUser.vipRights.musicPackage && x.beRepliedUser.vipRights.musicPackage.rights}
{if x.beRepliedUser.vipRights.musicPackage.iconUrl}
<img src="${x.beRepliedUser.vipRights.musicPackage.iconUrl}"
class="brand-tag brand-package" />
{else}
<img src="//p1.music.126.net/G2KYG9JjrGGP5grSaXOZaw==/109951163309837705.png"
class="brand-tag brand-package" />
{/if}
{/if}
{/if}
{/if}
：${getRichText(escape(x.content),\'s-fc7\')}
{if !!x.expressionUrl}
<div class="u-expression"><img src="${x.expressionUrl}?param=70y70"></img></div>
{/if}
</div>
{if x.beReplied&&x.beReplied.length}
{var replied = x.beReplied[0]}
<div class="que f-brk f-pr s-fc3">
<span class="darr"><i class="bd">◆</i><i class="bg">◆</i></span>
{if replied && (replied.content || replied.expressionUrl || replied.contentPicUrl || replied.contentPicNosKey)}
<a class="s-fc7" href="/user/home?id=${replied.user.userId}">${replied.user.nickname}${getAuthIcon(replied.user)}</a>
{if replied.user.vipRights}
{if replied.user.vipRights.redplus && replied.user.vipRights.redplus.vipCode === 300 && replied.user.vipRights.redplus.rights && replied.user.vipRights.redplus.iconUrl}
<img src="${replied.user.vipRights.redplus.iconUrl}" class="brand-tag brand-svip" />
{elseif replied.user.vipRights.associator && replied.user.vipRights.associator.rights}
{if replied.user.vipRights.redVipAnnualCount >= 1}
<img src="//p1.music.126.net/y8pM-M1mytg6B1ThedCbJA==/109951163709550847.png" class="brand-tag brand-vip"/>
{elseif replied.user.vipRights.associator.iconUrl}
<img src="${replied.user.vipRights.associator.iconUrl}"
class="brand-tag brand-vip" />
{else}
<img src="//p1.music.126.net/iOnYL-pAvH2LuQfStGOjfQ==/109951163709553273.png"
class="brand-tag brand-vip" />
{/if}
{elseif replied.user.vipRights.musicPackage && replied.user.vipRights.musicPackage.rights}
{if replied.user.vipRights.musicPackage.iconUrl}
<img src="${replied.user.vipRights.musicPackage.iconUrl}"
class="brand-tag brand-package" />
{else}
<img src="//p1.music.126.net/G2KYG9JjrGGP5grSaXOZaw==/109951163309837705.png"
class="brand-tag brand-package" />
{/if}
{/if}
{/if}
：${getRichText(escape(replied.content),\'s-fc7\')}
{if !!replied.expressionUrl}
<div class="u-expression"><img src="${replied.expressionUrl}?param=70y70"></img></div>
{elseif !!replied.contentPicUrl || !!replied.contentPicNosKey}
[图片]
{/if}
{else}
该评论已删除
{/if}
</div>
{/if}
<div class="rp">
<div class="time s-fc4">${timeformat(x.time)}</div>
{if GUser&&GUser.userId&&(GUser.userId==x.user.userId||GUser.userId==resUserId)}
<span class="dlt">
<a href="javascript:void(0)" class="s-fc3" data-id="${x.commentId}" data-type="delete">删除</a><span class="sep">|</span>
</span>
{else}
<span class="dlt"><span style="display: none;" class="j-delete-comment"><a href="javascript:void(0)" class="s-fc3" data-id="${x.commentId}" {if x.topCommentId}data-tid="${x.topCommentId}" {/if}data-type="delete">删除</a><span class="sep">|</span></span></span>
{/if}
<a data-id="${x.commentId}" data-type="{if !x.liked}like{else}unlike{/if}" href="javascript:void(0)"><i class="zan u-icn2 u-icn2-{if x.liked}13{else}12{/if}"></i>{if x.likedCount} (${getPlayCount(x.likedCount)}){/if}</a>
<span class="sep">|</span>
<a href="javascript:void(0)" class="s-fc3" data-id="${x.commentId}" data-type="reply">回复</a>
</div>
</div>
</div>
{/list}
</textarea>
<textarea name="jst" id="m-wgt-input-1" style="display:none;"> <div class="m-cmmtipt f-cb f-pr">
<div class="u-txtwrap holder-parent f-pr">
<textarea class="u-txt area j-flag" data-type="" placeholder="${placeholder}"><&#47;textarea>
</div>
<div class="btns f-cb f-pr">
<i class="icn u-icn u-icn-36 j-flag"></i><i class="icn u-icn u-icn-41 j-flag"></i>
<a href="javascript:void(0)" class="btn u-btn u-btn-1 j-flag">评论</a>
<span class="zs s-fc4 j-flag">110/120</span>
</div>
<div class="corr u-arr"><em class="arrline">◆</em><span class="arrclr">◆</span></div>
</div>
</textarea>
<textarea name="jst" id="m-wgt-input-2" style="display:none;"> <div class="rept m-quk m-quk-1 f-pr">
<div class="iner">
<div class="corr u-arr u-arr-1"><em class="arrline">◆</em><span class="arrclr">◆</span></div>
<div class="m-cmmtipt m-cmmtipt-1 f-cb f-pr">
<div class="u-txtwrap holder-parent f-pr j-wrap">
<textarea class="u-txt area j-flag" placeholder="${placeholder}"><&#47;textarea>
</div>
<div class="btns f-cb f-pr">
<i class="icn u-icn u-icn-36 j-flag"></i><i class="icn u-icn u-icn-41 j-flag"></i>
<a href="javascript:void(0)" class="btn u-btn u-btn-1 j-flag">回复</a>
<span class="zs s-fc4 j-flag">110/120</span>
</div>
</div>
</div>
</div>
</textarea>
<textarea name="jst" id="m-wgt-input-3" style="display:none;"> <div class="m-cmmtipt f-cb f-pr">
<div class="u-txtwrap holder-parent f-pr">
<textarea class="u-txt area j-flag" placeholder="${placeholder}"><&#47;textarea>
</div>
<div class="btns f-cb f-pr">
<i class="icn u-icn u-icn-36 j-flag"></i><i class="icn u-icn u-icn-41 j-flag"></i>
<a class="btn u-btn u-btn-1 j-flag" href="javascript:void(0)">回复</a>
<span class="zs s-fc4 j-flag">110/120</span>
</div>
</div>
</textarea>
<textarea name="jst" id="m-wgt-input-4" style="display:none;"> <div class="m-cmmtipt f-cb f-pr">
<div class="u-txtwrap f-pr">
<textarea class="u-txt area j-flag"><&#47;textarea>
</div>
<div class="btns f-cb f-pr">
<i class="icn u-icn u-icn-36 j-flag"></i><i class="icn u-icn u-icn-41 j-flag" style="display:none;"></i>
<a class="f-fr u-btn u-btn-1 j-flag" href="javascript:void(0)">发送</a><span class="zs s-fc4 j-flag">110/120</span>
</div>
</div>
</textarea>
<textarea name="jst" id="m-wgt-input-5" style="display:none;"> <div class="m-cmmtipt f-cb f-pr">
<div class="u-txtwrap holder-parent f-pr">
<textarea class="u-txt area j-flag" placeholder="${placeholder}"><&#47;textarea>
</div>
<div class="btns f-cb f-pr">
<i class="icn u-icn u-icn-36 j-flag"></i><i class="icn u-icn u-icn-41 j-flag"></i>
<a class="btn u-btn u-btn-1 j-flag" href="javascript:void(0)">评论</a>
<span class="zs s-fc4 j-flag">110/120</span>
</div>
</div>
</textarea>
<textarea name="jst" id="m-wgt-input-6" style="display:none;"> <div class="m-cmmtipt f-cb f-pr">
<div class="u-txtwrap holder-parent f-pr">
<textarea class="u-txt area j-flag" placeholder="${placeholder}"><&#47;textarea>
</div>
<div class="btns f-cb f-pr">
<i class="icn u-icn u-icn-36 j-flag"></i><i class="icn u-icn u-icn-41 j-flag"></i>
<a class="btn u-btn u-btn-1 j-flag" href="javascript:void(0)">发送</a>
<span class="zs s-fc4 j-flag">110/120</span>
</div>
</div>
</textarea>
<textarea name="ntp" id="m-wgt-subscribe" style="display:none;"><div class="lyct lyct-1 m-favgd f-cb">
<div class="tit j-flag"><i class="u-icn u-icn-33"></i>新歌单</div>
<div class="j-flag">
<div class="u-load s-fc4"><i class="icn"></i> 加载中...</div>
</div>
</div>
</textarea>
<textarea name="jst" id="m-wgt-subscribe-item" style="display:none;"><ul>
{list beg..end as y}
{var x=xlist[y]}
<li data-id="${x.id}" class="xtag {if x.trackCount+size>10000}dis{/if}">
<div class="item f-cb">
<div class="left">
<a href="javascript:void(0)" class="avatar" target="_blank">
<img alt="" src="${x.coverImgUrl}?param=40y40">
{if x.highQuality}<i class="u-jp u-icn2 u-icn2-jp5"></i>{/if}
</a>
</div>
<p class="name f-thide"><a class="s-fc0" href="javascript:void(0)" target="_blank">${escape(cutStr(x.name,40))}</a></p>
<p class="s-fc3">${x.trackCount}首</p>
{if x.trackCount+size>10000}<p class="limit">歌单已满</p>{/if}
</div>
</li>
{/list}
</ul>
</textarea>
<textarea name="ntp" id="m-wgt-forward" style="display:none;"><div class="lyct lyct-1 f-cb">
<div class="m-lyshare">
<div class="u-txtwrap f-pr">
<label style="display: block;" class="j-flag">说点什么</label>
<textarea class="u-txt area j-flag" text = ><&#47;textarea>
</div>
<div class="oper f-cb j-flag">
<div class="face f-fl f-pr">
<i class="u-icn u-icn-36 f-fl j-flag"></i>
<i class="u-icn u-icn-41 j-flag"></i>
</div>
<span class="zs f-fr s-fc3 j-flag">140</span>
</div>
<div class="btnwrap">
<a class="u-btn2 u-btn2-2 u-btn2-w2 j-flag" hidefocus="true" href="#"><i>转发</i></a>
<a class="u-btn2 u-btn2-1 u-btn2-w2 j-flag" hidefocus="true" href="#"><i>取消</i></a>
</div>
<div class="j-flag u-err"><i class="u-icn u-icn-25"></i><span></span></div>
</div>
</div>
</textarea>
<textarea name="ntp" id="m-import-ok" style="display:none;"><div class="lyct f-cb f-tc">
<p class="f-fs3 f-ff2"><i class="u-icn u-icn-76"></i>&nbsp;&nbsp;歌曲同步完成</p>
<div class="lybtn">
<a href="javascript:;" class="u-btn2 u-btn2-2 j-flag" hidefocus="true"><i>查看我的音乐</i></a>
</div>
</div>
</textarea>
<textarea name="jst" id="m-wgt-atlist" style="display:none;"> <div class="u-atlist">
{if suggests.length == 0}
<p>轻敲空格完成输入</p>
{else}
<p>选择最近@的人或直接输入</p>
{/if}
<div class="lst">
{list suggests as suggest}
<a href="javascript:;" data-index=${suggest_index} class="f-thide j-sgt">${suggest.nickname}</a>
{/list}
</div>
</div>
</textarea>
<textarea name="jst" id="m-wgt-receiverInput" style="display:none;"> <div class="ct f-pr">
<div class="u-txtwrap f-pr">
<div class="u-txt txtwrap j-flag">
{if receiver}
<div class="blk s-fc3 j-receiver">${receiver.nickname}<a href="#" class="cls" title="删除">&times;</a></div>
{/if}
<span class="holder-parent j-flag" style="float:left">
<input type="text" class="txt j-flag" />
<label class="holder j-flag">选择或输入好友昵称</label>
</span>
</div>
</div>
<ul class="full j-flag" style="_height:182px;display:none">
{list users as user}
<li class="j-item" data-userId=${user.userId} data-username=${user.nickname} data-index=${user_index}><a href="#"><img src=${user.avatarUrl}>${user.nickname}</a></li>
{/list}
</ul>
<div class="j-flag" style="position:absolute;left: -1000px;width:auto;"></div>
</div>
</textarea>
<textarea name="jst" id="m-wgt-receiverList" style="display:none;"> {list users as user}
<li class="j-item" data-userId=${user.userId} data-username=${user.nickname} data-index=${user_index}><a href="#"><img src=${user.avatarUrl}>${user.nickname}</a></li>
{/list}
</textarea>
<textarea name="ntp" id="m-wgt-sharewin" style="display:none;"><div class="lyct lyct-1 f-cb">
<div class="m-lyshare">
<ul class="m-tabs1 j-flag">
<li><a href="#"><em>分享给大家</em></a></li>
<li><a href="#"><em>私信分享</em></a></li>
</ul>
<div class="u-err j-flag" style="display:none">最多选择10位好友</div>
<div class="j-flag"></div>
<div class="j-slogan"></div>
<div class="u-txtwrap f-pr">
<textarea class="u-txt area j-flag" placeholder="说点什么吧" data-action="txt"><&#47;textarea>
<div class="info f-pr j-flag" data-action="search"></div>
</div>
<div class="oper f-cb">
<div class="face f-fl f-pr">
<i class="u-icn u-icn-36 f-fl j-flag" data-action="emot"></i>
<i class="u-icn u-icn-41 f-fl j-flag" data-action="at"></i>
<i class="u-icn u-icn-97 j-flag f-pr" data-action="upload" data-default></i>
</div>
<span class="f-fr s-fc4 j-flag">140/140</span>
</div>
<div class="f-cb j-flag"></div>
<div class="f-cb">
<div class="btnwrap f-fl">
<a class="u-btn2 u-btn2-2 u-btn2-w2 j-flag" hidefocus="true" href="javascript:;" data-action="share"><i>分享</i></a>
<a class="u-btn2 u-btn2-1 u-btn2-w2 j-flag" hidefocus="true" href="javascript:;" data-action="close"><i>取消</i></a>
</div>
<div class="f-cb j-flag f-fr">
<div class="share f-fr">
<span class="f-fl s-fc3">同时分享到：</span>
<ul class="u-logo u-logo-s f-cb">
<li><a class="u-slg u-slg-sn j-t" data-action="sns" data-type="2" hidefocus="true" href="//music.163.com/api/sns/authorize?snsType=2&clientType=web2&callbackType=Binding&forcelogin=true" title="新浪微博"></a></li>
<li><a class="u-slg u-slg-db j-t" data-action="sns" data-type="3" hidefocus="true" href="//music.163.com/api/sns/authorize?snsType=3&clientType=web2&callbackType=Binding&forcelogin=true" title="豆瓣网"></a></li>
</ul>
</div>
</div>
</div>
<div class="u-err j-flag"><i class="u-icn u-icn-25"></i><span></span></div>
</div>
</div>
</textarea>
<textarea name="jst" id="m-search-suggest" style="display:none;">{macro listArtists(artists)}
{list artists as art}
${art.name|mark}&nbsp;
{/list}
{/macro}
<div class="m-schlist">
<p class="note s-fc3"><a class="s-fc3 xtag" href="/search/#/?s=${keyword}&type=1002">搜“${keyword|cutStr}” 相关用户</a> &gt;</p>
<div class="rap">
{list result.order as index}
{var lst=result[index]}
{if !!lst&&!!lst.length}
<div class="itm f-cb">
{if index=="songs"}
<h3 class="hd"><i class="icn u-icn u-icn-26"></i><em class="f-fl">单曲</em></h3>
<ul class="{if index_index%2!=0}odd{/if} f-cb">
{list lst as song}
<li>
<a data-res-action="bilog" data-log-action="click" data-log-json=\'{"page":"page_search_suggest","resource_type":"song","resource_id":${song.id},"keyword":${keyword|cutStr}}\' class="s-fc0 f-thide xtag" href="/song?id=${song.id}">${song.name|mark}-${listArtists(song.artists)}</a>
</li>
{/list}
</ul>
{elseif index=="artists"}
<h3 class="hd"><i class="icn u-icn u-icn-27"></i><em class="f-fl">歌手</em></h3>
<ul class="{if index_index%2!=0}odd{/if} f-cb">
{list lst as artist}
<li>
<a data-res-action="bilog" data-log-action="click" data-log-json=\'{"page":"page_search_suggest","resource_type":"artist","resource_id":${artist.id},"keyword":${keyword|cutStr}}\' class="s-fc0 f-thide xtag" href="/artist?id=${artist.id}">${artist.name|mark}</a>
</li>
{/list}
</ul>
{elseif index=="albums"}
<h3 class="hd"><i class="icn u-icn u-icn-28"></i><em class="f-fl">专辑</em></h3>
<ul class="{if index_index%2!=0}odd{/if} f-cb">
{list lst as album}
<li>
<a data-res-action="bilog" data-log-action="click" data-log-json=\'{"page":"page_search_suggest","resource_type":"album","resource_id":${album.id},"keyword":${keyword|cutStr}}\' class="s-fc0 f-thide xtag" href="/album?id=${album.id}">${album.name|mark}{if album.artist}-${album.artist.name|mark}{/if}</a>
</li>
{/list}
</ul>
{elseif index=="playlists"}
<h3 class="hd"><i class="icn u-icn u-icn-29"></i><em class="f-fl">歌单</em></h3>
<ul class="{if index_index%2!=0}odd{/if} f-cb">
{list lst as playlist}
<li>
<a data-res-action="bilog" data-log-action="click" data-log-json=\'{"page":"page_search_suggest","resource_type":"list","resource_id":${playlist.id},"keyword":${keyword|cutStr}}\' class="s-fc0 f-thide xtag" href="/playlist?id=${playlist.id}">${playlist.name|mark}</a>
</li>
{/list}
</ul>
{elseif index=="mvs"}
<h3 class="hd"><i class="icn u-icn u-icn-96"></i><em class="f-fl">视频</em></h3>
<ul class="{if index_index%2!=0}odd{/if} f-cb">
{list lst as mv}
<li>
<a data-res-action="bilog" data-log-action="click" data-log-json=\'{"page":"page_search_suggest","resource_type":"mlog","resource_id":${mv.id},"keyword":${keyword|cutStr}}\' class="s-fc0 f-thide xtag" href="/mv?id=${mv.id}">MV:${mv.name|mark}{if mv.artistName}-${mv.artistName|mark}{/if}</a>
</li>
{/list}
</ul>
{/if}
</div>
{/if}
{/list}
</div>
</div>
</textarea>
<textarea name="jst" id="m-search-suggest-anonymous" style="display:none;">{macro listArtists(artists)}
{list artists as art}
${art.name|mark}&nbsp;
{/list}
{/macro}
<div class="m-schlist">
<p class="note s-fc3"><a class="s-fc3 xtag" href="/search/#/?s=${keyword}&type=1002">搜“${keyword|cutStr}” 相关用户</a> &gt;</p>
<div class="rap">
{list result.order as index}
{var lst=result[index]}
{if !!lst&&!!lst.length}
<div class="itm f-cb">
{if index=="songs"}
<h3 class="hd"><i class="icn u-icn u-icn-26"></i><em class="f-fl">单曲</em></h3>
<ul class="{if index_index%2!=0}odd{/if} f-cb">
{list lst as song}
<li>
<a class="s-fc0 f-thide xtag" href="/login" data-res-action="bilog" data-log-action="click" data-log-json=\'{"page":"page_search_suggest","resource_type":"song","resource_id":${song.id},"keyword":${keyword|cutStr}}\'>${song.name|mark}-${listArtists(song.artists)}</a>
</li>
{/list}
</ul>
{elseif index=="artists"}
<h3 class="hd"><i class="icn u-icn u-icn-27"></i><em class="f-fl">歌手</em></h3>
<ul class="{if index_index%2!=0}odd{/if} f-cb">
{list lst as artist}
<li>
<a class="s-fc0 f-thide xtag" href="/login" data-res-action="bilog" data-log-action="click" data-log-json=\'{"page":"page_search_suggest","resource_type":"artist","resource_id":${artist.id},"keyword":${keyword|cutStr}}\'>${artist.name|mark}</a>
</li>
{/list}
</ul>
{elseif index=="albums"}
<h3 class="hd"><i class="icn u-icn u-icn-28"></i><em class="f-fl">专辑</em></h3>
<ul class="{if index_index%2!=0}odd{/if} f-cb">
{list lst as album}
<li>
<a class="s-fc0 f-thide xtag" href="/login" data-res-action="bilog" data-log-action="click" data-log-json=\'{"page":"page_search_suggest","resource_type":"album","resource_id":${album.id},"keyword":${keyword|cutStr}}\'>${album.name|mark}{if album.artist}-${album.artist.name|mark}{/if}</a>
</li>
{/list}
</ul>
{elseif index=="playlists"}
<h3 class="hd"><i class="icn u-icn u-icn-29"></i><em class="f-fl">歌单</em></h3>
<ul class="{if index_index%2!=0}odd{/if} f-cb">
{list lst as playlist}
<li>
<a class="s-fc0 f-thide xtag" href="/login" data-res-action="bilog" data-log-action="click" data-log-json=\'{"page":"page_search_suggest","resource_type":"list","resource_id":${playlist.id},"keyword":${keyword|cutStr}}\'>${playlist.name|mark}</a>
</li>
{/list}
</ul>
{elseif index=="mvs"}
<h3 class="hd"><i class="icn u-icn u-icn-96"></i><em class="f-fl">视频</em></h3>
<ul class="{if index_index%2!=0}odd{/if} f-cb">
{list lst as mv}
<li>
<a class="s-fc0 f-thide xtag" href="/login" data-res-action="bilog" data-log-action="click" data-log-json=\'{"page":"page_search_suggest","resource_type":"mlog","resource_id":${mv.id},"keyword":${keyword|cutStr}}\'>MV:${mv.name|mark}{if mv.artistName}-${mv.artistName|mark}{/if}</a>
</li>
{/list}
</ul>
{/if}
</div>
{/if}
{/list}
</div>
</div>
</textarea>
<textarea name="jst" id="m-xwgt-share-infobar" style="display:none;"><i class="highlight"></i><div class="text f-fl f-fs1"><p class="f-thide">${info|escape}</p></div>
{if canChange}<i class="f-fr icn u-icn2 u-icn2-arr"></i>{/if}
</textarea>
<textarea name="jst" id="m-xwgt-share-videobar" style="display:none;"><div class="text">
<div class="cvr f-fl f-pr" style="background-image:url(${picUrl}?imageView&thumbnail=107x60),url(${picUrl}?imageView&thumbnail=107y60&blur=30x15)">
</div>
<h3 class="f-thide f-fs1">${title}</h3>
<i class="f-fr icn u-icn2 u-icn2-arr"></i>
</div>
</textarea>
<textarea name="ntp" id="m-xwgt-share-upload" style="display:none;"> <div class="f-pr choose f-cb">
<ul class="pics f-pr f-cb j-flag"><li class="f-pr add j-flag u-icn2 u-icn2-addimg" title="添加新图片"></li></ul>
<div class="f-pa tip s-fc6 j-flag"></div>
</div>
</textarea>
<textarea name="jst" id="m-xwgt-share-upload-preview" style="display:none;"> <li class="pic f-pr{if fail} z-fail{/if}">
{if !fail}
<i class="f-img icn"></i>
{else}
<div class="mask f-blk f-pa"></div><div class="f-blk f-pa error">${fail}</div>
{/if}
<span class="del f-pa u-icn2 u-icn2-delimg" title="删除"></span>
</li>
</textarea>
<textarea name="jst" id="m-xwgt-share-upload-preview-img" style="display:none;">{if !fail}
<img class="f-img" src="${url}?imageView&thumbnail=80y80" draggable=false>
{else}
<div class="mask f-blk f-pa"></div><div class="f-blk f-pa error">${fail}</div>
{/if}
</textarea>
<textarea name="ntp" id="ntp-alert" style="display:none;"><div class="lyct f-cb f-tc">
<p class="f-fs1">
<i class="u-icn u-icn-89 j-flag"></i>
<span class="f-fw1">&nbsp;&nbsp;&nbsp;<span class="j-flag"></span></span>
</p>
<p class="mesg j-flag">&nbsp;</p>
<div class="lybtn">
<a href="javascript:;" class="u-btn2 u-btn2-2 u-btn2-w2 j-flag" hidefocus="true"><i>知道了</i></a>
</div>
</div>
</textarea>
<textarea name="ntp" id="m-layer-commwin" style="display:none;"><div class="lyct f-tc">
<p class="j-t"><i class="u-icn u-icn-90"></i></p>
<p class="j-t msg1"></p>
</div>
<div class="j-t lsbtn f-tc">
<a href="javascript:;" class="u-btn2 u-btn2-2 u-btn2-w2" hidefocus="true"><i>上传节目</i></a>
</div>
</textarea>
<textarea name="ntp" id="m-layer-delwin" style="display:none;"><div class="lyct lyct-1 f-cb">
<div class="n-log2 n-log2-4">
<p class="js-tip u-tip-2"></p>
<div class="lybtn f-tc">
<a href="javascript:;" class="u-btn2 u-btn2-2" hidefocus="true" data-action="ok"><i>删除</i></a>
<a href="javascript:;" class="u-btn2 u-btn2-1" hidefocus="true" data-action="cancel"><i>取消</i></a>
</div>
</div>
</div>
</textarea>
<textarea name="ntp" id="m-layer-termconfirm" style="display:none;"><div class="m-layer-termconfirm">
<div class="termconfirm-modal">
<div class="termconfirm-head">
服务条款和隐私政策更新
</div>
<div class="termconfirm-body">
服务条款
</div>
<div class="termconfirm-foot">
<a href="javascript:;" class="btn btn-confirm" hidefocus="true" data-action="ok"><i>同意</i></a>
</div>
</div>
</div>
</textarea>
<textarea name="jst" id="m-layer-commwin-btn" style="display:none;">{list buttons as item}
<a hidefocus="true" class="u-btn2 ${item.klass} {if item.style}${item.style}{else}u-btn2-w2{/if}" href="#" {if !!item.action}data-action="${item.action}"{/if}><i>${item.text}</i></a>
{/list}
</textarea>
<textarea name="ntp" id="m-layer-outershare" style="display:none;"><div class="lyct lyct-1">
<ul class="n-outshr f-cb">
<li>
<a href="#" data-action="wxfrd" class="logo wxfrd"></a>
<a href="#" data-action="wxfrd" class="wd">微信</a>
</li>
<!--
<li>
<a href="#" data-action="wxevt" class="logo wxevt"></a>
<a href="#" data-action="wxevt" class="wd">微信朋友圈</a>
</li>
-->
<li>
<a href="#" data-action="yxfrd" class="logo yxfrd"></a>
<a href="#" data-action="yxfrd" class="wd">易信</a>
</li>
<!--
<li>
<a href="#" data-action="yxevt" class="logo yxevt"></a>
<a href="#" data-action="yxevt" class="wd">易信朋友圈</a>
</li>
-->
<li>
<a href="#" data-action="qzone" class="logo qzone"></a>
<a href="#" data-action="qzone" class="wd">QQ空间</a>
</li>
<li>
<a href="#" data-action="lofte" class="logo lofte"></a>
<a href="#" data-action="lofte" class="wd">LOFTER</a>
</li>
</ul>
</div>
</textarea>
<textarea name="ntp" id="m-layer-tip" style="display:none;"><div class="lyct f-cb f-tc">
<div class="f-fs1 j-flag">message</div>
<div class="lybtn">
<a hidefocus="true" class="u-btn2 u-btn2-2 u-btn2-w2 j-flag" href="javascript:;"><i>知道了</i></a>
</div>
</div>
</textarea>
<textarea name="ntp" id="m-outshare-layer" style="display:none;"><div class="lyct lyct-1 f-cb">
<ul class="m-shareto f-cb j-flag">
<li class="fst" data-action="sn" data-type="2">
<a href="#" class="logo logo-sn"></a>
<a href="#" class="wd s-fc3">新浪微博</a>
</li>
<li data-action="tx" data-type="6" style="display:none;">
<a href="#" class="logo logo-tc"></a>
<a href="#" class="wd s-fc3">腾讯微博</a>
</li>
<li data-action="db" data-type="3">
<a href="#" class="logo logo-db"></a>
<a href="#" class="wd s-fc3">豆瓣</a>
</li>
</ul>
</div>
</div>
</textarea>
<textarea name="ntp" id="m-sharesingle-layer" style="display:none;"><div class="lyct lyct-1 f-cb">
<div class="m-lyshare">
<div class="u-txtwrap f-pr">
<textarea data-action="txt" class="u-txt area j-flag"><&#47;textarea>
</div>
<div class="oper f-cb">
<div class="face f-fl f-pr j-flag">
<i data-action="emt" class="u-icn u-icn-36 f-fl"></i>
</div>
<span class="zs f-fr s-fc3 j-flag">140</span>
</div>
<div class="btnwrap">
<a data-action="ok" class="u-btn2 u-btn2-2 u-btn2-w2 j-flag" hidefocus="true" href="#"><i>分享</i></a>
<a data-action="cc" class="u-btn2 u-btn2-1 u-btn2-w2" hidefocus="true" href="#"><i>取消</i></a>
</div>
<div class="u-err f-hide j-flag"><i class="u-icn u-icn-25"></i></div>
</div>
</div>
</textarea>
<textarea name="jst" id="m-popup-info" style="display:none;"><div class="lyct f-tc">
<div class="f-cb m-tipinfo">
<i class="u-icn2 u-icn2-11 f-fl"></i>
<div class="f-fr f-pr f-fs1 tip">${tip}</div>
</div>
</div>
<div class="lsbtn f-tc">
<a data-action="ok" href="javascript:void(0)" class="u-btn2 u-btn2-2 u-btn2-2-h {if oktext.length<=2}u-btn2-w2{/if}" hidefocus="true"><i>${oktext}</i></a>
<a data-action="cc" href="javascript:void(0)" hidefocus="true" class="u-btn2 u-btn2-1 u-btn2-1-h {if cctext.length<=2}u-btn2-w2{/if}"><i>${cctext}</i></a>
</div>
</textarea>
<textarea name="jst" id="m-popup-song-buy" style="display:none;"><div class="lyct m-songpay f-tc">
<div class="f-cb m-tipinfo">
<i class="u-icn2 u-icn2-11 f-fl"></i>
<div class="f-fr f-pr f-fs1 tip">${tip}</div>
</div>
<div class="f-pr f-tc">
<a data-action="ok" href="javascript:void(0)" class="u-btn2 u-btn2-2 {if oktext.length<=2}u-btn2-w2{/if}" hidefocus="true"><i>${oktext}</i></a>
{if showSongText}<a data-action="song" class="song s-fc4" href="javascript:void(0)">${songTxt}</a>{/if}
</div>
</div>
</textarea>
<textarea name="jst" id="m-popup-alert" style="display:none;"><div class="lyct f-tc">
<p><i class="${icon}"></i></p>
<p class="msg1"><span class="f-fs1 s-fc1">${tip}</span></p>
</div>
<div class="lsbtn f-tc">
{if typeof(oktext) != \'undefined\'}<a data-action="ok" href="javascript:void(0)" class="u-btn2 u-btn2-2 u-btn2-2-h {if oktext.length<=2}u-btn2-w2{/if}" hidefocus="true"><i>${oktext}</i></a>{/if}
{if typeof(cctext) != \'undefined\'}<a data-action="cc" href="javascript:void(0)" class="u-btn2 u-btn2-1 u-btn2-1-h {if cctext.length<=2}u-btn2-w2{/if}" hidefocus="true"><i>${cctext}</i></a>{/if}
</div>
</textarea>
<textarea name="jst" id="m-readAll-popup-alert" style="display:none;"><div class="lyct f-tc">
<p><i class="${icon}"></i></p>
<p class="msg1"><span class="f-fs1 s-fc1">${tip}</span></p>
</div>
<div class="lsbtn f-tc">
{if typeof(oktext) != \'undefined\'}<a data-action="ok" href="javascript:void(0)" class="u-btn2 u-btn2-2 u-btn2-2-h {if oktext.length<=2}u-btn2-w2{/if}" hidefocus="true"><i>${oktext}</i></a>{/if}
{if typeof(cctext) != \'undefined\'}<a data-action="cc" href="javascript:void(0)" class="u-btn2 u-btn2-1 u-btn2-1-h {if cctext.length<=2}u-btn2-w2{/if}" hidefocus="true"><i>${cctext}</i></a>{/if}
</div>
</textarea>
<textarea name="txt" id="m-donate-tip" style="display:none;"><p>该资源为公益歌曲<p>
<p>捐赠任意金额（2~4999元）即可无限畅听下载</p>
</textarea>
<textarea name="ntp" id="m-simple-share-layer" style="display:none;"> <div class="lyct lyct-1">
<ul class="n-outshr f-cb">
<li data-type="xlwb">
<a href="javascript:;" class="logo xlwb"></a>
<a href="javascript:;" class="wd">新浪微博</a>
</li>
<li data-type="wx">
<a href="javascript:;" class="logo wxfrd"></a>
<a href="javascript:;" class="wd">微信</a>
</li>
<li data-type="yx">
<a href="javascript:;" class="logo yxfrd"></a>
<a href="javascript:;" class="wd">易信好友</a>
</li>
<li data-type="qzone">
<a href="javascript:;" class="logo qzone"></a>
<a href="javascript:;" class="wd">QQ空间</a>
</li>
<li data-type="lofter" style="display:none;">
<a href="javascript:;" class="logo lofte"></a>
<a href="javascript:;" class="wd">LOFTER</a>
</li>
<li data-type="db" style="display:none;">
<a href="javascript:;" class="logo db"></a>
<a href="javascript:;" class="wd">豆瓣</a>
</li>
</ul>
</div>
</textarea>
<textarea name="txt" id="m-report-point" style="display:none;"><div class="zcnt">
<div class="lyct f-cb f-tc">
<p class="f-fs2">悬赏1积分让大家来帮你补歌词，是否继续？</p>
<p style="padding-top: 10px;">若30天内歌词未补充，积分将退还给您</p>
<div class="lybtn">
<a href="javascript:;" data-action="ok" class="u-btn2 u-btn2-2 u-btn2-w2" hidefocus="true"><i>继续求</i></a>
<a href="javascript:;" data-action="cc" class="u-btn2 u-btn2-1 u-btn2-w2" hidefocus="true"><i>取消</i></a>
</div>
</div>
</div>
</textarea>
<textarea name="txt" id="txt-mobilestatus" style="display:none;"><div class="box f-cb">
<div data-action="invalid" class="item z-first f-fl">
<div class="icon"></div>
<p>原手机号已停用</p>
<p class="s-fc3">(使用其他方式验证)</p>
</div>
<div data-action="valid" class="item f-fr">
<div class="icon"></div>
<p>原手机号仍能使用</p>
<p class="s-fc3">(使用手机验证码验证)</p>
</div>
</div>
</textarea>
<textarea name="jst" id="jst-mobilethirdaccount" style="display:none;"><div class="box f-cb f-tc">
{if hasWx}
<div data-action="10" data-type="10" class="item f-ib z-first">
<div class="icon"></div>
<p>点击使用微信验证</p>
</div>
{/if}
{if hasQQ}
<div data-action="5" data-type="5" class="item f-ib">
<div class="icon"></div>
<p>点击使用QQ验证</p>
</div>
{/if}
</div>
</textarea>
<textarea name="ntp" id="m-question" style="display:none;"><div class="m-question">
<div>请填写以下安全问题的答案</div>
<div class="qa j-flag f-cb">
<label class="f-fl">问题：</label>
</div>
<div class="qa f-cb">
<label class="f-fl">回答：</label>
<input type="text" class="u-txt txt f-fl j-flag">
</div>
<div class="u-err f-hide j-flag"><i class="u-icn u-icn-25"></i>账号或密码错误</div>
<div class="btnwrap">
<a data-action="back" class="u-btn2 u-btn2-1 u-btn2-w2" hidefocus="true" href="javascript:void(0)"><i>上一步</i></a>
<a data-action="next" class="u-btn2 u-btn2-2 u-btn2-w2" hidefocus="true" href="javascript:void(0)"><i>下一步</i></a>
</div>
</div>
</textarea>
<textarea name="ntp" id="g-select" style="display:none;"><div class="u-slt f-ib">
<span class="curr f-thide">－请选择－</span>
<i class="btn"></i>
<ul class="f-hide">
</ul>
</div>
</textarea>
<textarea name="ntp" id="ntp-linuxlinks" style="display:none;"><div class="lyct lyct-1">
<div class="dc f-cb">
<ul class="links">
<li class="link f-cb">
<a href="" class="right" target="_blank" hidefocus="true" title="Linux版下载">deepin15（64位）</a>
<a href="" class="right" target="_blank" hidefocus="true" title="Linux版下载">ubuntu18.04（64位）</a>
</li>
</ul>
</div>
</div>
</textarea>
<textarea name="ntp" id="ntp-pcRedirect" style="display:none;"><div class="lyct lyct-1">
<div class="pcdld f-cb">
<img src="../../../style/web2/img/down/uwpWindown.png" alt="网易云音乐-UWP版">
<p class="txt">您的系统为Windows 10，推荐下载UWP版</p>
<div class="choose">
<a class="u-btn2 u-btn2-2" data-res-action="bilog" data-log-action="downloadapp" data-log-json=\'{"type":"pc","source":"downloadapp"}\' href="https://www.microsoft.com/store/apps/9nblggh6g0jf" onclick="g_stat(\'uwp\',true,event);_gaq.push([\'_trackEvent\',\'download\',\'uwp\',\'download\']);" hidefocus="true" title="UWP版下载" target="_blank"><i>下载UWP版本</i></a>
<a class="link" data-res-action="bilog" data-log-action="downloadapp" data-log-json=\'{"type":"pc","source":"downloadapp"}\' href="https://music.163.com/download/pc/latest" onclick="g_stat(\'pc\',true,event);_gaq.push([\'_trackEvent\',\'download\',\'pc\',\'download\']);" hidefocus="true" title="PC版下载" target="_blank"><i>继续下载PC版本</i></a>
</div>
</div>
</div>
</textarea>
<textarea name="jst" id="g-select-item" style="display:none;">{list options as o}
<li class="f-thide" data-index="${o_index}"><a href="javascript:;">${o|filter}</a></li>
{/list}
</textarea>
<textarea name="ntp" id="m-download-layer" style="display:none;"><h3 class="f-tc">使用云音乐客户端</h3>
<h4 class="f-tc s-fc3">即可批量下载高音质音乐</h4>
<div class="f-cb">
<div class="f-hide wrap j-flag">
<div class="left">
<div data-action="download" data-src="https://music.163.com/api/osx/download/latest" class="btn btn-mac"><i></i>Mac版<span class="ver j-flag">V1.9.1</span></div>
</div>
<div class="right">
<div class="qtcode"></div>
<div class="s-fc3 f-tc">扫描下载手机版</div>
</div>
</div>
<div class="f-hide wrap2 j-flag">
<div>
<img src="../../../style/web2/img/down/down_pc_bgi.png" class="center_img" ></img>
</div>
<div class="btn btn-open-client j-flag" data-action="orpheus">打开客户端下载</div>
<div>
<a class="btn_download" href="javascript:;" data-action="download" data-src="https://music.163.com/download/pc/latest">还没有客户端？去下载</a>
</div>
</div>
</div>
</textarea>
<textarea name="ntp" id="m-audioQuality-layer" style="display:none;"><h3 class="f-tc">使用云音乐客户端</h3>
<h4 class="f-tc s-fc3">尽享多种高品质音效</h4>
<div class="f-cb">
<div class="wrap2">
<div>
<img src="../../../style/web2/img/down/audio_pc_bgi.png" class="center_img" ></img>
</div>
<div class="btn btn-open-client" data-action="orpheus">打开客户端播放</div>
<div>
<a class="btn_download" href="javascript:;" data-action="download" data-src="https://music.163.com/download/pc/latest">还没有客户端？去下载</a>
</div>
</div>
</div>
</textarea>
<textarea name="ntp" id="m-programtips-layer" style="display:none;"><div class="f-tc wrap ">
<p class="f-fs1 s-fc1 wrap-p">该资源为付费内容，扫描下方二维码，使用最新的安卓或iPhone版本购买后即可畅享</p>
<div class="f-tc wrap-d">
<span class="qtcode j-flag"></span>
</div>
</div>
</textarea>
<textarea name="jst" id="com-artists-title" style="display:none;">{var title=""}
{if artists && artists.length}
{list artists as x}
{if x}
{var title = title + x.name}
{if x_index < x_length - 1}
{var title = title + " / "}
{/if}
{/if}
{/list}
{/if}
${escape(title)}
</textarea>
<textarea name="jst" id="com-mv-artists" style="display:none;">{if artists && artists.length}
<span class="${boxClazz}" title="${comJST(\'com-artists-title\', artists)}">
{list artists as x}
{if !!x}
{if !!x.id}
<a href="/artist?id=${x.id}"
class="${clazz}"
data-res-action="bilog"
data-log-action="click"
data-log-json=\'{"resource_type":"artist", "resource_id":${x.id}}\'>${mark(escape(x.name))}</a>
{else}
<span class="${clazz}">${mark(escape(x.name))}</span>
{/if}
{if x_index < x_length - 1}&nbsp;/&nbsp;{/if}
{/if}
{/list}
</span>
{/if}
</textarea>
<textarea name="jst" id="com-album-artists" style="display:none;">${comJST(\'com-mv-artists\', artists, clazz, mark, boxClazz)}
</textarea>
<textarea name="jst" id="com-user-type" style="display:none;">{if x.userType==4}${before}<sup class="${clazz2} u-icn2 u-icn2-music2 ${clazz}"></sup>${after}{elseif x.authStatus==1}${before}<sup class="u-icn u-icn-1 ${clazz}"></sup>${after}{elseif (x.expertTags && x.expertTags.length>0) || !isEmptyObject(x.experts)}${before}<sup class="u-icn u-icn-84 ${clazz}"></sup>${after}{/if}
</textarea>
<textarea name="jst" id="com-user-type-icon" style="display:none;">{if loginUserProfile.avatarDetail&&loginUserProfile.avatarDetail.identityIconUrl}
<img style="width: 20px; height: 20px;" class="person-icn" src="${loginUserProfile.avatarDetail.identityIconUrl}" />
{/if}
</textarea>
<textarea name="jst" id="com-user-type-icon-sml" style="display:none;">{if x.avatarDetail && x.avatarDetail.identityIconUrl}
<span class="u-icn-new-wrap">
<img class="u-icn-new" src="${x.avatarDetail.identityIconUrl}"></img>
</span>
{/if}
</textarea>
<textarea name="ntp" id="ntp-portrait" style="display:none;"><div class="m-emts z-show">
<div class="j-flag emtwrap f-cb"></div>
<div class="page">
<a href="#" hidefocus="true" class="j-flag u-btn u-btn-prv"></a><em class="j-flag s-fc3">1/2</em><a href="#" hidefocus="true" class="j-flag u-btn u-btn-nxt"></a>
</div>
</div>
</textarea>
<textarea name="jst" id="jst-portrait" style="display:none;">{list plist as item}
<span title="${item.key}" class="emtitm"><img data-text="${item.key}" data-url="${item.key|purl}" class="f-alpha" src="${item.key|purl}"></span>
{/list}
</textarea>
<textarea name="ntp" id="m-wgt-song-box" style="display:none;"><div class="j-flag"></div>
<div class="j-flag"></div>
</textarea>
<textarea name="jst" id="m-wgt-song-list" style="display:none;"><table class="m-table {if type==\'rank\'}m-table-rank{/if}">
<thead>
<tr>
<th class="first {if type==\'rank\'}wrk{else}w1{/if}"><div class="wp">&nbsp;</div></th>
<th><div class="wp af0"></div></th>
<th class="w2"><div class="wp af1"></div></th>
<th class="w3"><div class="wp af2"></div></th>
<th class="w4"><div class="wp af3"></div></th>
</tr>
</thead>
<tbody>
{list beg..end as y}
{var x=xlist[y]}
<tr id="${x.id|seed}" class="{if y%2==0}even{/if} {if disable(x)}js-dis{/if}">
<td class="left">
<div class="hd {if type==\'rank\'}rank{/if}">
<span data-res-id="${x.id}" data-res-type="18" data-res-action="play" {if from}data-res-from="${from.fid}" data-res-data="${from.fdata}"{/if} class="ply {if isPlaying(x)}ply-z-slt{/if}">&nbsp;</span>
<span class="num">${y+1}</span>
{if type==\'rank\'}
<div class="rk rk-1">
{if x.lastRank>=0}
{if y-x.lastRank>0}
<span class="ico u-icn u-icn-74 s-fc10">${y-x.lastRank}</span>
{elseif y-x.lastRank==0}
<span class="ico u-icn u-icn-72 s-fc4">0</span>
{else}
<span class="ico u-icn u-icn-73 s-fc9">${x.lastRank-y}</span>
{/if}
{else}
<span class="u-icn u-icn-75"></span>
{/if}
</div>
{/if}
</div>
</td>
<td class="">
<div class="f-cb">
<div class="tt">
<div class="ttc">
<span class="txt">
{var alia=songAlia(x)}
<a href="/song?id=${x.id}"><b title="${x.name|escape}{if alia} - (${alia|escape}){/if}">${soil(x.name)}</b></a>{if alia}<span title="${alia|escape}" class="s-fc8"> - (${soil(alia)})</span>{/if}
{if x.mvid>0}
<span data-res-id="${x.id}" data-res-action="mv" title="播放mv" class="mv">MV</span>
{/if}
</span>
</div>
</div>
</div>
</td>
<td class=" s-fc3">
<span class="u-dur {if canDel}candel{/if}">${dur2time(x.duration/1000)}{if x.ftype==2}<i title="歌曲来自第三方网站" class="migu u-icn2 u-icn2-14"></i>{/if}</span>
<div class="opt hshow">
<a class="u-icn u-icn-81 icn-add" href="javascript:;" title="添加到播放列表" hidefocus="true"
data-res-type="18"
data-res-id="${x.id}"
data-res-action="addto"
{if from}data-res-from="${from.fid}" data-res-data="${from.fdata}"{/if}></a>
<span data-res-id="${x.id}" data-res-type="18" data-res-action="fav" class="icn icn-fav" title="收藏"></span>
<span data-res-id="${x.id}" data-res-type="18" data-res-action="share" data-res-name="{if typeof removeQuotes != \'undefined\'}${removeQuotes(x.name)}{else}${x.name}{/if}" data-res-author="{list x.artists as art}${art.name}{if art_index<x.artists.length-1}/{/if}{/list}" {if x.album}data-res-pic="${x.album.picUrl}"{/if} class="icn icn-share" title="分享">分享</span>
<span data-res-id="${x.id}" data-res-type="18" data-res-action="download" class="icn icn-dl" title="下载"></span>
{if canDel}
<span data-res-id="${x.id}" data-res-type="18" data-res-action="delete" class="icn icn-del" title="删除">删除</span>
{/if}
</div>
</td>
<td class="">
<div class="text" title="{list x.artists as art}${art.name}{if art_index<x.artists.length-1}/{/if}{/list}">
${getArtistName(x.artists, \'\', \'\', false, false, true)}
</div>
</td>
{if type==\'dayRcmd\'}
<td class="hascls">
<div class="f-pr">
<div class="text">{if x.album}<a href="/album?id=${x.album.id}" title="${x.album.name}">${x.album.name}</a>{/if}</div>
<a href="javascript:;" data-res-action="dislike" data-res-id="${x.id}" data-res-alg="${x.alg}" class="cls u-icn u-icn-80 f-tid icn-dislike" title="不感兴趣">不感兴趣</a>
</div>
</td>
{else}
<td class="">
<div class="text">
{if x.album}
<a href="/album?id=${x.album.id}" title="${x.album.name|escape}">${soil(x.album.name)}</a>
{/if}
</div>
</td>
{/if}
</tr>
{/list}
</tbody>
</table>
</textarea>
<textarea name="jst" id="m-wgt-album-list" style="display:none;"><table class="m-table {if type==\'rank\'}m-table-rank{else}m-table-album{/if}">
<thead>
<tr>
<th class="first {if type==\'rank\'}wrk{else}w1{/if}"><div class="wp">&nbsp;</div></th>
<th><div class="wp">歌曲标题</div></th>
<th class="w2-1"><div class="wp">时长</div></th>
<th class="w4"><div class="wp">歌手</div></th>
</tr>
</thead>
<tbody>
{list beg..end as y}
{var x=xlist[y]}
<tr id="${x.id|seed}" class="{if y%2==0}even{/if} {if disable(x)}js-dis{/if}">
<td class="left">
<div class="hd {if type==\'rank\'}rank{/if}">
<span data-res-id="${x.id}" data-res-type="18" data-res-action="play" {if from}data-res-from="${from.fid}" data-res-data="${from.fdata}"{/if} class="ply {if isPlaying(x)}ply-z-slt{/if}">&nbsp;</span>
<span class="num">${y+1}</span>
{if type==\'rank\'}
<div class="rk rk-1">
{if x.lastRank>=0}
{if y-x.lastRank>0}
<span class="ico u-icn u-icn-74 s-fc10">${y-x.lastRank}</span>
{elseif y-x.lastRank==0}
<span class="ico u-icn u-icn-72 s-fc4">0</span>
{else}
<span class="ico u-icn u-icn-73 s-fc9">${x.lastRank-y}</span>
{/if}
{else}
<span class="u-icn u-icn-75"></span>
{/if}
</div>
{/if}
</div>
</td>
{if x.privilege.fee == 1}
<td class="">
<div class="f-cb">
<div class="tt">
<div class="ttc">
<span class="txt">
<i class="u-vip-icn"></i>
{var alia=songAlia(x)}
<a href="/song?id=${x.id}"><b title="${x.name|escape}{if alia} - (${alia|escape}){/if}">${soil(x.name)}</b></a>{if alia}<span title="${alia|escape}" class="s-fc8"> - (${soil(alia)})</span>{/if}
{if x.mvid>0}
<span data-res-id="${x.id}" data-res-action="mv" title="播放mv" class="mv">MV</span>
{/if}
</span>
</div>
</div>
</div>
</td>
{else}
<td class="">
<div class="f-cb">
<div class="tt">
<div class="ttc">
<span class="txt">
{var alia=songAlia(x)}
<a href="/song?id=${x.id}"><b title="${x.name|escape}{if alia} - (${alia|escape}){/if}">${soil(x.name)}</b></a>{if alia}<span title="${alia|escape}" class="s-fc8"> - (${soil(alia)})</span>{/if}
{if x.mvid>0}
<span data-res-id="${x.id}" data-res-action="mv" title="播放mv" class="mv">MV</span>
{/if}
</span>
</div>
</div>
</div>
</td>
{/if}
<td class=" s-fc3">
<span class="u-dur {if canDel}candel{/if}">${dur2time(x.duration/1000)}{if x.ftype==2}<i title="歌曲来自第三方网站" class="migu u-icn2 u-icn2-14"></i>{/if}</span>
<div class="opt hshow">
<a class="u-icn u-icn-81 icn-add" href="javascript:;" title="添加到播放列表" hidefocus="true"
data-res-type="18"
data-res-id="${x.id}"
data-res-action="addto"
{if from}data-res-from="${from.fid}" data-res-data="${from.fdata}"{/if}></a>
<span data-res-id="${x.id}" data-res-type="18" data-res-action="fav" class="icn icn-fav" title="收藏"></span>
<span data-res-id="${x.id}" data-res-type="18" data-res-action="share" data-res-name="{if typeof removeQuotes != \'undefined\'}${removeQuotes(x.name)}{else}${x.name}{/if}" data-res-author="{list x.artists as art}${art.name}{if art_index<x.artists.length-1}/{/if}{/list}" {if x.album}data-res-pic="${x.album.picUrl}"{/if} class="icn icn-share" title="分享">分享</span>
<span data-res-id="${x.id}" data-res-type="18" data-res-action="download" class="icn icn-dl" title="下载"></span>
{if canDel}
<span data-res-id="${x.id}" data-res-type="18" data-res-action="delete" class="icn icn-del" title="删除">删除</span>
{/if}
</div>
</td>
<td class="">
<div class="text" title="{list x.artists as art}${art.name}{if art_index<x.artists.length-1}/{/if}{/list}">
${getArtistName(x.artists, \'\', \'/\', false, true, true)}
</div>
</td>
</tr>
{/list}
</tbody>
</table>
</textarea>
<textarea name="jst" id="m-wgt-song-top50-list" style="display:none;"><table class="m-table m-table-1 m-table-4">
<tbody>
{list beg..end as y}
{var x=xlist[y]}
<tr id="${x.id|seed}" class="{if y%2==0}even{/if} {if disable(x)}js-dis{/if}">
<td class="w1">
<div class="hd">
<span data-res-id="${x.id}" data-res-type="18" data-res-action="play" {if from}data-res-from="${from.fid}" data-res-data="${from.fdata}"{/if} class="ply {if isPlaying(x)}ply-z-slt{/if}">&nbsp;</span>
<span class="num">${y+1}</span>
</div>
</td>
<td class="">
<div class="f-cb">
<div class="tt">
<div class="ttc">
<span class="txt">
{var alia=songAlia(x)}
<a href="/song?id=${x.id}"><b title="${x.name|escape}{if alia} - (${alia|escape}){/if}">${soil(x.name)}</b></a>{if alia}<span title="${alia|escape}" class="s-fc8"> - (${soil(alia)})</span>{/if}
{if x.mvid>0}
<span data-res-id="${x.id}" data-res-action="mv" title="播放mv" class="mv">MV</span>
{/if}
</span>
</div>
</div>
</div>
</td>
<td class="w2-1 s-fc3">
<span class="u-dur {if canDel}candel{/if}">${dur2time(x.duration/1000)}{if x.ftype==2}<i title="歌曲来自第三方网站" class="migu u-icn2 u-icn2-14"></i>{/if}</span>
<div class="opt hshow">
<a class="u-icn u-icn-81 icn-add" href="javascript:;" title="添加到播放列表" hidefocus="true"
data-res-type="18"
data-res-id="${x.id}"
data-res-action="addto"
{if from}data-res-from="${from.fid}" data-res-data="${from.fdata}"{/if}></a>
<span data-res-id="${x.id}" data-res-type="18" data-res-action="fav" class="icn icn-fav" title="收藏"></span>
<span data-res-id="${x.id}" data-res-type="18" data-res-action="share" data-res-name="{if typeof removeQuotes != \'undefined\'}${removeQuotes(x.name)}{else}${x.name}{/if}" data-res-author="{list x.artists as art}${art.name}{if art_index<x.artists.length-1}/{/if}{/list}" {if x.album}data-res-pic="${x.album.picUrl}"{/if} class="icn icn-share" title="分享">分享</span>
<span data-res-id="${x.id}" data-res-type="18" data-res-action="download" class="icn icn-dl" title="下载"></span>
{if canDel}
<span data-res-id="${x.id}" data-res-type="18" data-res-action="delete" class="icn icn-del" title="删除">删除</span>
{/if}
</div>
</td>
<td class="w4">
<div class="text">
{if x.album}
{var transName = x.album.tns && x.album.tns.length > 0 ? x.album.tns[0] : \'\'}
<a href="/album?id=${x.album.id}" title="${x.album.name|escape}{if transName} - (${transName|escape}){/if}">${soil(x.album.name)}</a>
{if transName}
<span title="${transName|escape}" class="s-fc8"> - (${transName|escape})</span>
{/if}
{/if}
</div>
</td>
</tr>
{/list}
</tbody>
</table>
</textarea>
<textarea name="jst" id="m-wgt-song-rank-list" style="display:none;"><table class="m-table m-table-rank">
<thead>
<tr>
<th class="first w1"></th>
<th><div class="wp">标题</div></th>
<th class="w2-1"><div class="wp">时长</div></th>
<th class="w3-1"><div class="wp">歌手</div></th>
</tr>
</thead>
<tbody>
{list beg..end as y}
{var x=xlist[y]}
<tr id="${x.id|seed}" class="{if y%2==0}even{/if} {if disable(x)}js-dis{/if}">
{if y<3}
<td>
<div class="hd">
<span class="num">${y+1}</span>
<div class="rk ">
{if x.lastRank>=0}
{if y-x.lastRank>0}
<span class="ico u-icn u-icn-74 s-fc10">${y-x.lastRank}</span>
{elseif y-x.lastRank==0}
<span class="ico u-icn u-icn-72 s-fc4">0</span>
{else}
<span class="ico u-icn u-icn-73 s-fc9">${x.lastRank-y}</span>
{/if}
{else}
<span class="u-icn u-icn-75"></span>
{/if}
</div>
</div>
</td>
<td class="rank">
<div class="f-cb">
<div class="tt">
<a href="/song?id=${x.id}">{if x.album}<img class="rpic" src="${x.album.picUrl}?param=50y50&quality=100">{/if}</a>
<span data-res-id="${x.id}" data-res-type="18" data-res-action="play" {if from}data-res-from="${from.fid}" data-res-data="${from.fdata}"{/if} class="ply {if isPlaying(x)}ply-z-slt{/if}">&nbsp;</span>
<div class="ttc">
<span class="txt">
{var alia=songAlia(x)}
<a href="/song?id=${x.id}"><b title="${x.name|escape}{if alia} - (${alia|escape}){/if}">${soil(x.name)}</b></a>{if alia}<span title="${alia|escape}" class="s-fc8"> - (${soil(alia)})</span>{/if}
{if x.mvid>0}
<span data-res-id="${x.id}" data-res-action="mv" title="播放mv" class="mv">MV</span>
{/if}
</span>
</div>
</div>
</div>
</td>
{else}
<td>
<div class="hd">
<span class="num">${y+1}</span>
<div class="rk ">
{if x.lastRank>=0}
{if y-x.lastRank>0}
<span class="ico u-icn u-icn-74 s-fc10">${y-x.lastRank}</span>
{elseif y-x.lastRank==0}
<span class="ico u-icn u-icn-72 s-fc4">0</span>
{else}
<span class="ico u-icn u-icn-73 s-fc9">${x.lastRank-y}</span>
{/if}
{else}
<span class="u-icn u-icn-75"></span>
{/if}
</div>
</div>
</td>
<td class="">
<div class="f-cb">
<div class="tt">
<span data-res-id="${x.id}" data-res-type="18" data-res-action="play" {if from}data-res-from="${from.fid}" data-res-data="${from.fdata}"{/if} class="ply {if isPlaying(x)}ply-z-slt{/if}">&nbsp;</span>
<div class="ttc">
<span class="txt">
{var alia=songAlia(x)}
<a href="/song?id=${x.id}"><b title="${x.name|escape}{if alia} - (${alia|escape}){/if}">${soil(x.name)}</b></a>{if alia}<span title="${alia|escape}" class="s-fc8"> - (${soil(alia)})</span>{/if}
{if x.mvid>0}
<span data-res-id="${x.id}" data-res-action="mv" title="播放mv" class="mv">MV</span>
{/if}
</span>
</div>
</div>
</div>
</td>
{/if}
<td class=" s-fc3">
<span class="u-dur {if canDel}candel{/if}">${dur2time(x.duration/1000)}{if x.ftype==2}<i title="歌曲来自第三方网站" class="migu u-icn2 u-icn2-14"></i>{/if}</span>
<div class="opt hshow">
<a class="u-icn u-icn-81 icn-add" href="javascript:;" title="添加到播放列表" hidefocus="true"
data-res-type="18"
data-res-id="${x.id}"
data-res-action="addto"
{if from}data-res-from="${from.fid}" data-res-data="${from.fdata}"{/if}></a>
<span data-res-id="${x.id}" data-res-type="18" data-res-action="fav" class="icn icn-fav" title="收藏"></span>
<span data-res-id="${x.id}" data-res-type="18" data-res-action="share" data-res-name="{if typeof removeQuotes != \'undefined\'}${removeQuotes(x.name)}{else}${x.name}{/if}" data-res-author="{list x.artists as art}${art.name}{if art_index<x.artists.length-1}/{/if}{/list}" {if x.album}data-res-pic="${x.album.picUrl}"{/if} class="icn icn-share" title="分享">分享</span>
<span data-res-id="${x.id}" data-res-type="18" data-res-action="download" class="icn icn-dl" title="下载"></span>
{if canDel}
<span data-res-id="${x.id}" data-res-type="18" data-res-action="delete" class="icn icn-del" title="删除">删除</span>
{/if}
</div>
</td>
<td class="">
<div class="text" title="{list x.artists as art}${art.name}{if art_index<x.artists.length-1}/{/if}{/list}">
${getArtistName(x.artists, \'\', \'\', false, false, true)}
</div>
</td>
</tr>
{/list}
</tbody>
</table>
</textarea>
<textarea name="jst" id="m-wgt-song-pgm-list" style="display:none;"><table class="m-table m-table-prog">
<tbody id="song-list">
{list beg..end as y}
{var x=xlist[y]}
<tr id="${x.id|seed}" class="{if y%2!=0}even{/if} {if disable(x)}js-dis{/if}">
<td class="first col1">
<div class="hd">
<span data-res-id="${x.id}" data-res-type="18" data-res-action="play" {if from}data-res-from="${from.fid}" data-res-data="${from.fdata}"{/if} class="ply {if isPlaying(x)}ply-z-slt{/if}">&nbsp;</span>
<span class="num">${y+1}</span>
</div>
</td>
<td class="col2">
<div class="f-cb">
<div class="tt">
<div class="ttc">
<span class="txt">
{var alia=songAlia(x)}
<a href="/song?id=${x.id}"><b title="${x.name|escape}{if alia} - (${alia|escape}){/if}">${soil(x.name)}</b></a>{if alia}<span title="${alia|escape}" class="s-fc8"> - (${soil(alia)})</span>{/if}
{if x.mvid>0}
<span data-res-id="${x.id}" data-res-action="mv" title="播放mv" class="mv">MV</span>
{/if}
</span>
</div>
</div>
</div>
</td>
<td class="col3 s-fc3">
<span class="u-dur {if canDel}candel{/if}">${dur2time(x.duration/1000)}{if x.ftype==2}<i title="歌曲来自第三方网站" class="migu u-icn2 u-icn2-14"></i>{/if}</span>
<div class="opt hshow">
<a class="u-icn u-icn-81 icn-add" href="javascript:;" title="添加到播放列表" hidefocus="true"
data-res-type="18"
data-res-id="${x.id}"
data-res-action="addto"
{if from}data-res-from="${from.fid}" data-res-data="${from.fdata}"{/if}></a>
<span data-res-id="${x.id}" data-res-type="18" data-res-action="fav" class="icn icn-fav" title="收藏"></span>
<span data-res-id="${x.id}" data-res-type="18" data-res-action="share" data-res-name="{if typeof removeQuotes != \'undefined\'}${removeQuotes(x.name)}{else}${x.name}{/if}" data-res-author="{list x.artists as art}${art.name}{if art_index<x.artists.length-1}/{/if}{/list}" {if x.album}data-res-pic="${x.album.picUrl}"{/if} class="icn icn-share" title="分享">分享</span>
<span data-res-id="${x.id}" data-res-type="18" data-res-action="download" class="icn icn-dl" title="下载"></span>
{if canDel}
<span data-res-id="${x.id}" data-res-type="18" data-res-action="delete" class="icn icn-del" title="删除">删除</span>
{/if}
</div>
</td>
<td class="col4">
<div class="text" title="{list x.artists as art}${art.name}{if art_index<x.artists.length-1}/{/if}{/list}">
${getArtistName(x.artists, \'\', \'\', false, false, true)}
</div>
</td>
<td class="col5">
<div class="text">
{if x.album}
<a href="/album?id=${x.album.id}" title="${x.album.name|escape}">${soil(x.album.name)}</a>
{/if}
</div>
</td>
</tr>
{/list}
</tbody>
</table>
</textarea>
<textarea name="jst" id="m-wgt-song-listen" style="display:none;"> <ul>
{list beg..end as y}
{var x=xlist[y]}
{if extData&&extData.limit&&y>=extData.limit}
{break}
{/if}
{var from=getFrom()}
<li id="${x.id|seed}" {if y%2 !=0 }class=\'even\'{/if}>
<div class="hd ">
<span data-res-id="${x.id}" data-res-type="18" data-res-action="play" {if from}data-res-from="${from.fid}" data-res-data="${from.fdata}"{/if} class="ply {if isPlaying(x)}ply-z-slt{/if}">&nbsp;</span>
<span class="num">${y+1}.</span>
</div>
<div class="song">
<div class="tt">
<div class="ttc">
<span class="txt"><a href="/song?id=${x.id}"><b title="${x.name}">${x.name}</b></a>
<span class=\'ar s-fc8\'> <em>-</em>
${getArtistName(x.artists, \'s-fc8\')}
</span>
</span>
</div>
</div>
<div class="opt">
<a class="u-icn u-icn-81 icn-add" href="javascript:;" title="添加到播放列表" hidefocus="true" data-res-type="18" data-res-id="${x.id}" data-res-action="addto" {if from}data-res-from="${from.fid}" data-res-data="${from.fdata}"{/if}></a>
<span data-res-id="${x.id}" data-res-type="18" data-res-action="subscribe" class="icn icn-fav" title="收藏"></span>
<span data-res-id="${x.id}" data-res-type="18" data-res-action="share" data-res-name="${x.name}" data-res-author="{list x.artists as art}${art.name}{if art_index<x.artists.length-1}/{/if}{/list}" class="icn icn-share" title="分享">分享</span>
<span data-res-id="${x.id}" data-res-type="18" data-res-action="download" class="icn icn-dl" title="下载">下载</span>
</div>
</div>
<div class="tops">
<span class="bg" style=\'width:${x.score*100/x.max}%;\'></span>
{if extData.showCount&&x.playCount}<span class="times f-ff2">${x.playCount}次</span>{/if}
</div>
</li>
{/list}
</ul>
{if extData&&extData.limit&&xlist.length>extData.limit}
<div class="more">
<a href="/user/songs/rank?id=${hostId}" >查看更多&gt;</a>
</div>
{/if}
</textarea>
<textarea name="jst" id="m-wgt-purchased-song-list" style="display:none;"> {list beg..end as y}
{var x=xlist[y]}
<tr id="${x.id|seed}" class="{if y%2==1}even{/if} {if disable(x)}js-dis{/if}">
<td class="left">
<div class="hd {if type==\'rank\'}rank{/if}">
<span data-res-id="${x.id}" data-res-type="18" data-res-action="play" {if from}data-res-from="${from.fid}" data-res-data="${from.fdata}"{/if} class="ply {if isPlaying(x)}ply-z-slt{/if}">&nbsp;</span>
<span class="num">${y+1}</span>
{if type==\'rank\'}
<div class="rk rk-1">
{if x.lastRank>=0}
{if y-x.lastRank>0}
<span class="ico u-icn u-icn-74 s-fc10">${y-x.lastRank}</span>
{elseif y-x.lastRank==0}
<span class="ico u-icn u-icn-72 s-fc4">0</span>
{else}
<span class="ico u-icn u-icn-73 s-fc9">${x.lastRank-y}</span>
{/if}
{else}
<span class="u-icn u-icn-75"></span>
{/if}
</div>
{/if}
</div>
</td>
<td class="u-hasopt">
<div class="f-cb">
<div class="tt">
<div class="ttc">
<span class="txt">
{var alia=songAlia(x)}
<a href="/song?id=${x.id}"><b title="${x.name|escape}{if alia} - (${alia|escape}){/if}">${soil(x.name)}</b></a>{if alia}<span title="${alia|escape}" class="s-fc8"> - (${soil(alia)})</span>{/if}
{if x.mvid>0}
<span data-res-id="${x.id}" data-res-action="mv" title="播放mv" class="mv">MV</span>
{/if}
</span>
</div>
</div>
<div class="opt hshow">
<a class="u-icn u-icn-81 icn-add" href="javascript:;" title="添加到播放列表" hidefocus="true"
data-res-type="18"
data-res-id="${x.id}"
data-res-action="addto"
{if from}data-res-from="${from.fid}" data-res-data="${from.fdata}"{/if}></a>
<span data-res-id="${x.id}" data-res-type="18" data-res-action="fav" class="icn icn-fav" title="收藏"></span>
<span data-res-id="${x.id}" data-res-type="18" data-res-action="share" data-res-name="{if typeof removeQuotes != \'undefined\'}${removeQuotes(x.name)}{else}${x.name}{/if}" data-res-author="{list x.artists as art}${art.name}{if art_index<x.artists.length-1}/{/if}{/list}" {if x.album}data-res-pic="${x.album.picUrl}"{/if} class="icn icn-share" title="分享">分享</span>
<span data-res-id="${x.id}" data-res-type="18" data-res-action="download" class="icn icn-dl" title="下载"></span>
{if canDel}
<span data-res-id="${x.id}" data-res-type="18" data-res-action="delete" class="icn icn-del" title="删除">删除</span>
{/if}
</div>
</div>
</td>
<td class="">
<div class="text" title="{list x.artists as art}${art.name}{if art_index<x.artists.length-1}/{/if}{/list}">
${getArtistName(x.artists, \'\', \'\', false, false, true)}
</div>
</td>
<td class="">
<div class="text">
{if x.album}
<a href="/album?id=${x.album.id}" title="${x.album.name|escape}">${soil(x.album.name)}</a>
{/if}
</div>
</td>
<td class="s-fc3">${formatTime(x.paidTime)}</td>
</tr>
{/list}
</textarea>
<textarea name="ntp" id="m-msg-private-send" style="display:none;"><div class="lyct lyct-1 f-cb">
<div class="m-lyshare m-plshare">
<div class="u-err j-flag" style="display: none;">最多选择10位好友</div>
<div class="item item-1 f-cb">
<label>发 给：</label>
<div class="ct f-pr j-flag">
</div>
</div>
<div class="item f-cb">
<label>内 容：</label>
<div class="ct j-flag">
</div>
</div>
</div>
</div>
</textarea>
</div>
<script src="//s3.music.126.net/web/s/core_c7686f506f6f2607bb7f6cea1951f039.js?c7686f506f6f2607bb7f6cea1951f039" type="text/javascript"></script><script src="//s3.music.126.net/web/s/pt_album_index_c2a5ddb85cef0a4d7c08a826808cc654.js?c2a5ddb85cef0a4d7c08a826808cc654" type="text/javascript"></script>
<script defer="defer" src="//img3.126.net/kaola/dsp1f/js/ntes-ad-cloud.min.js?v=1.0"></script>
</body>
<script type="text/javascript">
var _gaq=_gaq||[];
_gaq.push([\'_setAccount\',\'UA-38766552-1\'],[\'_setLocalGifPath\',\'/UA-38766552-1/__utm.gif\'],[\'_setLocalRemoteServerMode\']);
_gaq.push([\'_trackPageview\']);
//fix ipad下的一个bug
if (navigator.userAgent.indexOf(\'iPad\') != -1) {
iframeHeight = Math.max(
Math.max(document.body.scrollHeight, document.documentElement.scrollHeight),
Math.max(document.body.offsetHeight, document.documentElement.offsetHeight),
Math.max(document.body.clientHeight, document.documentElement.clientHeight)
);
top.document.body.style.height = iframeHeight + 20 + \'px\';
}
</script>
</html>'
];
