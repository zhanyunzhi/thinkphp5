<?php
namespace app\index\controller;
use think\Db;
class Index {

    public function index() {
        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';
    }
    /**
    * 比较标准的接口输出函数
    * @param string  $info 消息
    * @param integer $code 接口错误码，很关键的参数
    * @param array   $data 附加数据
    * @return array
    */
    function var_json($info = '', $code = 10000, $data = array()) {
        $out['code'] = $code ?: 0;
        $out['info'] = $info ?: ($out['code'] ? 'error' : 'success');
        $out['data'] = $data ?: array();
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin:*');
        echo json_encode($out, JSON_HEX_TAG);
        exit(0);
    }
    public function rn() {

        $id = empty($_GET['id']) ? 0 : intval($_GET['id']);
        // $body = file_get_contents('php://input');
        //$a = empty($_POST['a']) ? '' : $_POST['a'];
        //$qq = empty($_POST['qq']) ? 0 : intval($_POST['qq']);
        //$this->var_json('调试返回', 9999, array('a'=>$a,'qq'=>$qq,'body'=>$body));
        //假设这是数据源，如MySQL
        $data = array();
        $data = Db::table('user')->where('id',$id)->select();
        $this->var_json('success', 1000, $data);
        /*$data[979136] = array('qq'=>979136, 'vip'=>5,'level'=>128, 'reg_time'=>1376523234, 'qb'=>300);
        $data[979137] = array('qq'=>979137, 'vip'=>8,'level'=>101, 'reg_time'=>1377123144, 'qb'=>300);
        preg_match('/^[a-zA-Z]+$/', $a) || $this->var_json('非法调用');
        isset($data[$qq]) || $this->var_json('用户不存在', 100001);*/
        switch ($a) {
            //获取用户基本信息
            case 'info':
                //你的更多业务逻辑 ...
                $this->var_json('success', 0, $data[$qq]);
                break;
            //获取动态消息
            case 'message':
                $this->var_json('您正在调用动态消息接口', 0);
                break;
            //获取好友列表
            case 'friends':
                $this->var_json('你正在调用好友列表接口', 0);
                break;
            default:
                $this->var_json('非法调用');
        }
    }
}
