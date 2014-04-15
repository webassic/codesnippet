<?php
class Multitenancy_MultitenancyUtility 
{
public function createtables($strDentistId){

        /**
         * Getting the handle to configuration file of Zend Framework i.e. application.ini
         */
        $bootstrap= Zend_Controller_Front::getInstance()->getParam('bootstrap');
        
        /**
         * Getting the options of the Multitenanacy database
         */
        $config = $bootstrap->getOption('doctrine');
        
        /**
         * Creating a connection to the source database
         */
        $sourceconn = mysql_connect($config['sourcedsnhostname'], $config['sourcedsnusername'], $config['sourcedsnpassword']);
        $selectedDatabase = mysql_select_db($config['sourcedsndatabase'], $sourceconn);
        
        /**
         * Fetching the list of all the tables in the template database
         */
        $sql="show tables;";
        $result= mysql_query($sql,$sourceconn);
        
        
        /**
         * Fetching Each table, generating its create query and creating the same in the another table
         */
        $arrOfTables = array();
        if($result)
        {

            while($row= mysql_fetch_row($result))
            {
                
                $arrOfTables[] = $this->getCreateQueryForTable($row[0],$strDentistId,$sourceconn);
               
            }
            /**
             * After fetching the create query for the table, creating the same in the new database
             */
            $this->createUserTablesByDump($arrOfTables);
            $this->insertTsreatmentName($strDentistId);
            $this->insertGeneralSettings($strDentistId);
            $this->insertConsent($strDentistId);
            
        }
        else
        {
            echo "/* no tables in $mysql_database */\n";
        }
        mysql_free_result($result);
		return true;
       
    }
/**
 * Fetches the create query for each table and appends ID and Prefix to each table name
 * @param type $strTableName
 * @param type $strDentistId
 * @param type $strDentistPrefix
 * @param type $connection
 * @return type 
 */
private function getCreateQueryForTable($strTableName, $strDentistId,$connection){
      $sql="show create table `$strTableName`; ";

        $result=mysql_query($sql,$connection);

        $rowArr = array();
        while ($row = mysql_fetch_assoc($result))
         {
          if($row['Table']!="city" && $row['Table']!="country" && $row['Table']!="language" && $row['Table']!="userlanguage")
          {
          	
            $user_tb_name = $strDentistId.'_'.$row['Table'];
            $data = $row['Create Table'];
            $data = explode("`", $data);
            $data['1'] = $user_tb_name;
            $row = implode("`", $data);
            // --- Assign query to the arr to create tables for the user ---
            $rowArr = $row;
          }  
        }

        mysql_free_result($result);

        return $rowArr;
        
  
} 
    /**
     * Creates the table in the destination database 
     * @param type $data
     * @param type $connection 
     */
    private function createUserTablesByDump($data){        
      
       /**
         * Getting the handle to configuration file of Zend Framework i.e. application.ini
         */
        $bootstrap= Zend_Controller_Front::getInstance()->getParam('bootstrap');
        
        /**
         * Getting the options of the Multitenanacy database
         */
        $config = $bootstrap->getOption('doctrine');
        
        
        /**
         * Creating a connection to the destination database
         */
        $destinationconn = mysql_connect($config['destinationdsnhostname'], $config['destinationdsnusername'], $config['destinationdsnpassword']);
        $selectedDatabase = mysql_select_db($config['destinationdsndatabase'], $destinationconn);
        
        foreach($data as $key => $val){
         $result = mysql_query($val,$destinationconn);
        }
    }
    
