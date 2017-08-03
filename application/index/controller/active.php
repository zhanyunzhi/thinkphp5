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
                Db::table("active_num")                 //访问次数加一
                    ->where("device_id","eq",$deviceNum)
                    ->where("is_use","eq",1)
                    ->where("is_sale","eq",1)
                    ->setInc('visit_amount');
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
    //批量生成激活码
    public function create_active_num() {
        $count = empty($_GET['count']) ? 100 : $_GET['count'];          //生成激活码个数，默认为100
        $buyer = empty($_GET['buyer']) ? 'Tiny' : $_GET['buyer'];          //批量购买激活码的人
        $owner = empty($_GET['owner']) ? 'who' : $_GET['owner'];          //激活码的用有者
        $returnData = array();
        $timeNow = date('Y-m-d H:i:s',time());
        $datas = array();       //批量写入的数组数据集
        $active = array();        //单个写入的数组数据
        $fileDatas = array();       //要写入文件的数组数据集
        if($owner != 'Tiny'){
            $returnData = array('msg'=>'I do not know who you are!');
            return_result('warning', '3000', $returnData);
        }
        for($i=0; $i<$count; $i++){
            $guid = create_guid('Tiny');
            $active = ['active_num' => $guid, 'add_time' => $timeNow, 'is_use' => 0, 'is_sale' => 1, 'owner' => $owner, 'buyer' => $buyer];
            array_push($datas,$active);
            $fileDatas[$i+1] = $guid;
        }
        try{
            $returnCount = Db::name('active_num')->insertAll($datas);
            if($returnCount){
                $path = '../application/index/controller/'.date('Y-m-d',time()).'.doc';         //在linux系统下要修改controller文件夹的权限为777  chmod 777 controller/
                $saveText = print_r($fileDatas, true);
                if(fopen($path,'w')){
                    file_put_contents($path, $saveText);
                    $returnData = array('msg'=>'成功添加'.$returnCount.'条激活码，并成功生成doc文件');
                    return_result('success', '0000', $returnData);
                }else{
                    $returnData = array('msg'=>'成功添加'.$returnCount.'条激活码，但是生成doc文件失败');
                    return_result('success', '0000', $returnData);
                }
            }else{
                $returnData = array('msg'=>'添加失败');
                return_result('info', '1000', $returnData);
            }
        }catch(\Exception $e){
            $returnData = array('msg'=>'服务器错误');
            return_result('error', '2000', $returnData);
        }
    }

    /**
    * 文件下载
    */
    public function download() {
        echo '正在下载...如果长时间没响应，请刷新页面';
        $fileName = '../uploads/bankBill.apk';
        $showName = 'bankBill.apk';
        header('Content-type: application/docx');
        header('Content-Disposition: attachment; filename='.$showName);
        readfile($fileName);
        exit();
    }
}
