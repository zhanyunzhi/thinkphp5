<?php
namespace app\index\controller;
use think\Db;

class Active {
    //检查激活码是否存在，存在的话更新激活码为已激活状态，并写入设备标识，写入成功则表示激活成功
    public function check_active_num() {
        Db::listen(function($sql, $time, $explain){
            // 记录SQL
            echo $sql. ' ['.$time.'s]';
            // 查看性能分析结果
            dump($explain);
        });
        $num = empty($_GET['num']) ? 0 : $_GET['num'];
        $deviceNum = empty($_GET['deviceNum']) ? 0 : $_GET['deviceNum'];
        $timeNow = date('Y-m-d H:i:s',time());
        $returnData = array();
        $data = Db::table("active_num")
            ->where("active_num","eq",$num)
            ->where("is_use","eq",0)
            ->where("is_sale","eq",1)
            ->update(["active_time" => $timeNow,"device_num" => $deviceNum]);
        if($data > 0){
            $returnData = array('msg'=>'激活成功');
            return_result('success', 0000, $returnData);
        }else{
            $returnData = array('msg'=>'激活失败');
            return_result('error', 1000, $returnData);
        }
    }
}
