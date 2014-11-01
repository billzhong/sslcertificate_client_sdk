<?php
/**
 * @File:   ssl_sdk.php
 * @Author: xoxo
 * @Date:   2014/10/31 16:06:39
 */
class SSLCertificate{
  var $appid = 'appid';   // 编辑成你的appid
  var $appkey = 'appkey'; // 编辑成你的appkey

  var $gateway = "https://www.sslcertificate.cn/api/";
  // for debug: https://sandbox.sslcertificate.cn/api/
  // for debug code: https://sandbox.sslcertificate.cn/api/code/

  var $version = "2014-10-27";
  var $data = array();

  /**
   * generate signature
   * @param  array $parameters 参数
   * @return string            签名
   */
  private function signature( $parameters ){
    $parameter_names = array_keys( $parameters );
    sort( $parameter_names );
    $to_sign_array = array();
    foreach ($parameter_names as $v) {
      $to_sign_array[] = strtolower($v) . '=' . $parameters[$v];
    }
    $to_sign = http_build_query( $to_sign_array );
    $signature = base64_encode( hash_hmac('sha1', $to_sign, $this->appkey, true ) );
    return $signature;
  }

  public function exec( $gateway ){
    $this->data['appid'] = $this->appid;
    $this->data['version'] = $this->version;
    $this->data['timestamp'] = gmdate('Y-m-d\TH:i:s\Z');
    $this->data['signature'] = $this->signature( $this->data );
    $url = $gateway . "?" . http_build_query( $this->data );
    $opts = array(
      'http'=>array(
        'method'=>"GET",
        'header'=>"Accept-language: en\r\n" .
                  "User-Agent: SSLCertificate API Client v20141111\r\n"
      )
    );
    $context = stream_context_create($opts);
    $result = file_get_contents( $url, false, $context );
    return $result;
  }

  public function code( $code ){
    $this->data['code'] = $code;
    return $this;
  }

  /**
   * set nonce
   * @param  string $nonce nonce数据,限制1至32位长,可以传递订单号,sslcertificateAPI通知的时候会将nonce传回.
   */
  public function nonce( $nonce ){
    $this->data['nonce'] = $nonce;
    return $this;
  }

  public function domain( $domain ){
    $this->data['domain'] = $domain;
    return $this;
  }

  public function csr( $csr ){
    $this->data['csr'] = $csr;
    return $this;
  }

  public function callback( $callback ){
    $this->data['callback'] = $callback;
    return $this;
  }

  public function submitCSR(){
    $this->data['action'] = 'submitCSR';
    $result = $this->exec( $this->gateway );
    $data = json_decode( $result, true );
    if( isset($data['code']) && intval( $data['code'] / 100 ) == 2 ){
      return true;
    }else{
      return false; // track reason?
    }
  }

  public function receiveDV(){
    $signature = $_GET['signature'];
    unset( $_GET['signature'] );
    $local_signature = $this->signature( $_GET );
    if( $signature != $local_signature ){echo json_encode( array('code' => 403, 'data' => array('msg' => 'signature does not match the real') ) ); exit(); }
    $code = $_GET['code'];
    $domain = $_GET['domain'];
    $nonce = $_GET['nonce'];
    $dv_status = $_GET['dv_status'];
    if( $dv_status == "success" ){// success...　可以把nonce理解为订单号
      return array( 'code'=>200, 'data'=> array('code' => $code, 'dv_status' => 'success', 'dv_url' => $_GET['dv_url'], 'nonce' => $nonce ) );
    }else if( $dv_status == "reject" ){// reject...
      return array( 'code'=>301, 'data'=> array('code' => $code, 'dv_status' => 'reject', 'dv_url' => false, 'nonce' => $nonce ) );
    }else{
      return array( 'code'=>500, 'data'=> array('code' => $code, 'dv_status' => $dv_status, 'dv_url' => false, 'nonce' => $nonce ) );
    }
  }

  public function receiveCERT(){
    $signature = $_GET['signature'];
    unset( $_GET['signature'] );
    $local_signature = $this->signature( $_GET );
    if( $signature != $local_signature ){echo json_encode( array('code' => 403, 'data' => array('msg' => 'signature does not match the real') ) ); exit(); }
    $code = $_GET['code'];
    $domain = $_GET['domain'];
    $nonce = $_GET['nonce'];
    $cert = $_GET['cert'];
    if( $cert ){// success...
      return array( 'code'=>200, 'data'=> array('code' => $code, 'cert' => $cert, 'nonce' => $nonce ) );
    }else{
      return array( 'code'=>500, 'data'=> array('code' => $code, 'dv_status' => $dv_status, 'dv_url' => false, 'nonce' => $nonce ) );
    }
  }

}

