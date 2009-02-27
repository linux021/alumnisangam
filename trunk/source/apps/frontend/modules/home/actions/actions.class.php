<?php

/**
 * home actions.
 *
 * @package    shopper
 * @subpackage home
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class homeActions extends sfActions
{
	/**
	 * Executes index action
	 *
	 */

	public function executeIndex()
	{
		$c = new Criteria();
		$branches = BranchPeer::doSelect($c);
		$options = array();
		$options[] = 'Select Department';
		foreach($branches as $branch)
		{
			$options[$branch->getId()] = $branch->getName();
		}
		$this->options = $options;
	}
	public function executeAdmin()
	{
		if($this->getUser()->hasCredential('admin')){
			return $this->forward('home', 'adminmenu');
		}
	}
	public function executeError404()
	{

	}
	public function executeSecure()
	{

	}

	public function executeAdminmenu()
	{

	}
	public function executeLogin()
	{
		$username=$this->getRequestParameter('username');
		$password=$this->getRequestParameter('password');
		$c = new Criteria();
		$c->add(UserPeer::USERNAME, $username);
		$user = UserPeer::doSelectOne($c);
		if($user)
		{
			$islocked=$user->getIslocked();
			if($islocked=="0")
			{
				$salt = md5("I am Indian.");
				if(sha1($salt.$password) == $user->getPassword())
				{
		//			$userroles=$user->getUserroles();
		//			foreach($userroles as $r){
		//				$this->getUser()->addCredential($r->getRole()->getName());
		//			}
		//		if($password == $user->getPassword())
		//		{
					$c = new Criteria();
					$c->addJoin(UserPeer::ID, UserrolePeer::USER_ID);
					$c->addJoin(UserrolePeer::ROLE_ID, RolePeer::ID);
					$c->add(UserPeer::USERNAME, $username);
					
					$roles = RolePeer::doSelect($c);
					foreach($roles as $role)
					{
						$this->getUser()->addCredential($role->getName());
					}
					$this->getUser()->setAuthenticated(true);
					$this->getUser()->setAttribute('username',$user->getUsername());
					
					$this->getUser()->setAttribute('userid', $user->getId());
					if($this->getUser()->hasCredential('admin'))
					{
						return $this->redirect('home/adminmenu');
					}
					else
					{
						return $this->redirect('personal/show');
					}
		//		}
		//		else
		//		{
		//			$this->setFlash('login', 'Invalid username or password.');
		//			return $this->redirect('home/admin');
		//		}
				}
				else
				{
					$this->setFlash('login', 'Invalid username or password.');
					return $this->redirect('home/admin');
				}
			}
			else
			{
				$this->setFlash('login', 'Username is not Enabled.');
				return $this->redirect('home/admin');
			}
		}
		else
		{
			$this->setFlash('login', 'Username is not Enabled.');
			return $this->redirect('home/admin');
		}
	}
	public function executeLogout()
	{
		$this->getUser()->setAuthenticated();
		$this->getUser()->clearCredentials();
		$this->getUser()->getAttributeHolder()->remove('username');
		$this->redirect('home/admin');
	}
	public function executeAccessdenied()
	{


	}
	
	public function executeRegsearch()
	{
		//$name = $this->getRequestParameter('name');
		$year = $this->getRequestParameter('year');
		//$enrol = $this->getRequestParameter('enrol');
		$branch = $this->getRequestParameter('branch');
		
		$c = new Criteria();
		$c->add(UserPeer::ISLOCKED, '1');
		/*if($name)
		{
			$c->addJoin(UserPeer::ID, PersonalPeer::USER_ID);
			$c1 = $c->getNewCriterion(PersonalPeer::FIRSTNAME, $name);
			$c1->addOr($c->getNewCriterion(PersonalPeer::MIDDLENAME, $name));
			$c1->addOr($c->getNewCriterion(PersonalPeer::LASTNAME, $name));
			$c->add($c1);
		}*/
		if($year)
		{
			$c->add(UserPeer::GRADUATIONYEAR, $year);
		}
	/*	if($enrol)
		{
			$c->add(UserPeer::ENROLMENT, $enrol);
		}*/
		if($branch)
		{
			$c->addJoin(UserPeer::BRANCH_ID, BranchPeer::ID);
			$c->add(BranchPeer::ID, $branch);
		}
		
		$this->regusers = UserPeer::doSelect($c);
	}

	public function executeRegverify()
	{
		$userid = $this->getRequestParameter('regradio');
		$c = new Criteria();
		$c->add(PersonalPeer::USER_ID, $userid);
		$this->personal = PersonalPeer::doSelectOne($c);
	}
	
	public function executeRegmail()
	{
		$userid = $this->getRequestParameter('userid');
		$roll = $this->getRequestParameter('roll');
		$hawa = $this->getRequestParameter('hawa');
		$city = $this->getRequestParameter('city');
		$hod = $this->getRequestParameter('hod');
		$director = $this->getRequestParameter('director');
		$teacher = $this->getRequestParameter('favteacher');
		$lanka = $this->getRequestParameter('favlankashop');
		$email = $this->getRequestParameter('email');
		
		$user = UserPeer::retrieveByPK($userid);
		if($user)
		{
			$username = $user->getUsername();
			$c = new Criteria();
			$c->add(PersonalPeer::USER_ID, $userid);
			$personal = PersonalPeer::doSelectOne($c);
			$name = $personal->getFirstname()." ".$personal->getMiddlename()." ".$personal->getLastname();
			
			$user->setIslocked('2');
			$user->setPassword($email);
			$user->save();
		}		
		$sendermail = sfConfig::get('app_from_mail');
		$sendername = sfConfig::get('app_from_name');
		$to = sfConfig::get('app_to_adminmail');
		$subject = "Registration request for ITBHU Global Org";
		$body='
  Hi ,
 
  I want to connect to ITBHU Global. My verification information is: 

';
		$body=$body.'Roll Number :            '.$roll.'
';
		$body=$body.'HAWA :                   '.$hawa.'
';
		$body=$body.'City :                   '.$city.'
';
		$body=$body.'HoD :                    '.$hod.'
';
		$body=$body.'Director :               '.$director.'
';
		$body=$body.'Favourite Teacher :      '.$teacher.'
';
		$body=$body.'Favuorite Lanka Shop :   '.$lanka.'
';
		$body=$body.'My Email :               '.$email.'
';		
		$body=$body.'Username I am claiming : '.$username.'

';
		$body=$body.'Thanks,';
		$body=$body.'
'.$name;
		
		
		$mail = myUtility::sendmail($sendermail, $sendername, $sendermail, $sendername, $sendermail, $to, $subject, $body);
		
		
		$sendermail = sfConfig::get('app_from_mail');
		$sendername = sfConfig::get('app_from_name');
		$to = $email;
		$subject = "Registration request for ITBHU Global Org";
		$body ='
		Dear '.$name.',
		
		Thank you for your connect request. We\'ll get back to you shortly.	
		
		
		Admin,
		ITBHU Global
		';
		$mail = myUtility::sendmail($sendermail, $sendername, $sendermail, $sendername, $sendermail, $to, $subject, $body);
		
	}

	public function executeRegisternewform()
	{
		$c = new Criteria();
		$branches = BranchPeer::doSelect($c);
		$options = array();
		$options[] = 'Select Department';
		foreach($branches as $branch)
		{
			$options[$branch->getId()] = $branch->getName();
		}
		$this->options = $options;		
	}
	
	public function executeRegisternew()
	{
		$user = new User();
		$personal = new Personal();
		
		$user->setBranchId($this->getRequestParameter('branch'));
		$user->setGraduationyear($this->getRequestParameter('year'));
		$user->setUsername($this->getRequestParameter('username'));
		$user->setIslocked('3');
		$user->save();
		
		$personal->setUserId($user->getId());
		$personal->setFirstname($this->getRequestParameter('firstname'));
		$personal->setMiddlename($this->getRequestParameter('middlename'));
		$personal->setLastname($this->getRequestParameter('lastname'));
		$personal->setEmail($this->getRequestParameter('email'));
		$personal->save();
	}
	
	public function handleErrorRegisternew()
	{
		$this->forward('home', 'registernewform');
	}

	public function executeBulkuploadform()
	{
				
	}

	public function executeBulkupload()
	{
		if($this->getRequest()->getFileName('csvfile'))
		{
	    	$fileName = md5($this->getRequest()->getFileName('csvfile').time().rand(0, 99999));
		 	$ext = $this->getRequest()->getFileExtension('csvfile');
		 	$this->getRequest()->moveFile('csvfile', sfConfig::get('sf_upload_dir')."//csvfiles//".$fileName.".csv");
		 	$fullname = $fileName.".csv";
		 	//$fullpath = '/uploads/csvfiles/'.$fullname;
		 	$fp = sfConfig::get('sf_upload_dir')."//csvfiles//".$fileName.".csv";
			$reader = new sfCsvReader($fp, ',', '"');
			$reader->open();
		
			$i=1;
			$exist[] = array();
			$ignore[] = array();
			$ignoreflag = 0;
			$success = 0;
		    while ($data = $reader->read())
		    {
		    	$name[] = array();
		    	$name = explode(' ', $data[0]);
		    	$roll = $data[1];
		    	$enrol = $data[2];
		    	$branch = $data[3];
		    	$degree = $data[4];
		    	$year = $data[5];
		    	
		    	$c = new Criteria();
		    	$c->add(UserPeer::ENROLMENT, $enrol);
		    	$user = UserPeer::doSelectOne($c);
		    	if(!$user){
			    	$c = new Criteria();
			    	$c->add(BranchPeer::NAME, $branch);
			    	$br = BranchPeer::doSelectOne($c);
			    	if(!$br)
			    	{
			    		$br = new Branch();
			    		$br->setName($branch);
			    		$br->save();
			    	}
			    	
			    	$c = new Criteria();
			    	$c->add(DegreePeer::NAME, $degree);
			    	$dg = DegreePeer::doSelectOne($c);
			    	if(!$dg)
			    	{
			    		$dg = new Degree();
			    		$dg->setName($degree);
			    		$dg->save();
			    	}
			    	
			    	$user = new User();
			    	if($roll){
			    		$user->setRoll($roll);
			    		$user->setRollflag('1');
			    	}
			    	if($enrol){
			    		$user->setEnrolment($enrol);
			    		$user->setEnrolflag('1');
			    	}else{
			    		$ignoreflag = 1;
			    	}
			    	if($year){
			    		$user->setGraduationyear($year);
			    		$user->setGraduationyearflag('1');
			    	}
			    	$user->setBranchId($br->getId());
			    	$user->setBranchflag('1');
			    	$user->setDegreeId($dg->getId());
			    	$user->setDegreeflag('1');
			    	$user->setIslocked('1');
			    	
			    	$personal = new Personal();
			    	$personal->setFirstname($name[0]);
			    	if($name[3]){
			    		$midname = $name[1]." ".$name[2];
			    		$personal->setMiddlename($midname);
			    		$personal->setLastname($name[3]);
			    	}elseif($name[2]){
			    		$personal->setMiddlename($name[1]);
			    		$personal->setLastname($name[2]);
			    	}
			    	elseif($name[1]){
			    		$personal->setLastname($name[1]);
			    	}
		    		
			    	if($ignoreflag == 0){
			    		$user->save();
			    		$personal->setUserId($user->getId());
			    		$personal->save();
			    		$success++;
			    	}else{
			    		$ignore[] = $i;
			    	}
			    	
		    	}else{
		    		$exist[] = $i;
		    	}
			    
		    	$i++;
		    } // while ($data = $reader->read()) ends here
		    $reader->close();
		    
			$this->sc = $success;
			$this->ig = $ignore;
			$this->ex = $exist;
		}
	}
	
	public function handleErrorBulkupload()
	{
		$this->forward('home', 'bulkuploadform');
	}
	
	public function executeSearchform()
	{
		$c = new Criteria();
		$branches = BranchPeer::doSelect($c);
		$options = array();
		$options[] = 'Select Department';
		foreach($branches as $branch)
		{
			$options[$branch->getId()] = $branch->getName();
		}
		$this->broptions = $options;	
		
		$c = new Criteria();
		$chapters = ChapterPeer::doSelect($c);
		$options = array();
		$options[] = 'Select Chapter';
		foreach($chapters as $chapter)
		{
			$options[$chapter->getId()] = $chapter->getName();
		}
		$this->choptions = $options;
		
		$degrees = DegreePeer::doSelect(new Criteria());
		$options = array();
		$options[] = 'Select Degree';
		foreach($degrees as $degree)
		{
			$options[$degree->getId()] = $degree->getName();
		}
		$this->dgoptions = $options;
		
		$options = array();
		$options[] = 'Select Year';
		for($i=1923; $i<=2013; $i++)
		{
			$options[$i] = $i;
		}
		$this->yroptions = $options; 
		
	}
	
	public function executeSearch()
	{
		$branchid = $this->getRequestParameter('branch');
		$chapterid = $this->getRequestParameter('chapter');
		$year = $this->getRequestParameter('year');
		$degreeid = $this->getRequestParameter('degree');
		$currentlyat = $this->getRequestParameter('currentlyat');
		
		$flag = 0;
		
		$c = new Criteria();
		if($branchid != 0)
		{
			$c->add(UserPeer::BRANCH_ID, $branchid);
			$flag = 1;
		}
		if($chapterid != 0)
		{
			$c->addJoin(UserPeer::ID, UserchapterregionPeer::USER_ID);
			$c->addJoin(UserchapterregionPeer::CHAPTERREGION_ID, ChapterregionPeer::ID);
			$c->add(ChapterregionPeer::CHAPTER_ID, $chapterid);
			$flag = 1;
		}
		if($year != 0)
		{
			$c->add(UserPeer::GRADUATIONYEAR, $year);
			$flag = 1;
		}
		if($degreeid != 0)
		{
			$c->add(UserPeer::DEGREE_ID, $degreeid);
			$flag = 1;
		}
		/*if($currentlyat)
		{
			
		}*/
		if($flag == 1)
		{
			$this->results = UserPeer::doSelect($c);
		}
		else
		{
			$this->flag = 1;
		}
		$this->chapterid = $chapterid;
	}
	
}