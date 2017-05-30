<?php
namespace app\index\controller;
use think\Db;

class Active {
    //检查激活码是否存在，存在的话更新激活码为已激活状态，并写入设备标识，写入成功则表示激活成功
    public function check_active_num() {
        /*Db::listen(function($sql, $time, $explain){
            // 记录SQL
            echo $sql. ' ['.$time.'s]';
            // 查看性能分析结果
            dump($explain);
        });*/
        $num = empty($_GET['num']) ? 0 : $_GET['num'];
        $deviceNum = empty($_GET['device_id']) ? 0 : $_GET['device_id'];
        $userAgent = empty($_GET['user_agent']) ? 0 : $_GET['user_agent'];
        $timeNow = date('Y-m-d H:i:s',time());
        $returnData = array();
        try{
            $data = Db::table("active_num")
                ->where("active_num","eq",$num)
                ->where("is_use","eq",0)
                ->where("is_sale","eq",1)
                ->update(["active_time" => $timeNow,"device_id" => $deviceNum,"is_use" => 1,"device_user_agent" => $userAgent]);
            if($data > 0){
                $returnData = array('msg'=>'激活成功');
                return_result('success', '0000', $returnData);
            }else{
                $returnData = array('msg'=>'激活失败，请确保您的激活码有效');
                return_result('info', '1000', $returnData);
            }
        }catch(\Exception $e){
            $returnData = array('msg'=>'服务器暂时无法验证您的信息');
            return_result('error', '2000', $returnData);
        }
    }
    //检查用户是否已经激活，根据设备id
    public function is_active() {
        $deviceNum = empty($_GET['device_id']) ? 0 : $_GET['device_id'];
        $returnData = array();
        try{
            $data = Db::table("active_num")
                ->where("device_id","eq",$deviceNum)
                ->where("is_use","eq",1)
                ->where("is_sale","eq",1)
                ->find();
            if($data){
                $returnData = array('msg'=>'已激活');
                return_result('success', '0000', $returnData);
            }else{
                $returnData = array('msg'=>'未激活');
                return_result('info', '1000', $returnData);
            }
        }catch(\Exception $e){
            $returnData = array('msg'=>'服务器暂时无法验证您的信息');
            return_result('error', '2000', $returnData);
        }
    }
}
