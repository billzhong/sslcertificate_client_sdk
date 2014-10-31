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
$code = 'bbf1f512b9f57df14c1850629542eafd';
$callback = 'https://www.yourdomainname.com/callback/callback_demo.php';

$ssl = new SSLCertificate();
$res = $ssl->code( $code )
           ->nonce( $order_id )
           ->domain( $domain )
           ->csr( $csr )
           ->callback( $callback )
           ->submitCSR();

if( $res ){
  echo "\r\n<br/>\r\nOrder success\r\n<br/>\r\n";
}else{
  echo "\r\n<br/>\r\nOrder fail\r\n<br/>\r\n";
}
