<?php

require_once 'ssl_sdk.php';

$ssl = new SSLCertificate();

$action = $_GET['action'];
if( $action == 'notifyDV' ){
  $res= $ssl->receiveDV(); //{"code":200,"data":{"msg":"sucess"}}
  $flag = intval( intval( $res['code'] ) / 100 );
  if( $flag > 1 && $flag < 4 ){
    $code = $res['data']['code'];
    $dv_status = $res['data']['dv_status'];
    $dv_url = @$res['data']['dv_url'];
    $nonce = $res['data']['nonce'];
    $notify_order_id = $nonce; // nonce is id!  
    if( $dv_status == "success" ){
      // success order and notify
    }else if( $dv_status == "reject"){
      // the domain was rejected, and ussually $dv_url will be empty
    }
    echo '{"code":200,"data":{"msg":"sucess"}}';
  }else{
    echo '{"code":500,"data":{"msg":"fail"}}';
  }
}else if( $action == 'notifyCERT' ){
  $res= $ssl->receiveCERT(); //{"code":200,"data":{"msg":"sucess"}}
  if( intval( intval( $res['code'] ) / 100 ) == 2 ){
    $code = $res['data']['code'];
    $cert = @$res['data']['cert'];
    $nonce = $res['data']['nonce'];
    $notify_order_id = $nonce; // nonce is id!  
    if( $cert ){
      // successfully received certificate pem format
    }else{
      // not received... what is the fuck?
    }
    echo '{"code":200,"data":{"msg":"sucess"}}';
  }else{
    echo '{"code":500,"data":{"msg":"fail"}}';
  }
}
