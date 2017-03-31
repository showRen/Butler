<?php
class matching{
	private $db;
	private $user_id;
	private $pw;
	public function matching($action,$pw,$user_id){
		$this -> pw = $pw;
			if($action <= 0){
				$str = "没有听懂";
			}
			else if(0 < $action && $action < 1000){
				$str = "你想知道关于它的什么呢";
			}
			else if(1000 <= $action && $action < 10000){
				$str = "你想做什么操作呢";
			}
			else{
				$i_scenes_sql = 'SELECT node_site,node_date,node_date_data FROM i_scenes WHERE user_id = '.$user_id;//同一用户同时只存在一条
				$i_scenes_data = $this ->db -> db_select($i_scenes_sql);
				switch ($action){
					
					case 10100:
							if($i_scenes_data["node_date_data"]>0){
								$date = $i_scenes_data["node_date"];
								$date_data = $i_scenes_data["node_date_data"];
								$chengshi=$i_scenes_data["node_site"];
								$strtest =  'https://free-api.heweather.com/v5/weather?city='.$chengshi.'&key=baf575be81664c73987708be9c8d877d';
								$row = json_decode($this -> pw->get($strtest),true);
								$str = '主人~'.$chengshi.$date.'天气:'.$row["HeWeather5"][0]["daily_forecast"][$date_data]["cond"]["txt_d"]
									//	."转".$row["HeWeather5"][0]["daily_forecast"][$date_data]["cond"]["txt_n"]."，"
										."，"
										."气温".$row["HeWeather5"][0]["daily_forecast"][$date_data]["tmp"]["min"]."-"
										.$row["HeWeather5"][0]["daily_forecast"][$date_data]["tmp"]["max"]."℃，"
										.$row["HeWeather5"][0]["daily_forecast"][$date_data]["wind"]["dir"]
										.$row["HeWeather5"][0]["daily_forecast"][$date_data]["wind"]["sc"]."级。";
						//		$pw->send($date_data);
							}
							else{
								$chengshi=$i_scenes_data["node_site"];
								$strtest =  'https://free-api.heweather.com/v5/now?city='.$chengshi.'&key=baf575be81664c73987708be9c8d877d';
								$row = json_decode($this -> pw->get($strtest),true);
								$str = '主人~'.$chengshi."今天天气".$row["HeWeather5"][0]["now"]["cond"]["txt"]
								."，温度为".$row["HeWeather5"][0]["now"]["tmp"]."℃,"
								.$row["HeWeather5"][0]["now"]["wind"]["dir"]
								.$row["HeWeather5"][0]["now"]["wind"]["sc"]."级。";

							}
							$this -> pw->send($str);
							return 1;
							break;
							
					case 10101:
							$date_data = $i_scenes_data["node_date_data"];
							$date = $i_scenes_data["node_date"];
							$chengshi=$i_scenes_data["node_site"];
							$strtest =  'https://free-api.heweather.com/v5/weather?city='.$chengshi.'&key=baf575be81664c73987708be9c8d877d';
							$row = json_decode($pw->get($strtest),true);
							
							$str = $chengshi.$date."的降水概率为".$row["HeWeather5"][0]["daily_forecast"][$date_data]["pop"]."%";
							
							$this -> pw->send($str);
							return 1;
							break;
					case 10102:
							
							$date = $i_scenes_data["node_date"];
								$date_data = $i_scenes_data["node_date_data"];
								$chengshi=$i_scenes_data["node_site"];
								$strtest =  'https://free-api.heweather.com/v5/weather?city='.$chengshi.'&key=baf575be81664c73987708be9c8d877d';
								$row = json_decode($pw->get($strtest),true);
								$str = $chengshi.$date.$row["HeWeather5"][0]["daily_forecast"][$date_data]["wind"]["dir"]
										.$row["HeWeather5"][0]["daily_forecast"][$date_data]["wind"]["sc"]."级";
								$this -> pw->send($str);
							return 1;
							break;
							
					case 10103:
							$chengshi=$i_scenes_data["node_site"];
							$strtest =  'https://free-api.heweather.com/v5/now?city='.$chengshi.'&key=baf575be81664c73987708be9c8d877d';
							$row = json_decode($this -> pw->get($strtest),true);
							
							$str = $chengshi."温度为".$row["HeWeather5"][0]["now"]["tmp"]."℃";
							$this -> pw->send($str);
							return 1;
							break;
							
					
					case 10104:
								if(date("G")<12){
									$chengshi=$i_scenes_data["node_site"];
									$strtest =  'https://free-api.heweather.com/v5/now?city='.$chengshi.'&key=baf575be81664c73987708be9c8d877d';
									$row = json_decode($this -> pw->get($strtest),true);
									$str = '主人~'.$chengshi."今天天气".$row["HeWeather5"][0]["now"]["cond"]["txt"]
									."，温度为".$row["HeWeather5"][0]["now"]["tmp"]."℃,"
									.$row["HeWeather5"][0]["now"]["wind"]["dir"]
									.$row["HeWeather5"][0]["now"]["wind"]["sc"]."级。\n今天又是元气满满的一天哦，加油~";
									$this -> pw->send($str);
								}else{
									$this -> pw->send("笨蛋主人，现在不是早上啊！");
								}
								
							return 1;
							break;
							
					case 20001:
							
							$this -> pw->send("你好！");
							$sql = $this -> record_time($action);
							return 1;
							break;
					case 20002:
							
							$this -> pw->send("主人你好，我叫佳佳~");
							$sql = $this -> record_time($action);
							return 1;
							break;
					case 20003:
							
							$this -> pw->send("我不傻");
							$sql = $this -> record_time($action);
							return 1;
							break;
					case 20004:
							
							$this -> pw->send("我今年3岁了");
							$sql = $this -> record_time($action);
							return 1;
							break;
					case 20005:
							
							$this -> pw->send("再见~");
							$sql = $this -> record_time($action);
							return 1;
							break;
					case 20006:
							
							$this -> pw->send("有啥开心的事");
							$sql = $this -> record_time($action);
							return 1;
							break;
					case 20007:
							$this -> pw->send('早上好，主人');
							return 1;
							break;
							
					case 20008:
							$str = '已经十点了主人，别太累了要早睡呀';
							$this -> pw->send($str);
							return 1;
							break;	
				/*	case 20009:
						if(date("G")==22){
							$sql = "SELECT scenes_time FROM i_scenes WHERE user_id =  ".$this -> user_id;
							$row = $this -> db -> db_select($sql);
							if($row['scenes_time']>time()-600){
								$sql = "SELECT Next_time FROM i_scheduled_task WHERE user_id =  '".$this -> user_id."'AND action = 20008";
								$row = $this -> db ->db_select($sql);
								if($row['Next_time']<time()){
									$sql = 'Update i_scheduled_task Set Next_time ='.(time()+75000) .' ,Last_time = '.time().' Where user_id ='.$this->user_id.' AND action = 20008';
									$this -> db ->db_update($sql);
									$this -> pw->send('已经十点了主人，别太累了要早睡呀');
									
								}else{
									$sql = "INSERT INTO i_scheduled_task(action,user_id,Next_time)
									VALUES ('".(20008)."' , '".($this -> user_id)."', '".time()."')";
									$this -> db ->db_update($sql);
									
								}
							}
						}
							return 1;
							break;*/
							
					case 30001://温湿度
							$temperatureHumidity = $this ->temperatureHumidity();
							$this -> pw->send($temperatureHumidity);
							$sql = $this -> record_time($action);
							return 1;
							break;
					
					
												
					case 30021:
							$this -> pw ->send('室温有点低，要打开空调么');
							$sql = "Update i_scenes Set action = 90020 ,scenes_type = 1 Where user_id =".$this->user_id;
							$this -> db ->db_update($sql);
							return 1;
							break;
							
					case 30022:
							$this -> pw ->send('室温有点高，要打开空调么');
							$sql = "Update i_scenes Set action = 90020 ,scenes_type = 1 Where user_id =".$this->user_id;
							$this -> db ->db_update($sql);
							return 1;
							break;
					case 30026:
							$this -> pw ->send('室温有点低，要调整空调温度么');
							$sql = "Update i_scenes Set action = 90030 ,scenes_type = 1 Where user_id =".$this->user_id;
							$this -> db ->db_update($sql);
							return 1;
							break;
					case 30027:
							$this -> pw ->send('室温有点高，要调整空调温度么');
							$sql = "Update i_scenes Set action = 90030 ,scenes_type = 1 Where user_id =".$this->user_id;
							$this -> db ->db_update($sql);
							return 1;
							break;
					case 30031:
							$this -> pw ->send('有点干，要打开加湿器么');
							$sql = "Update i_scenes Set action = 90030 ,scenes_type = 1 Where user_id =".$this->user_id;
							$this -> db ->db_update($sql);
							return 1;
							break;
					case 30032:
							$this -> pw ->send('湿度有点高，主人要注意身体');
							$sql = "Update i_scenes Set action = 90030 ,scenes_type = 1 Where user_id =".$this->user_id;
							$this -> db ->db_update($sql);
							return 1;
							break;	
							
					case 30041:
							$this -> pw ->send('天亮了，要把灯关了么');
							$sql = "Update i_scenes Set action = 90045 ,scenes_type = 1 Where user_id =".$this->user_id;
							$this -> db ->db_update($sql);
							return 1;
							break;
					case 30042:
							$this -> pw ->send('天黑了，要打开灯么');
							$sql = "Update i_scenes Set action = 90040 ,scenes_type = 1 Where user_id =".$this->user_id;
							$this -> db ->db_update($sql);
							return 1;
							break;
					
							
					case 90001:
							
							$this -> pw->send("请问可以打开么（定时）");
							$sql = "Update i_scenes Set action = 90001 , scenes_type = 1 ,scenes_time = '".time()."',scenes_date = '".date("Y-m-d H:i:s")."' Where user_id =".$this->user_id;//将等待回复信息写入场景
							$this -> db ->db_update($sql);
							
							return 1;
							break;
					
					
					case 90002:
							$this -> pw->send("打开（定时）");//确认+1
							$sql = "Update i_scenes Set action = 0 , scenes_type = 0 ,scenes_time = '".time()."',scenes_date = '".date("Y-m-d H:i:s")."' Where user_id =".$this->user_id;//将等待回复信息写入场景
							$this -> db ->db_update($sql);
							$sql = 'Update i_scheduled_task Set Next_time ='.(time()+1000) .' ,Last_time = '.time().' 
							Where user_id ='.$this->user_id.' AND action = 90001';//延时
							
							$this -> db ->db_update($sql);
							//延时
							return 1;
							break;
					case 90003:
							$this -> pw->send("延时（定时）");//等待+2
							$sql = "Update i_scenes Set action = 0 , scenes_type = 0 ,scenes_time = '".time()."',scenes_date = '".date("Y-m-d H:i:s")."' Where user_id =".$this->user_id;//将等待回复信息写入场景
							$this -> db ->db_update($sql);
							return 1;
							break;
					case 90004:
							$this -> pw->send("好的（定时）");//拒绝+3
							$sql = "Update i_scenes Set action = 0 , scenes_type = 0 ,scenes_time = '".time()."',scenes_date = '".date("Y-m-d H:i:s")."' Where user_id =".$this->user_id;//将等待回复信息写入场景
							$this -> db ->db_update($sql);
							return 1;
							break;
							
					case 90005:
							$this -> pw->send("请问执行么（用户请求）");
							$sql = "Update i_scenes Set action = 90005 ,scenes_type = 1 Where user_id =".$this->user_id;
							$this -> db ->db_update($sql);
							$sql = 'Update AI_timer Set Next_time ='.(time()+60) .' ,Last_time = '.time().'Where user_id ='.$this->user_id;
							$this -> db ->db_update($sql);
							return 1;
							break;
					
					case 90006:
							$this -> pw->send("执行（用户请求）");//确认+1
							$sql = 'Update i_scenes Set scenes_type = 0 Where user_id ='.$this->user_id;//将等待回复信息写入场景
							$this -> db ->db_update($sql);
							$sql = 'Update AI_timer Set Next_time ='.(time()+7200) .' ,Last_time = '.time().'Where user_id ='.$this->user_id;
							$this -> db ->db_update($sql);
							//延时
							return 1;
							break;
					
					case 90007:
							$this -> pw->send("延时（用户请求）");//等待+2
							$sql = "Update i_scenes Set scenes_type = 0 Where user_id =".$this->user_id;//将等待回复信息写入场景
							$this -> db ->db_update($sql);
							return 1;
							break;
					case 90008:
							$this -> pw->send("好的（用户请求）");//拒绝+3
							$sql = "Update i_scenes Set action = 0 , scenes_type = 0 Where user_id =".$this->user_id;//将等待回复信息写入场景
							$this -> db ->db_update($sql);
							return 1;
							break;
					
				/*	case 90008:
							$this -> pw->send("时间");//拒绝+3
							$sql = "Update i_scenes Set action = 0 , scenes_type = 0 Where user_id =".$this->user_id;//将等待回复信息写入场景
							$this -> db ->db_update($sql);
							return 1;
							break;*/
						
							//设想，检索全部，加起来，明天=60*60*24 八点=60*60*8，减去当前时间时*60*60+分*60，写入next_time
					
					case 90014:
							$this -> pw->send("延时（用户请求）");//等待+2
							$sql = "Update i_scenes Set scenes_type = 0 Where user_id =".$this->user_id;//将等待回复信息写入场景
							$this -> db ->db_update($sql);
							return 1;
							break;
					case 90015:
							$this -> pw->send("好的（用户请求）");//拒绝+3
							$sql = "Update i_scenes Set action = 0 , scenes_type = 0 Where user_id =".$this->user_id;//将等待回复信息写入场景
							$this -> db ->db_update($sql);
							return 1;
							break;
					
	
					case 90021:
							$sql = "Select info,switch From i_equipment Where equipment = '空调' AND user_id = ".$this -> user_id;
							$row = $this -> db -> db_select($sql);
							if($row['switch']){
									$this -> pw -> send("笨蛋主人，空调开着呢");
							}else{
								$this -> pw->send("空调已经打开");//确认+1
								
								$sql = "Update i_equipment Set switch = 1 Where user_id =".$this->user_id." AND equipment = '空调'";//将等待回复信息写入场景
								$this -> db ->db_update($sql);
								//延时
								
								$sql = 'Update i_scenes Set scenes_type = 0 Where user_id ='.$this->user_id;//将等待回复信息写入场景
								$this -> db ->db_update($sql);
								
								$this -> record_time($action);
								$this -> record_info($action,1);
							}
							return 1;
							break;
					case 90022:
							$sql = "Select info,switch From i_equipment Where equipment = '空调' AND user_id = ".$this -> user_id;
							$row = $this -> db -> db_select($sql);
							if(!$row['switch']){
									$this -> pw -> send("报告主人，空调没有打开");
							}else{
								$this -> pw->send("空调已经关闭");//确认+1

								$sql = "Update i_equipment Set switch = 0 Where user_id =".$this->user_id." AND equipment = '空调'";//将等待回复信息写入场景
								$this -> db ->db_update($sql);
								//延时
								
								$sql = 'Update i_scenes Set scenes_type = 0 Where user_id ='.$this->user_id;//将等待回复信息写入场景
								$this -> db ->db_update($sql);
								
								$this -> record_time($action);
								$this -> record_info($action,0);
							}
							return 1;
							break;
						
					case 90030://空调状态
								$sql = "Select info,switch From i_equipment Where equipment = '空调' AND user_id = ".$this -> user_id;
								$row = $this -> db -> db_select($sql);
								if($row['switch']){
									$row = $this -> db ->db_select($sql);
									$this -> pw -> send("报告主人，空调当前设定是".$row['info']."度。");
								}else{
									$this -> pw -> send("主人，空调没有打开");
								}
								
							return 1;
							break;
					
					case 90031://设定空调温度(自动)
							$sql = "Select scenes_data From i_frequency_info Where user_id =".$this->user_id."	AND action = 90032 Order by frequency Desc";//将等待回复信息写入场景
							$row = $this -> db ->db_select($sql);
							if($row){
								$data = $row['scenes_data'];
								$this -> pw -> send("已经将空调设定为".$data."度");
								$this -> record_info($action,$data);
							}else{
								$data = 23;
								$this -> pw -> send("已经将空调设定为".$data."度");
							}
						//	$sql = "Update i_equipment Set equipment = '空调' , switch = 1 ,info = '".time()."',scenes_date = '".date("Y-m-d H:i:s")."' Where user_id =".$this->user_id;//将等待回复信息写入场景
							$sql = "Update i_equipment Set time = ".time().",info = ".$data." Where user_id =".$this->user_id." AND equipment = '空调'";//将等待回复信息写入场景
							$this -> db ->db_update($sql);
							
							$sql = 'Update i_scenes Set scenes_type = 0 Where user_id ='.$this->user_id;//将等待回复信息写入场景
							$this -> db ->db_update($sql);
							return 1;
							break;
					
					case 90032://设定空调温度
							$sql = "Select scenes_data From i_scenes Where user_id =".($this->user_id)." AND node_type = 1";//将等待回复信息写入场景
							$row = $this -> db ->db_select($sql);
						//	$this -> pw ->send($sql);
							if($row){
								$this -> pw ->send("已经将空调设定为".$row['scenes_data']."度");
								//$sql = "Update i_equipment Set equipment = '空调' , switch = 1 ,info = '".time()."',scenes_date = '".date("Y-m-d H:i:s")."' Where user_id =".$this->user_id;//将等待回复信息写入场景
								//$this -> db ->db_update($sql);
								$sql = "Update i_equipment Set time = ".time().",info = ".$row['scenes_data']." Where user_id =".$this->user_id." AND equipment = '空调'";//将等待回复信息写入场景
								$this -> db ->db_update($sql);
								$this -> record_info($action,$row['scenes_data']);
							}else{
							
								$sql = "Select info From i_equipment Where equipment = '空调' AND user_id = ".$this -> user_id;
								$row = $this -> db ->db_select($sql);
								$this -> pw -> send("空调当前为".$row['info']."度，需要调整么？");
							}
							
							return 1;
							break;
					
					case 90040://灯状态
								$sql = "Select info,switch From i_equipment Where equipment = '灯' AND user_id = ".$this -> user_id;
								$row = $this -> db -> db_select($sql);
								if($row['switch']){
									$this -> pw -> send("报告主人，灯开着呢");
								}else{
									$this -> pw -> send("报告主人，灯没有打开");
								}
								
							return 1;
							break;
					
					
					case 90041:
							if(date("G")>6&&date("G")<18){
								$sql = "Select info,switch From i_equipment Where equipment = '灯' AND user_id = ".$this -> user_id;
								$row = $this -> db -> db_select($sql);
								if($row['switch']){
									$this -> pw -> send("报告主人，灯开着呢");
								}else{
									$this -> pw ->send('主人，您确定要打开灯么？白天开灯会浪费电呀！');
									$sql = "Update i_scenes Set action = 90044 ,scenes_type = 1 Where user_id =".$this->user_id;
							//		$this -> pw ->send($sql);
									$this -> db ->db_update($sql);
								}
								
							}else{
								
								$sql = "Select info,switch From i_equipment Where equipment = '灯' AND user_id = ".$this -> user_id;
								$row = $this -> db -> db_select($sql);
								if($row['switch']){
									$this -> pw -> send("报告主人，灯开着呢");
								}else{
									$infrared = '1,16754775,32';
									$sid = 5;
									$nid = 1;
									$this -> infraredEmission($infrared,$sid,$nid,$user_id);//$data,$sid,$nid    1,16754775,32,（类型,值,字节）
									$this -> pw ->send('灯已经打开');
									
									$this -> record_time($action);
									$this -> record_info($action,1);
									
									$sql = "Update i_equipment Set switch = 1 , info = ".date("G")." Where user_id =".$this->user_id." AND equipment = '灯'";//将等待回复信息写入场景
									$this -> db ->db_update($sql);
									
									
								}
								$sql = 'Update i_scenes Set scenes_type = 0 Where user_id ='.$this->user_id;//将等待回复信息写入场景
								$this -> db ->db_update($sql);
									
							}
							
							return 1;
							break;
							
					case 90044:
							$this -> pw ->send('好的主人');
							$sql = 'Update i_scenes Set scenes_type = 0 Where user_id ='.$this->user_id;//将等待回复信息写入场景
							$this -> db ->db_update($sql);
							return 1;
							break;	
							
					case 90045:
							$infrared = '1,16754775,32';
							$sid = 5;
							$nid = 1;
							$this -> infraredEmission($infrared,$sid,$nid,$user_id);//$data,$sid,$nid    1,16754775,32,（类型,值,字节）
							$this -> pw ->send('好吧主人，听你的就是，我把灯已经打开了');
							$this -> record_time($action);
							$this -> record_info($action,1);
							$sql = "Update i_equipment Set switch = 1 , info = ".date("G")." Where user_id =".$this->user_id." AND equipment = '灯'";//将等待回复信息写入场景
							$this -> db ->db_update($sql);
							
							$sql = 'Update i_scenes Set scenes_type = 0 Where user_id ='.$this->user_id;//清除等待回复标记
							$this -> db ->db_update($sql);
							return 1;
							break;
							
					case 90046:
							$infrared = '1,16754775,32';
							$sid = 5;
							$nid = 1;
							$this -> infraredEmission($infrared,$sid,$nid,$user_id);//$data,$sid,$nid    1,16754775,32,（类型,值,字节）
							$this -> pw ->send('灯已经关闭');
							$this -> record_time($action);
							$this -> record_info($action,0);
							$sql = "Update i_equipment Set switch = 0 , info = ".date("G")." Where user_id =".$this->user_id." AND equipment = '灯'";//将等待回复信息写入场景
							$this -> db ->db_update($sql);
							
							$sql = 'Update i_scenes Set scenes_type = 0 Where user_id ='.$this->user_id;//将等待回复信息写入场景
							$this -> db ->db_update($sql);
							return 1;
							break;
					case 90047:
							$this -> pw ->send('好的主人');
							$sql = 'Update i_scenes Set scenes_type = 0 Where user_id ='.$this->user_id;//将等待回复信息写入场景
							$this -> db ->db_update($sql);
							return 1;
							break;
					case 90048:
							$this -> pw ->send('好的主人');
							$sql = 'Update i_scenes Set scenes_type = 0 Where user_id ='.$this->user_id;//将等待回复信息写入场景
							$this -> db ->db_update($sql);
							return 1;
							break;	
					case 99001:
							$this -> pw ->send('主人，欢迎回家~（自动判断是否打开空调等电器并进行相应设置）');
							$sql = "Update i_equipment Set switch = 1";
							$this -> db ->db_update($sql);

							return 1;
							break;
					
					case 99002:
							$this -> pw ->send('主人再见~（自动断掉电视机等不必要设备的电源）');
							$sql = "Update i_equipment Set switch = 0";
							$this -> db ->db_update($sql);
	
							return 1;
							break;
							
					case 99007:
							$this -> pw ->send('主人晚安~（关闭电视等）');
							$sql = "Update i_equipment Set switch = 0";
							$this -> db ->db_update($sql);
							
							$sql = 'Update i_scenes Set scenes_type = 0 Where user_id ='.$this->user_id;//将等待回复信息写入场景
							$this -> db ->db_update($sql);
							return 1;
							break;		
			
				}
			}
			$this -> pw->send($str);
		}
		public function temperatureHumidity(){//温湿度
			
		$api_device_sql="SELECT sid,nid,data,note,status,regdate,lasttime FROM api_device WHERE user_id='".$this -> user_id."' and sid='1' and nid='2'";

			if (!$api_device_row = $this ->db -> db_select($api_device_sql))
			{
				$type=1;
				$user_id=$user_id;
				$sid="1";
				$nid="2";
				$data="0.0";
				$note="温湿度设备";
				$status=1;
				$regdate="";
				$lasttime="";		
					
				$api_device_sql_i="INSERT INTO api_device(type,user_id,sid,nid,data,note,status,regdate,lasttime) 
					VALUES ('".$type."', '".$user_id."', '".$sid."', '".$nid."', '".$data."', '".$note."', '".$status."', '".$regdate."', '".$lasttime."')";	
				$contentStr = $this ->db -> db_update($api_device_sql_i);
				return $contentStr;
			}
			else
			{
				$sid=sprintf("%03d",$api_device_row['sid']);
				$nid=sprintf("%03d",$api_device_row['nid']);
				$data=$api_device_row['data'];
				$status=$api_device_row['status'];
				$regdate=$api_device_row['regdate'];
				$lasttime=$api_device_row['lasttime'];
					
				$nowt=time()-60*2;
				if( strtotime($lasttime)<$nowt ){
					$contentStr = "设备异常";
					return $contentStr;				
				}
				else if( strpos($data,".") ){
					$arr = explode(".",$data);			
					$temperature=$arr[1];//温度数据
					$humidity=$arr[0];//湿度数据
					$contentStr = "当前温度：".$temperature."℃    \n当前湿度：".$humidity."%";
					return $contentStr;					
				}
			}//*/
			
		}
		public function infraredEmission($data,$sid,$nid,$user_id){//发射红外线(第一次重构未测试)  需打开http://121.42.136.49/tcp/tcpserver.php
					$type=1;
					$note="红外线设置";
					$status=0;
					$time="";

					//插入		
					$sql="INSERT INTO api_worklist(type,user_id,sid,nid,data,note,status,time) 
					VALUES ('".$type."', '".$user_id."', '".$sid."', '".$nid."', '".$data."', '".$note."', '".$status."', '".$time."')";	
					$this -> db -> db_update($sql);
					
					//更新设置最后时间
					$nowt=time();//当前时间 60*60是一个小时 
					$time=date("Y-m-d H:i:s",$nowt);		
					$sql="UPDATE api_device SET data='".$data."' WHERE user_id='".$user_id."' and sid='".(int)$sid."' and nid='".(int)$nid."'";//lasttime='".$time."',
					$this -> db -> db_update($sql);

					
					//获取apikey，处理tcp协议部份 
					//------------------------------------------------------
					$sql="SELECT apikey FROM api_member WHERE user_id='".$user_id."'";
					$row = $this -> db -> db_select($sql);
					$apikey=$row['apikey'];
					
						
					$tcpdata="mode=exe&";
					$tcpdata=$tcpdata."apikey=".$apikey."&";
					$tcpdata=$tcpdata."data={ck".(sprintf("%03d",$sid).sprintf("%03d",$nid).$data)."}";
				//	$url="http://121.42.136.49/tcp/tcpclient.php;";
				//	$tcptext = file_get_contents($url);
					$url="http://121.42.136.49/tcp/tcpclient.php?data=".urlencode($tcpdata);	
					$tcptext = file_get_contents($url);
					
					//$contentStr = $tcptext;
					//$this -> pw ->send($contentStr);
					//------------------------------------------------------
					return $tcptext;
					//mysql_close();
					//exit;/**/
		}
		public function record_time($action){
					//i_frequency
				$sql = 'Select frequency From i_frequency_time Where action = '.$action.' AND user_id ='.$this -> user_id ;
				$row = $this -> db ->db_select($sql);
				if(!$row){
					$sql = "INSERT INTO i_frequency_time(frequency,time,action,user_id)
							VALUES (1 ,'" .date("G")."', '".$action."' , '".($this -> user_id)."')";
							$this -> db ->db_update($sql);
				}else{
						$frequency = $row['frequency'];
						$sql = 'Update i_frequency_time  Set frequency = '.($frequency+1).' 
						Where time = '.date("G").' AND action = '.$action; //上传小时值
						//return $sql ;
						$this -> db ->db_update($sql);
				}
			
			//i_frequency_total
			
			$sql = 'Select frequency From i_frequency_time_total Where action = '.$action.' AND user_id ='.$this -> user_id ;
			$row = $this -> db ->db_select($sql);	
			if(!$row){
				$sql = "INSERT INTO i_frequency_time_total(frequency,action,user_id)
						VALUES (1 , '".$action."' , '".($this -> user_id)."')";
						$this -> db ->db_update($sql);
			}else{
					$frequency = $row['frequency'];
					$sql = 'Update i_frequency_time_total Set frequency = '.($frequency+1).' Where action = '.$action.' AND user_id ='.$this -> user_id; //上传小时值
					$this -> db ->db_update($sql); //上传小时值
			}
			
			
			
			
		}
		public function record_info($action,$info){
				$sql = 'Select frequency From i_frequency_info Where action = '.$action.' AND user_id ='.$this -> user_id.' AND info = '.$info;
				$row = $this -> db ->db_select($sql);
				if(!$row){
					$sql = "INSERT INTO i_frequency_info(frequency,info,action,user_id)
							VALUES (1 ,".$info.", '".$action."' , '".($this -> user_id)."')";
							$this -> db ->db_update($sql);
				}else{
						$frequency = $row['frequency'];
						$sql = 'Update i_frequency_info  Set frequency = '.($frequency+1).'  
						Where info = '.$info.' AND user_id ='.$this -> user_id.' AND action = '.$action; //上传小时值
						//return $sql ;
						
						$this -> db ->db_update($sql);
				}
			
				//i_frequency_time_total
				
				$sql = 'Select frequency From i_frequency_info_total Where action = '.$action.' AND user_id ='.$this -> user_id ;
				$row = $this -> db ->db_select($sql);	
				if(!$row){
					$sql = "INSERT INTO i_frequency_info_total(frequency,action,user_id)
							VALUES (1 , '".$action."' , '".($this -> user_id)."')";
							$this -> db ->db_update($sql);
				}else{
						$frequency = $row['frequency'];
						$sql = 'Update i_frequency_info_total Set frequency = '.($frequency+1).' Where action = '.$action.' AND user_id ='.$this -> user_id; //上传小时值
					$this -> db ->db_update($sql); //上传小时值
				}

		}
		public function __construct($db,$user_id){
			$this -> db = $db;
			$this -> user_id = $user_id;
		}
			
}
?>