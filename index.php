<?php

require_once 'package/PeanutWechat.class.php';

require_once 'package/wx.class.php';
require_once 'package/db.class.php';

require_once 'matching.class.php';
require_once 'retrieval.class.php';
require_once 'polling.class.php';





$wechatObj = new wechatCallbackapiTest();
if (isset($_GET['echostr'])) {
    $wechatObj->valid();
}else{
    $wechatObj->responseMsg();
}

class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
	
    public function responseMsg()
    {
		//数据库操作类对象
		$db = new db();
		//微信连接类对象
		$wx = new wx($db);
		//初始化检索类
		$retrieval = new retrieval($db);
		//获取文本
        $postStr = $wx->postObj('str');
		//检测文本输入
		if (!empty($postStr)){	

			//获取用户属性
			$openid = $wx->postObj('fromUsername');
            $toUsername = $wx->postObj('toUsername');
            $word = $wx->postObj('word');
			//获取token
			$token = $wx -> getToken();
			//初始化客服接口
			$pw =new PeanutWechat($token,$openid);	
			//注册
			$this -> register($openid,$pw,$db);
			//获取user_id
			$api_member_sql = "select user_id from api_member where openid = '".$openid."'" ;
			$api_member_row = $db -> db_select($api_member_sql);
			$user_id=$api_member_row['user_id'];
			//网关状态
	//		$this -> gatewayStatus($db,$user_id,$pw);
			//初始化轮询计划任务类
			$Polling = new Polling($db,$user_id);
			//初始化操作类
			$matching = new matching($db,$user_id);	
			//新建用户场景
			$i_scenes_sql = "SELECT action,scenes_type,scenes_time FROM i_scenes WHERE user_id =  '".$user_id."'";//同一用户同时只存在一条
			$i_scenes_row = $db -> db_select($i_scenes_sql);
			//判断存在该用户的场景
			if(!$i_scenes_row){
				$i_scenes_sql_i="INSERT INTO i_scenes(scenes_time,scenes_date,user_id) 
				VALUES ('".time()."','".date("Y-m-d H:i:s")."',  '".$user_id."')";	
			}
			else{
				$i_scenes_sql_i="UPDATE i_scenes SET scenes_time = '".time()."' , scenes_date = '".date("Y-m-d H:i:s")."' WHERE user_id = '".$user_id."'";	
			}
			$db -> db_update($i_scenes_sql_i);
			//异步处理
			$wx -> asynchronous();
			
			$retrieval -> user_id($user_id,$pw);
		//	$action=$retrieval -> retrieval($word,0);//,$pw
			
			if($i_scenes_row['scenes_type']==1&&$i_scenes_row['scenes_time']>(time()-300)){//有等待回复且未超时
				
				//从对话表读入操作函数，进行回复关键词匹配
				
				
				$i_keyword_confirm_sql = "SELECT data FROM i_keyword_confirm  WHERE keyword = '".$word."'";
				$i_keyword_confirm_row = $db ->db_select($i_keyword_confirm_sql);
			//	$pw ->send($i_keyword_confirm_row);
				$returnData = $retrieval -> repart_r($word);
				if($returnData){
					$action=$i_scenes_row['action'];
					
				}
				else{
					$action=0;
				}
				
				$action=$retrieval -> retrieval($word,$action,$pw);//,$pw
			//	$pw ->send($action);
				$sql = 'Update i_scenes Set scenes_type = 0 Where user_id ='.$user_id;//清除等待回复标记
				$db ->db_update($sql);
				$matching -> matching($action,$pw,$user_id);
			}else{ //正常检索


				$action = 0;
			//	$pw ->send($action);
				$retrieval -> Scenes($word);
				
				$action = $retrieval -> retrieval($word,$action,$pw);//,$pw
			//	$pw ->send($action);
				$matching -> matching($action,$pw,$user_id);
			}

		}

		else{
            $contentStr = "错误";
			$resultStr = $wx->posttext($contentStr);
			echo $resultStr;
            exit;
        }
		
    }
	public function register($openid,$pw,$db){//注册
				   
					
		$sql = "select user_id from api_member where openid = '".$openid."'" ;
						//;
		if (!$row = $db -> db_select($sql)){
			$apikey=substr(md5($openid),2,12);
			$sql="INSERT INTO api_member(openid,apikey) VALUES ( '".$openid."','".$apikey."')";	
			$db -> db_update($sql);
			//$msgType = "text";
			$contentStr = '欢迎新用户';
			$pw ->send($contentStr);
		}
	}
		//网关状态
	public function gatewayStatus($db,$user_id,$pw){
				
		$sql="SELECT sid,nid,data,note,status,regdate,lasttime FROM api_device WHERE user_id='".$user_id."' and sid='1' and nid='1'";
		if (!$row = $db -> db_select($sql)){
					$type=1;
					$sid=1;
					$nid=1;
					$data=$data;
					$note="网关设备";
					$status=1;
					$regdate="";
					$lasttime="";		
					//$ip=get_onlineip();
					$nowt=time();
					$regdate=date("Y-m-d H:i:s",$nowt);
					$lasttime="2014-01-01 00:00:00";
								
				
					$sql="INSERT INTO api_device(type,user_id,sid,nid,data,note,status,regdate,lasttime) 
					VALUES ('".$type."', '".$user_id."', '".$sid."', '".$nid."', '".$data."', '".$note."', '".$status."', '".$regdate."', '".$lasttime."')";	
					$db -> db_update($sql);
		}
		else{
				
					$sid=sprintf("%03d",$row['sid']);
					$nid=sprintf("%03d",$row['nid']);
					$data=$row['data'];
					$status=$row['status'];
					$regdate=$row['regdate'];
					$lasttime=$row['lasttime'];
					
					$nowt=time()-60*2;
			if( strtotime($lasttime)<$nowt ){
					$contentStr = "网关连接失败";//"网关未配置或异常";
					$pw ->send($contentStr);
					exit;
			}
		}
	}
}

?>