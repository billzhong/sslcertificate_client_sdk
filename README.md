#SSLCertificate API Reference


目录
前言
#1. 服务简介 

##2. 基本概念

 1. 数字证书
 2. SSL
 3. 非对称加密
 4. RSA
 5. SHA-1/SHA-256
 6. CSR
 7. APPID
 8. APPKEY
 9. SIGN/SIGNATURE

##3. 调用方式 
 1. 请求结构
  1. 服务地址
  2. 通信协议
  3. 请求方法
  4. 请求组成
  5. 请求编码
 2. 公共参数
  1. 公共请求参数
  2. 公共返回参数
 3. 返回结果
  1. 返回成功
  2. 错误结果
 4. 签名机制
  1. RESTful API和签名
  2. submit 操作
     1. submitCSR
  3. receive 操作
     1. receiveDV
     2. receiveCERT
 5. 错误响应 
  1. 错误响应格式
  2. 错误码表



#前言

#1. 服务简介 

在PC电商与移动互联电商日益成熟的时代，互联网安全显得尤其重要，互联网安全离不开加密通讯技术的支持。
本文档向开发者描述了一份关于SSLCertificate的API的设想



##2. 基本概念

####1.数字证书
>数字证书是由CA签发的、用以声明服务器身份的一项数字加密产品。

----
####2.SSL
>ssl(`secure socket layer`)是使用非对称加密交换密钥后进行加密通讯的协议。具有安全保密的特点。

----
####3.非对称加密
>非对称加密是指用以加密、解密的密码是两份密钥组成的加密技术，一般公钥用于加密，私钥用于解密；且公钥加密过的数据，只有用私钥才能解密的算法才是安全的非对称加密算法。

----
####4.RSA
>rsa是互联网上使用最多的非对称加密算法。

----
####5.SHA-1/SHA-256

>SHA与MD5类似，都是消息摘要算法。SHA-1是现在绝大多数CA所支持的签发算法，但是随着时间进步，计算力变得更加容易，谷歌建议CA尽量不要再使用SHA-1来签发证书了，现在破解一份SHA-1伪造证书的成本已经在10万美元以内。

----
####6. CSR
>CSR(Certificate signature request)是证书申请人在生成私钥后，需要用私钥导出的公钥签一份申请档案，提交给CA(中间可能经过代理商)的文件。CSR不包含私钥，但包含公钥。

----
####7. APPID
>APPID是您在SSL API平台申请代理后，由SSL API发放给您的一份串码，此信息相当于您的用户名。

----
####8. APPKEY
>这是SSL API平台发放给您的一串需要严格保密的字串，此字串仅能有SSL API平台和您两人知晓，切勿泄露，否则他人将可以您的身份请求SSL API消费您的预存款。
>在您请求SSL API时，也请不要将APPKEY请求出去，我们校验您的身份时是使用下面一个字段：SIGNATURE来进行校验的。

----
####9. SIGN/SIGNATURE
>在您请求到SSL API时，安全起见我们不允许您将APPKEY作为参数请求发出，但我们仍然需要用这种签名校验各个参数的方式来检查是不是由您发出的。所以我们使用了SIGNATURE校验的方式。
>因为SIGNATURE只有SSL API平台和您两方知晓，所以我们这种校验方式是安全的。


----



##3. 调用方式 

### 1. 请求结构

####1. 服务地址