    function insertTsreatmentName($strDentistId)
    {
    	 /**
         * Getting the handle to configuration file of Zend Framework i.e. application.ini
         */
        $bootstrap= Zend_Controller_Front::getInstance()->getParam('bootstrap');
        
    	/**
         * Getting the options of the Multitenanacy database
         */
        $config = $bootstrap->getOption('doctrine');
         /**
         * Creating a connection to the destination database
         */
    	$sourceconn = mysql_connect($config['sourcedsnhostname'], $config['sourcedsnusername'], $config['sourcedsnpassword']);
        $selectedDatabase = mysql_select_db($config['sourcedsndatabase'], $sourceconn);
        
        /***
         * fetch data from source database 
         */
    	$tableName=$strDentistId.'_treatment';
    	
    	$qry='SELECT * FROM treatment';
    	$result1 = mysql_query($qry,$sourceconn);
    	while($row[] = mysql_fetch_assoc($result1)){};
    	$row=$this->filterArray($row);//print_r($row);exit;
    	$intArrLength=count($row);
    	
    	/**
         * Creating a connection to the destination database
         */
        $destinationconn = mysql_connect($config['destinationdsnhostname'], $config['destinationdsnusername'], $config['destinationdsnpassword']);
        $selectedDatabase = mysql_select_db($config['destinationdsndatabase'], $destinationconn);
              
    	for($i=0;$i<$intArrLength;$i++)
    	{
    	 $insertQury="INSERT INTO ".$tableName."(TreatmentName,TreatmentCost,Comments,CreationDate,EditedUser,EditedTaskName,EditedDatetime) VALUES
    	           (   	            
    	            '".$row[$i]['TreatmentName']."',    	            
    	            '".$row[$i]['TreatmentCost']."',
    	            '".$row[$i]['Comments']."',
    	            '".$row[$i]['CreationDate']."',
    	            '".$row[$i]['EditedUser']."',
    	            '".$row[$i]['EditedTaskName']."',
    	            '".$row[$i]['EditedDatetime']."'  	            
    	          )";
        
    	 $result = mysql_query($insertQury,$destinationconn);
    	}
    	return true;
    	
    }
    
    
    function insertGeneralSettings($strDentistId)
    {
    	 /**
         * Getting the handle to configuration file of Zend Framework i.e. application.ini
         */
        $bootstrap= Zend_Controller_Front::getInstance()->getParam('bootstrap');
        
    	/**
         * Getting the options of the Multitenanacy database
         */
        $config = $bootstrap->getOption('doctrine');
         /**
         * Creating a connection to the destination database
         */
    	$sourceconn = mysql_connect($config['sourcedsnhostname'], $config['sourcedsnusername'], $config['sourcedsnpassword']);
        $selectedDatabase = mysql_select_db($config['sourcedsndatabase'], $sourceconn);
        
        /***
         * fetch data from source database 
         */
    	$tableName=$strDentistId.'_generalsettings';
    	
    	$qry='SELECT * FROM generalsettings';
    	$result1 = mysql_query($qry,$sourceconn);
    	while($row[] = mysql_fetch_assoc($result1)){};
    	$row=$this->filterArray($row);//print_r($row);exit;
    	$intArrLength=count($row);
    	
    	/**
         * Creating a connection to the destination database
         */
        $destinationconn = mysql_connect($config['destinationdsnhostname'], $config['destinationdsnusername'], $config['destinationdsnpassword']);
        $selectedDatabase = mysql_select_db($config['destinationdsndatabase'], $destinationconn);
              
    	for($i=0;$i<$intArrLength;$i++)
    	{
    	 $insertQury="INSERT INTO ".$tableName."(ListName,ItemName,ItemOrder,Comments,CreationDate,EditedUser,EditedTaskName,EditedDatetime) VALUES
    	           (
    	            '".$row[$i]['ListName']."',
    	            '".$row[$i]['ItemName']."',    	            
    	            '".$row[$i]['ItemOrder']."',
    	            '".$row[$i]['Comments']."',
    	            '".$row[$i]['CreationDate']."',
    	            '".$row[$i]['EditedUser']."',
    	            '".$row[$i]['EditedTaskName']."',
    	            '".$row[$i]['EditedDatetime']."'  	            
    	          )";
        
    	 $result = mysql_query($insertQury,$destinationconn);
    	}
    	return true;
    	
    }
    
    
     function insertConsent($strDentistId)
    {
    	 /**
         * Getting the handle to configuration file of Zend Framework i.e. application.ini
         */
        $bootstrap= Zend_Controller_Front::getInstance()->getParam('bootstrap');
        
    	/**
         * Getting the options of the Multitenanacy database
         */
        $config = $bootstrap->getOption('doctrine');
         /**
         * Creating a connection to the destination database
         */
    	$sourceconn = mysql_connect($config['sourcedsnhostname'], $config['sourcedsnusername'], $config['sourcedsnpassword']);
        $selectedDatabase = mysql_select_db($config['sourcedsndatabase'], $sourceconn);
        
        /***
         * fetch data from source database 
         */
    	$tableName=$strDentistId.'_consent';
    	
    	$qry='SELECT * FROM consent';
    	$result1 = mysql_query($qry,$sourceconn);
    	while($row[] = mysql_fetch_assoc($result1)){};
    	$row=$this->filterArray($row);//print_r($row);exit;
    	$intArrLength=count($row);
    	
    	/**
         * Creating a connection to the destination database
         */
        $destinationconn = mysql_connect($config['destinationdsnhostname'], $config['destinationdsnusername'], $config['destinationdsnpassword']);
        $selectedDatabase = mysql_select_db($config['destinationdsndatabase'], $destinationconn);
              
    	for($i=0;$i<$intArrLength;$i++)
    	{
    	 $insertQury="INSERT INTO ".$tableName."(ConsentType,ConsentDesc,Comments,CreationDate,EditedUser,EditedTaskName,EditedDatetime) VALUES
    	           (
    	            '".$row[$i]['ConsentType']."',
    	            '".$row[$i]['ConsentDesc']."',           
    	            '".$row[$i]['Comments']."',
    	            '".$row[$i]['CreationDate']."',
    	            '".$row[$i]['EditedUser']."',
    	            '".$row[$i]['EditedTaskName']."',
    	            '".$row[$i]['EditedDatetime']."'  	            
    	          )";
        
    	 $result = mysql_query($insertQury,$destinationconn);
    	}
    	return true;
    	
    }
    
    
    
    
    function filterArray($arr)
	  {
			$i=0;
			$temp =array();
			$intCntArray =count($arr);
			for($j=0;$j<$intCntArray;$j++)
			{
				if($arr[$j]!="")
				{
					$temp[$i] = $arr[$j];
					$i++;
				}
			}
			return $temp;
		}
 
}
?>