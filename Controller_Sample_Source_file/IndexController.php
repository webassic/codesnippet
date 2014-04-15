<?php

class StudentCharts_IndexController extends Zend_Controller_Action
{

    public function init()
    {
    	// object created "Charts_BO_ChartsBO" that class. 
    	
        $this->obChartsBO  = new Charts_BO_ChartsBO;
    }

    public function indexAction()
    {
    	    	
    }
    
    public function registartionAction()
    {
    	if($this->getRequest()->isPost())
         {	
         	 //print_r($this->getRequest()->getPost());exit;
         	$boolInsertValue = $this->obChartsBO->registrationuser($this->getRequest()->getPost());
         	
         	echo $boolInsertValue;
         	exit;
         	
         }    	
    }
    
	public function loginAction()
    {
    	
        $delSuccess=$this->getRequest()->getParam('suucessdelete'); 
	    if($delSuccess!='')
	    {
         $this->view->suceessdelShow=$delSuccess;
	    }
	    
        $sendpassSuccess=$this->getRequest()->getParam('sendpass'); 
	    if($sendpassSuccess!='')
	    {
         $this->view->suceesssendpassShow=$sendpassSuccess;
	    }
    	
    	if($this->getRequest()->isPost())
         {
           
           $arrfetchValue = $this->obChartsBO->userdetails($this->getRequest()->getPost());
           
           print_r(json_encode($arrfetchValue));exit;
           
         }  	
    }
    
    
    
    public function ckeckemailAction()
     {
    	  	
    	if($this->getRequest()->isPost())
         {	
         
         	$boolInsertValue = $this->obChartsBO->loginUser($this->getRequest()->getPost());
         	echo $boolInsertValue;
         	exit;
         }    	
     }
    
    
    public function dashboardAction()
    {
      
      
       $delerror=$this->getRequest()->getParam('errordelete'); 
	    if($delerror!='')
	    {
          $this->view->deleteerrorShow=$delerror;
	    }
    }
    
    public function changepassAction()
    {
    	if($this->getRequest()->isPost())
         {
           
           $boolInsertPassValue = $this->obChartsBO->changepassword($this->getRequest()->getPost());
           echo $boolInsertPassValue;exit;
         }
    }
    
    public function deleteprofileAction()
    {
      
    	$boolDeleteAccount = $this->obChartsBO->deleteaccount();
        if($boolDeleteAccount==1)
        {
          $this->_redirect('/studentCharts/index/login/suucessdelete/1');
        }
        if($boolDeleteAccount==0)
        {
          $this->_redirect('/studentCharts/index/dashboard/errordelete/1');
        }
    }
    public function logoutAction()
    
  	{
  		$sessionTitle = new Zend_Session_Namespace('emailId');
      	$sessionTitle->emailId="";
      	
      	$this->_redirect('/studentCharts/index/login');
      	exit;
  	
    }
    
    public function userdetailsAction()
    {
        if($this->getRequest()->isPost())
         {
           
           $arrfetchValue = $this->obChartsBO->userdetails($this->getRequest()->getPost());
           print_r($arrfetchValue);exit;
           
           
         } 
    }
    
    public function saveinfouserAction()
    {
            $boolInsertValue = $this->obChartsBO->saveinfouser($this->getRequest()->getPost());
         	
         	echo $boolInsertValue;
         	exit;
    	
    	
    }

    public function forgotpassAction()
    {
    	if($this->getRequest()->isPost())
         {    
    	   
         	$boolInsertValue = $this->obChartsBO->forgotpass($this->getRequest()->getPost());
         	echo $boolInsertValue;
         	exit;
         }
    
   }
   
   public function userprofileAction()
   {
     
   	 $arruserprofile = $this->obChartsBO->Userprofile();
   	 
     $this->view->showinfo=$arruserprofile;
    
   }
   public function chatroomAction()
   {
   	$arrCountryList = $this->obChartsBO->getAllCountry();
   	  	
   	$countryCount = count($arrCountryList);
   	$arrStateList = '';
   	$arrStateList = array();
   	for($i=0;$i<$countryCount;$i++)
   	{
   		$arrStateList[] = $this->obChartsBO->getAllState($arrCountryList[$i]['country']);
   	}   	
   	
   	$stateCount = count($arrStateList);
   	$arrCityList = '';
   	$arrCityList = array();
   	for($j=0;$j<$stateCount;$j++)
   	{
   		$p = count($arrStateList[$j]);
   		$arrCityListOne = '';
   		$arrCityListOne = array();
   		for($k=0;$k<$p;$k++)
   		{ 
   			$arrCityListOne[] = $this->obChartsBO->getAllCity($arrStateList[$j][$k]['state'],$arrCountryList[$j]['country']);
   		}
   		
   		$arrCityList[] = $arrCityListOne;
   	}
   	
   	
   	$stateCount = count($arrStateList);
   	$arrUnivercityList = '';
   	$arrUnivercityList = array();
   	for($j=0;$j<$stateCount;$j++)
   	{
   		$p = count($arrStateList[$j]);
   		$arrUnivercityListOne = '';
   		$arrUnivercityListOne = array();
   		for($k=0;$k<$p;$k++)
   		{ 
   			$q = count($arrCityList[$j][$k]);
   			$arrUnivercityListTwo = '';
   			$arrUnivercityListTwo = array();
   			
   			for($l=0;$l<$q;$l++)
   			{
   				$arrUnivercityListTwo[] = $this->obChartsBO->getAllUnivercity($arrCityList[$j][$k][$l]['city'],$arrStateList[$j][$k]['state'],$arrCountryList[$j]['country']);
   			}
   			
   			$arrUnivercityListOne[] = $arrUnivercityListTwo;
   		}
   		
   		$arrUnivercityList[] = $arrUnivercityListOne;
   	}
   	  	
   
   	$this->view->arrCountryList = $arrCountryList;
   	//$this->view->countryCount = count($arrCountryList);
   	//echo "------------------------------------------------------------------------\n";
   	//print_r($arrStateList);
   	$this->view->arrStateList = $arrStateList;
   	//$this->view->stateCount = count($arrStateList);
   	
   //echo "------------------------------------------------------------------------\n";
   //print_r($arrCityList);
   	$this->view->arrCityList=$arrCityList;
   //$this->view->cityCount = count($arrCityList);
   //echo "------------------------------------------------------------------------\n";
   //print_r($arrUnivercityList);
   	$this->view->arrUnivercityList=$arrUnivercityList;
   //$this->view->stateCount = count($arrUnivercityList);
   	//exit;
   	
   	
   $arrmsgValue = $this->obChartsBO->selectchatroommsg();
   $this->view->arrshowmsg=$arrmsgValue;
   $this->view->arrmsgCount=count($arrmsgValue);
   	
       if($this->getRequest()->isPost())
         {    
    	    //print_r($this->getRequest()->getPost());exit;
         	$arrmsgValue = $this->obChartsBO->insertchatroommsg($this->getRequest()->getPost());
            $this->view->arrshowmsg=$arrmsgValue;
            $this->view->arrmsgCount=count($arrmsgValue);    
         	print_r(json_encode($arrmsgValue));exit;
         }
   }
	
	
}