>正式环境地址为 [https://www.sslcertificate.cn/api/][1]
>沙箱环境地址为 [https://sandbox.sslcertificate.cn/api/][2]

----
####2. 通信协议

>支持通过 HTTPS 协议请求通信。

----
####3. 请求方法
>使用 HTTP 的 GET METHOD 进行API请求。

----
####4. 请求组成

>向 SSL API 发送 HTTP 请求完成某项 API 接口操作，发送的请求还需要带上正确的请求参数、请求头以及请求正文。

----
####5. 请求编码

>请求及返回结果都使用 UTF-8 字符集进行编码。

----


##2. 公共参数

####1. 公共请求参数
>   `1. action`          操作
>   `2. appid`           请求来源人
>   `3. signature`       签名

---
>请求示例:
```
Remote Address:223.4.0.121:443
Request URL:https://www.sslcertificate.cn/api/?nonce=10002&domain=%2A.sslcertificate.cn&csr=-----BEGIN+CERTIFICATE+REQUEST-----%0AMIIC4zCCAcsCAQAwgZ0xHDAaBgNVBAMUEyouc3NsY2VydGlmaWNhdGUuY24xHDAa%0ABgNVBAoUEyouc3NsY2VydGlmaWNhdGUuY24xFjAUBgNVBAsTDVNlY3VyaXR5IGRl%0AcHQxJTAjBgkqhkiG9w0BCQEWFnNzbGNlcnRpZmljYXRlQDE2My5jb20xCzAJBgNV%0ABAYTAkNOMRMwEQYDVQQIEwpTb21lLVN0YXRlMIIBIjANBgkqhkiG9w0BAQEFAAOC%0AAQ8AMIIBCgKCAQEA5lPmofhDt1CQw7Rr0HffzudUUaHF8%2F5onCI%2F7aKPMKeaVQLJ%0AB5vv1V6qTRelqpywmbXTDPj1X5AjLqM2fzPAjt3vnMdEPjddyfSxCv1KEWxAytNP%0AlFDnCUsv0Z7gSZDPJYwleoBgxUFVvRgRs%2BDfkdafGqlQV2TiDtZsZlq2nLLz5UlO%0AwjwbmHS6OGCIOYbgcnokVVHHug9%2FgpDLeHo4ZyHJd9ZZK0SxrBKNtex94DpkP12P%0AxT83FVNEsnaXjlaR%2Fc0TcGbiGQ3BiSUI1VBmQFL2KNuk6KyXxg2ot7EHQ1GnG5P6%0A%2BLR4zTjXySeZia13%2FAm2NwBRzyrbp0yp99nT5wIDAQABoAAwDQYJKoZIhvcNAQEE%0ABQADggEBAI4PApFve6GDigGoZtfecgEugoDi%2B%2BA7%2FNjpULduR9ijsoiOSvMesF7Z%0ARtZQ8HQcwavi5apO5o1zPkZYJvbUQ6KYeL3rR1iIlobktSjVHboiQxOfDwMrVcMs%0AjBL%2BKXl%2BcqbgfQqAXDFRFmeRtyfpJ10HTCLXFWqNVTz8HTRLHyHwCM5wNAmQfGlQ%0A3tPGEdJTqAMXa8wMdgoxAQNO3Ru%2FMnM8nkG3GUWtal1q1q2aWodPP3xTGWA94DRJ%0AWVnToJKY7ZdzapvSMeJxevxoeeIqmfR5chIkfvRIj%2FZcHZoe%2BfpZvdgvEV3bJbsp%0A5QQVXwnJVWXI6CxyJUHebB8pwyDGBi8%3D%0A-----END+CERTIFICATE+REQUEST-----&callback=https%3A%2F%2Fwww.yourdomainname.com%2Fcallback%2Fcallback_demo.php&action=submitCSR&appid=dev&version=2014-11-11&timestamp=2014-11-24T06%3A14%3A17Z&code=&signature=3imAGbCliWXWVxfXJvUNVKkQ%2FMA%3D
Request Method:GET
Status Code:200 OK
Request Headersview source
Accept:text/html,application/xhtml+xml,application/xml,image/webp
Accept-Encoding:gzip, deflate, sdch
Accept-Language:zh-CN,zh;q=0.8
Cache-Control:max-age=0
Connection:keep-alive
Host:www.sslcertificate.cn
User-Agent:Mozilla/5.0 Chrome/39.0.2171.65 Safari/537.36
Query String Parameters
nonce:10002
domain:*.sslcertificate.cn
csr:-----BEGIN CERTIFICATE REQUEST-----
MIIC4zCCAcsCAQAwgZ0xHDAaBgNVBAMUEyouc3NsY2VydGlmaWNhdGUuY24xHDAa
BgNVBAoUEyouc3NsY2VydGlmaWNhdGUuY24xFjAUBgNVBAsTDVNlY3VyaXR5IGRl
cHQxJTAjBgkqhkiG9w0BCQEWFnNzbGNlcnRpZmljYXRlQDE2My5jb20xCzAJBgNV
BAYTAkNOMRMwEQYDVQQIEwpTb21lLVN0YXRlMIIBIjANBgkqhkiG9w0BAQEFAAOC
AQ8AMIIBCgKCAQEA5lPmofhDt1CQw7Rr0HffzudUUaHF8/5onCI/7aKPMKeaVQLJ
B5vv1V6qTRelqpywmbXTDPj1X5AjLqM2fzPAjt3vnMdEPjddyfSxCv1KEWxAytNP
lFDnCUsv0Z7gSZDPJYwleoBgxUFVvRgRs+DfkdafGqlQV2TiDtZsZlq2nLLz5UlO
wjwbmHS6OGCIOYbgcnokVVHHug9/gpDLeHo4ZyHJd9ZZK0SxrBKNtex94DpkP12P
xT83FVNEsnaXjlaR/c0TcGbiGQ3BiSUI1VBmQFL2KNuk6KyXxg2ot7EHQ1GnG5P6
+LR4zTjXySeZia13/Am2NwBRzyrbp0yp99nT5wIDAQABoAAwDQYJKoZIhvcNAQEE
BQADggEBAI4PApFve6GDigGoZtfecgEugoDi++A7/NjpULduR9ijsoiOSvMesF7Z
RtZQ8HQcwavi5apO5o1zPkZYJvbUQ6KYeL3rR1iIlobktSjVHboiQxOfDwMrVcMs
jBL+KXl+cqbgfQqAXDFRFmeRtyfpJ10HTCLXFWqNVTz8HTRLHyHwCM5wNAmQfGlQ
3tPGEdJTqAMXa8wMdgoxAQNO3Ru/MnM8nkG3GUWtal1q1q2aWodPP3xTGWA94DRJ
WVnToJKY7ZdzapvSMeJxevxoeeIqmfR5chIkfvRIj/ZcHZoe+fpZvdgvEV3bJbsp
5QQVXwnJVWXI6CxyJUHebB8pwyDGBi8=
-----END CERTIFICATE REQUEST-----
callback:https://www.yourdomainname.com/callback/callback_demo.php
action:submitCSR
appid:dev
version:2014-11-11
timestamp:2014-11-24T06:14:17Z
code:
signature:3imAGbCliWXWVxfXJvUNVKkQ/MA=
```

----
####2. 公共返回参数
>   `1. code`            返回的状态码
>   `2. data`            数据
>   `2. data.msg`        消息

---
>返回示例:
```
{
    code: 403,
    data: {
        msg: "timestamp inaccuracy is over than 15 minutes."
    }
}
```

##3. 返回结果
####1. 返回成功
>不管执行成功失败，返回结果均会包含如下JSON结构开发者可以很方便地进行Parse结果
```
code: 状态码 成功时始终为20x(如200, 201, 204...), 失败时为其它(如403, 500...)
data: 数据
data.msg: 消息
```

---
####2. 错误结果
>当请求参数有误、无权限操作、资源限制或服务器处理出错时，SSL API会返回code不为20x的结果，并会将错误信息给出到data.msg中。
如：
```
{
    code: 400,
    data: {
        msg: "agent's balance is not enough to finish this order!"
    }
}
```
>就是告诉， 这个代理商的余额过低，无法下单。

---


>>>>>#未完待续(Please wait...)



---
>调用 [PHP SDK][3] 示例:

```
<?php
require_once 'ssl_sdk.php';
$csr = '-----BEGIN CERTIFICATE REQUEST-----
MIIC4zCCAcsCAQAwgZ0xHDAaBgNVBAMUEyouc3NsY2VydGlmaWNhdGUuY24xHDAa
BgNVBAoUEyouc3NsY2VydGlmaWNhdGUuY24xFjAUBgNVBAsTDVNlY3VyaXR5IGRl
cHQxJTAjBgkqhkiG9w0BCQEWFnNzbGNlcnRpZmljYXRlQDE2My5jb20xCzAJBgNV
BAYTAkNOMRMwEQYDVQQIEwpTb21lLVN0YXRlMIIBIjANBgkqhkiG9w0BAQEFAAOC
AQ8AMIIBCgKCAQEA5lPmofhDt1CQw7Rr0HffzudUUaHF8/5onCI/7aKPMKeaVQLJ
B5vv1V6qTRelqpywmbXTDPj1X5AjLqM2fzPAjt3vnMdEPjddyfSxCv1KEWxAytNP
lFDnCUsv0Z7gSZDPJYwleoBgxUFVvRgRs+DfkdafGqlQV2TiDtZsZlq2nLLz5UlO
wjwbmHS6OGCIOYbgcnokVVHHug9/gpDLeHo4ZyHJd9ZZK0SxrBKNtex94DpkP12P
xT83FVNEsnaXjlaR/c0TcGbiGQ3BiSUI1VBmQFL2KNuk6KyXxg2ot7EHQ1GnG5P6
+LR4zTjXySeZia13/Am2NwBRzyrbp0yp99nT5wIDAQABoAAwDQYJKoZIhvcNAQEE
BQADggEBAI4PApFve6GDigGoZtfecgEugoDi++A7/NjpULduR9ijsoiOSvMesF7Z
RtZQ8HQcwavi5apO5o1zPkZYJvbUQ6KYeL3rR1iIlobktSjVHboiQxOfDwMrVcMs
jBL+KXl+cqbgfQqAXDFRFmeRtyfpJ10HTCLXFWqNVTz8HTRLHyHwCM5wNAmQfGlQ
3tPGEdJTqAMXa8wMdgoxAQNO3Ru/MnM8nkG3GUWtal1q1q2aWodPP3xTGWA94DRJ
WVnToJKY7ZdzapvSMeJxevxoeeIqmfR5chIkfvRIj/ZcHZoe+fpZvdgvEV3bJbsp
5QQVXwnJVWXI6CxyJUHebB8pwyDGBi8=
-----END CERTIFICATE REQUEST-----';
$domain = '*.sslcertificate.cn';
$order_id = 10000;
$callback = 'https://www.yourdomainname.com/callback/callback_demo.php';
$ssl = new SSLCertificate( 'dev', '234354365', 'sandbox' );
// 'sandbox'用于开启测试
// 不设置或者设置成其它则为正式环境
$do = $ssl->nonce( $order_id )
           ->domain( $domain )
           ->csr( $csr )
           ->callback( $callback )
           ->submitCSR();
if( $do ){
  //成功
}else{
  //失败
}
```

  [1]: https://www.sslcertificate.cn/api/
  [2]: https://sandbox.sslcertificate.cn/api/
  [3]: https://github.com/xoxossl/sslcertificate_client_sdk/