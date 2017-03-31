<?php

class retrieval{//对象检索
    private $db;
	private $user_id;
	private	$type;
	private $pw;
	
	public function retrieval($word,$action1,$pw){
	//	$this -> pw -> send($action1);
		$this -> pw = $pw;
		
		if($action1>90000){
				$returnData = $this -> repart_r($word);
		//	$i_keyword_confirm_sql = "SELECT data FROM i_keyword_confirm  WHERE keyword = '".$word."'";
		//	$i_keyword_confirm_row = $this ->db ->db_select($i_keyword_confirm_sql);
			
			if($returnData){
				$action2 = $action1+$returnData;
				if($action2>$action1){
					return $action2;
				}
				
			}
			$action1 = 0;
		}
		$repart_t_sql = $this -> Scenes($word);
		//$this -> pw -> send($repart_t_sql);
		$action = $this -> repart($word,$action1);
		if(0 < $action && $action < 10000&&$action!=$action1){
			$action = $this -> retrieval($word,$action);
		//	$this -> pw -> send($word);
			return $action;
		}
		else if($action1 == 0&&$action == 0){
			$i_keyword_node_sql = 'SELECT node_0,node_type FROM i_scenes  WHERE user_id = '.$this -> user_id;
			$i_keyword_node_row = $this ->db ->db_select($i_keyword_node_sql);
			
			//此处加入超时判断
			
		//	$pw -> send($row['obj_type']!=1||$this -> type == 1);//测试代码
			if($i_keyword_node_row['node_type']==1){
				$word = $word.$i_keyword_node_row['node_0'];
			//	$this -> pw -> send($word);
				//$this -> type = 0;
				$i_keyword_node_sql_u='UPDATE i_scenes SET node_type = 0 WHERE user_id = '.$this -> user_id;
				$this ->db -> db_update($i_keyword_node_sql_u);
				$action = $this -> repart($word,$action1);
				$action1 = 1;
				$action = $this -> retrieval($word,$action);
			//	$this -> pw -> send($word);//测试代码
				
			}
			return $action;
		}
		else{
			return $action;
		}
		
	}
	
