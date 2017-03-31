<?php
	
	class Polling{
		public $db;
		public $user_id;
		public function Polling(){
		
		
		
		$sql = 'Select Next_time From i_scheduled_task where  user_id ='.($this->user_id).' AND action = 99999';
		$row = $this -> db ->db_select($sql);
			if($row){		
			//	return $row ;
				if($row['Next_time']<time()){
					
				//	更新轮询'next'值
					
					$sql = 'Update i_scheduled_task Set Next_time ='.(time()+10) .' ,Last_time = '.time().' Where user_id ='.$this->user_id.' AND action = 99999';
					$this -> db ->db_update($sql);
				
					
				//	开始轮询操作
					//检查任务表
					$sql = "SELECT action FROM i_scheduled_task WHERE user_id =  '".$this -> user_id."' AND Next_time <".(time());
					$row = $this -> db ->db_select($sql);
					if($row){
						
						switch ($row['action']){
							case 99001:
							
								$this -> implement($row['action'],3600);
								return '90001';
								break;
							
					/*		case 10104:
								
								if(date("G")==8)
							//	$sql = 'Update i_scheduled_task Set Next_time ='.(time()+90) .' ,Last_time = '.time().' Where user_id ='.$this->user_id.' AND action = '.$row['action'];
							//	$this -> db ->db_update($sql);
								break;
					*/	
						}

					}

				//	检查温度（设定空调）
				
					$sql = "SELECT data FROM api_device WHERE user_id =  '".$this -> user_id."' AND sid = 1 AND nid = 2";
					$row = $this -> db ->db_select($sql);
					$data=$row['data'];
					$arr = explode(".",$data);			
					$temperature=$arr[1];//温度数据
					$humidity=$arr[0];//湿度数据
					$sql = "SELECT info,switch,time FROM i_equipment WHERE user_id =  '".$this -> user_id."' AND equipment = '空调'";
					$row = $this -> db ->db_select($sql);
					if($row){
						$sql = 'Update i_equipment Set time ='.(time()) .' Where user_id ='.$this->user_id." AND equipment = '空调'";
						$this -> db ->db_update($sql);
						if($row['time']<(time()-600)){
							if(!$row['switch']){
								if($temperature<18){//温度过低
									return 30021;
								}
								if($temperature>25){//温度过高
								
									return 30022;
								}
							}else{
								if($temperature<18){//温度过低
									return 30026;
								}
								if($temperature>25){//温度过高
								
									return 30027;
								}
							}
						}
					}else{
						$sql = "INSERT INTO i_equipment(time,user_id,equipment)
						VALUES ('".time()."' , '".($this -> user_id)."' , '空调')";
						$this -> db ->db_update($sql);
					}
					
					
					
					
					$sql = "SELECT info,switch,time FROM i_equipment WHERE user_id =  '".$this -> user_id."' AND equipment = '灯'";
					$row = $this -> db ->db_select($sql);
					if($row){
						$sql = 'Update i_equipment Set time ='.(time()) .' Where user_id ='.$this->user_id." AND equipment = '灯'";
						$this -> db ->db_update($sql);
						if($row['time']<(time()-30000)){
							if(date("G")>8&&date("G")<16&&$row['switch']){//天亮关灯
									return 30041;
							}elseif(date("G")<21&&date("G")>18&&(!$row['switch'])){//天黑开灯
									return 30042;
							}
						}
					}else{
						$sql = "INSERT INTO i_equipment(time,user_id,equipment)
						VALUES (".time()." , ".$this->user_id.",'灯')";
						$this -> db ->db_update($sql);
					}

					/*检查时间（开关灯）（推送天气）*/
					if(date("G")==8){

						$sql = "SELECT Next_time FROM i_scheduled_task WHERE user_id =  '".$this -> user_id."'AND action = 20007";
						$row = $this -> db ->db_select($sql);
						if($row){
							if($row['Next_time']<time()){
								$sql = 'Update i_scheduled_task Set Next_time ='.(time()+75000) .' ,Last_time = '.time().' Where user_id ='.$this->user_id.' AND action = 20007';
								$this -> db ->db_update($sql);
								return  '20007';

							}

						}
						else{
							$sql = "INSERT INTO i_scheduled_task(action,user_id,Next_time)
							VALUES ('".(20007)."' , '".($this -> user_id)."', '".time()."')";
							$this -> db ->db_update($sql);
						}
					}
					
					//提醒睡觉
					if(date("G")==22){

							$sql = "SELECT scenes_time FROM i_scenes WHERE user_id =  ".$this -> user_id;
							$row = $this -> db -> db_select($sql);
							if($row['scenes_time']>time()-600){
								$sql = "SELECT Next_time FROM i_scheduled_task WHERE user_id =  '".$this -> user_id."'AND action = 20008";
								$row = $this -> db ->db_select($sql);
								if($row['Next_time']<time()){
									$sql = 'Update i_scheduled_task Set Next_time ='.(time()+75000) .' ,Last_time = '.time().' Where user_id ='.$this->user_id.' AND action = 20008';
									$this -> db ->db_update($sql);
									return  '20008';
									
								}else{
									$sql = "INSERT INTO i_scheduled_task(action,user_id,Next_time)
									VALUES ('".(20008)."' , '".($this -> user_id)."', '".time()."')";
									$this -> db ->db_update($sql);
									
								}
							}
						}
					
					
					return 0;
				}
			}else{
				$sql = "INSERT INTO i_scheduled_task(action,user_id,Next_time)
						VALUES ('".(99999)."' , '".($this -> user_id)."', '".(time()+60)."')";
						$this -> db ->db_update($sql);
			}
			
		}
		public function implement($action,$delay)
		{
			$sql = 'Update i_scheduled_task Set Next_time ='.(time()+$delay) .' ,Last_time = '.time().' Where user_id ='.$this->user_id.' AND action = '.$action;
			$this -> db ->db_update($sql);
		}
		
		
		
		
		
	
		public function __construct($db,$user_id){
			$this -> db = $db;
			$this -> user_id = $user_id;
		}

	}
?>