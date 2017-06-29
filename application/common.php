<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件    可以在controller里面直接调用
    /**
     * 比较标准的接口输出函数
     * @param string  $info 消息
     * @param integer $code 接口错误码，很关键的参数
     * @param array   $data 附加数据
     * @return array
     */
function return_result($info = '', $code = '0000', $data = array()) {
    $out['code'] = $code ?: '0';
    $out['info'] = $info ?: ($out['code'] ? 'error' : 'success');
    $out['data'] = $data ?: array();
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin:*');
    echo json_encode($out, JSON_HEX_TAG);
    exit(0);
}
    /**
     * 比较标准的接口输出函数
     * @param string  $namespace 命名空间
     * @return string  32位唯一标识码
     */
function create_guid($namespace = '') {
    static $guid = '';
    $uid = uniqid("", true);
    $data = $namespace;
    $data .= $_SERVER['REQUEST_TIME'];
    $data .= $_SERVER['HTTP_USER_AGENT'];
    $data .= $_SERVER['SERVER_ADDR'];
    $data .= $_SERVER['SERVER_PORT'];
    $data .= $_SERVER['REMOTE_ADDR'];
    $data .= $_SERVER['REMOTE_PORT'];
    $hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
    $guid = '' .
        substr($hash, 0, 8) .
        '-' .
        substr($hash, 8, 4) .
        '-' .
        substr($hash, 12, 4) .
        '-' .
        substr($hash, 16, 4) .
        '-' .
        substr($hash, 20, 12) .
        '';
    return $guid;
}