	public function Scenes($word){
		$i_keyword_node_sql_u="UPDATE i_scenes SET node_type = 0 , scenes_data = ' ' WHERE user_id = ".$this -> user_id;
		$this ->db -> db_update($i_keyword_node_sql_u);
		//$this -> pw -> send($i_keyword_node_sql_u);
		$repart_t_sql1=$this -> repart_t($word,1);
		$repart_t_sql2=$this -> repart_t($word,2);
		$repart_t_sql2=$this -> repart_t($word,3);
	}
	public function repart($word,$action){
			
           $long_max = mb_strlen($word,'utf-8');


			if($long_max>6)
			{
				$strlong = 6;
			}
			else{
				$strlong = $long_max;
			}
			$i=$long_max-$strlong;
            for (;$i>=0;$i--){//当前位置-$add
				
				$k=0;
		  
				$i_cach = $i;
			   for ($j=$strlong+1;$j>0;$j--){//读取长度
                   $keyword = mb_substr($word,$i,$j,'utf-8');
				   $keywords = $keywords."\n".$keyword;
				 //  $this -> pw -> send($keyword);
				   if(0 < $action && $action < 1000)
					{
						$i_keyword_node_sql="SELECT action,type FROM i_keyword_node WHERE  keyword = '".$keyword."' AND last = '".$action."' AND Grade =1";
					}
					else if(1000 <= $action && $action < 10000)
					{
						$i_keyword_node_sql="SELECT action,type FROM i_keyword_node WHERE  keyword = '".$keyword."'AND last = '".$action."' AND Grade =2";
					}
					else
					{
						$i_keyword_node_sql="SELECT action,type FROM i_keyword_node WHERE  keyword = '".$keyword."' AND Grade =0";
					}
					
                  if($i_keyword_node_row = $this ->db ->db_select($i_keyword_node_sql)){

						$k=$j;

						if($k>0){		
							$i=$i-$k;
							if($i<0){					
								$strlong+=$i;	
							}
							
							$actionT=$i_keyword_node_row['action'];
							$keyword_type = $i_keyword_node_row['type'];
						 	if(0 < $actionT && $actionT < 1000||$keyword_type == 1){
									$i_keyword_node_sql_u="UPDATE i_scenes SET node_0 = '".$keyword."' ,action = '".$actionT."',node_type = '".$row['type']."' WHERE user_id = '".$this -> user_id."'";	
								}
							else if(1000 <= $actionT && $actionT < 10000){
									$i_keyword_node_sql_u="UPDATE i_scenes SET node_1 = '".$keyword."' ,action = '".$actionT."' WHERE user_id = '".$this -> user_id."'";
								}
							elseif($actionT >= 10000){
									$i_keyword_node_sql_u="UPDATE i_scenes SET node_2 = '".$keyword."' ,action = '".$actionT."' WHERE user_id = '".$this -> user_id."'";
								}
							$this ->db -> db_update($i_keyword_node_sql_u);
							
						//	$this -> pw ->send($keywords);
							return $i_keyword_node_row['action'];
							
							break;
							
						}
				    }
				   $i++;
                }
				$i = $i_cach;
				if($i<=0&&$strlong>0){
					$i=1;
					$strlong--;
					if($k>0){
						$strlong=$strlong-$k+1;
					}
				}
            }
			
			return $action;

	}
	public function repart_t($word,$sign){
			
		//	$keyword = '今天';
		//	$sign = 2;
		//	$i_keyword_china_city_list_sql="SELECT data FROM i_keyword_info WHERE  keyword = '".$keyword."'AND sign = ".$sign;
		//	return $i_keyword_china_city_list_sql;
            $long_max = mb_strlen($word,'utf-8');

			if($long_max>6)
			{
				$strlong = 6;
			}
			else{
				$strlong = $long_max-1;
			}
			$i=$long_max-$strlong;
            for (;$i>=0;$i--){//当前位置-$add
				
				$k=0;
		  
				$i_cach = $i;
			    for ($j=$strlong+1;$j>0;$j--){//读取长度
					$keyword = mb_substr($word,$i,$j,'utf-8');
					
					//$keywords = $keywords."\n".$keyword;
					
					if($sign == 1){
						$repart_t_sql="SELECT cityZh FROM i_keyword_china_city_list WHERE  cityZh = '".$keyword."'";
					}else{
						$repart_t_sql="SELECT data FROM i_keyword_info WHERE  keyword = '".$keyword."'AND sign = ".$sign;
					}
					
					$repart_t_row = $this ->db ->db_select($repart_t_sql);
					
                   if($repart_t_row = $this ->db ->db_select($repart_t_sql)){

						$k=$j;
						
						if($k>0){		
							$i=$i-$k;
							if($i<0){					
								$strlong+=$i;	
							}
							$i_cach = $i_cach-$k+1;
					//		$action = $row['action'];
							
							if($sign == 1){
									$i_keyword_node_sql_u="UPDATE i_scenes SET node_type = 1 ,node_site = '".$keyword."' WHERE user_id = ".$this -> user_id;	
									//$this -> type = 1;
								}
							else if($sign == 2){
									$date_data = $repart_t_row["data"];
									$i_keyword_node_sql_u="UPDATE i_scenes SET node_type = 1 , node_date = '".$keyword."' ,node_date_data = ".$date_data.' WHERE user_id = '.$this -> user_id;
									//$this -> type = 1;
								}
							else if($sign == 3){
									$i_keyword_node_sql_u="UPDATE i_scenes SET node_type = 1 , scenes_data = ".$repart_t_row["data"].' WHERE user_id = '.$this -> user_id;
									//$this -> type = 1;
								}
							$this ->db -> db_update($i_keyword_node_sql_u);
						//	$this ->pw->send($i_keyword_node_sql_u);
							
							$this -> pw ->send($keywords);
							break;
							
						}
				    }/**/
				   $i++;
                }
				$i = $i_cach;
				if($i<=0&&$strlong>0){
					$i=1;
					$strlong--;
					if($k>0){
						$strlong=$strlong-$k+1;
					}
				}
            }

	}
	public function repart_r($word){
			
		//	$keyword = '今天';
		//	$sign = 2;
		//	$i_keyword_china_city_list_sql="SELECT data FROM i_keyword_info WHERE  keyword = '".$keyword."'AND sign = ".$sign;
		//	return $i_keyword_china_city_list_sql;
            $long_max = mb_strlen($word,'utf-8');

			if($long_max>6)
			{
				$strlong = 6;
			}
			else{
				$strlong = $long_max-1;
			}
			$i=$long_max-$strlong;
            for (;$i>=0;$i--){//当前位置-$add
				
				$k=0;
		  
				$i_cach = $i;
			    for ($j=$strlong+1;$j>0;$j--){//读取长度
					$keyword = mb_substr($word,$i,$j,'utf-8');

					$sql="SELECT data FROM i_keyword_confirm WHERE  keyword = '".$keyword."'";
					
					$row = $this ->db ->db_select($sql);
					
                   if($row = $this ->db ->db_select($sql)){

						$k=$j;
						
						if($k>0){		
							$i=$i-$k;
							if($i<0){					
								$strlong+=$i;	
							}
							$i_cach = $i_cach-$k+1;
					//		$action = $row['action'];
							$this -> pw ->send($keywords);
							return $row['data'];
				
							
						}
				    }/**/
				   $i++;
                }
				$i = $i_cach;
				if($i<=0&&$strlong>0){
					$i=1;
					$strlong--;
					if($k>0){
						$strlong=$strlong-$k+1;
					}
				}
            }

	}
	public function user_id($user_id,$pw){
		$this -> user_id = $user_id;
		//$this -> type = $type;
		$this ->pw = $pw;
	}
	
    public function __construct($db){
        $this -> db = $db;
    }
}
	
?>