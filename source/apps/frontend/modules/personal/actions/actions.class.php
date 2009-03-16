<?php
// auto-generated by sfPropelCrud
// date: 2009/02/10 08:16:08
?>
<?php

/**
 * personal actions.
 *
 * @package    sf_sandbox
 * @subpackage personal
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 3335 2007-01-23 16:19:56Z fabien $
 */
class personalActions extends sfActions
{
  public function executeIndex()
  {
    return $this->forward('personal', 'list');
  }

  public function executeList()
  {
    $this->personals = PersonalPeer::doSelect(new Criteria());
  }

  public function executeShow()
  {
  	$c = new Criteria();
  	$c->add(UserPeer::USERNAME, $this->getUser()->getAttribute('username'));
  	$user = UserPeer::doSelectOne($c);
    $c = new Criteria();
    $c->add(PersonalPeer::USER_ID, $user->getId());
    $this->personal = PersonalPeer::doSelectOne($c);
  	
    $c = new Criteria();
    $c->add(LoruserPeer::USER_ID, $user->getId());
    $c->addJoin(LoruserPeer::LORVALUES_ID, LorvaluesPeer::ID);
    $c->add(LorvaluesPeer::LORFIELDS_ID, sfConfig::get('app_lor_linkedin'));
    $this->lors = LorvaluesPeer::doSelect($c);
    
    $c = new Criteria();
    $c->add(LoruserPeer::USER_ID, $user->getId());
    $c->addJoin(LoruserPeer::LORVALUES_ID, LorvaluesPeer::ID);
    $c->add(LorvaluesPeer::LORFIELDS_ID, sfConfig::get('app_lor_general'));
    $this->glors = LorvaluesPeer::doSelect($c);
    
    $this->forward404Unless($this->personal);
  }

  public function executeCreate()
  {
    $this->personal = new Personal();

    $this->setTemplate('edit');
  }

  public function executeEdit()
  {
  	$c = new Criteria();
  	$c->add(UserPeer::USERNAME, $this->getUser()->getAttribute('username'));
  	$user = UserPeer::doSelectOne($c);
    //$this->personal = PersonalPeer::retrieveByPk($this->getRequestParameter('id'));
    $c = new Criteria();
    $c->add(PersonalPeer::USER_ID, $user->getId());
    $this->personal = PersonalPeer::doSelectOne($c);
    $this->forward404Unless($this->personal);
    
    $this->privacyoptions = Array('1' => 'Myself', '2' => 'My Classmates', '3' => 'Everyone');
    
  }

  public function executeUpdate()
  {
    if (!$this->getRequestParameter('id'))
    {
      $personal = new Personal();
    }
    else
    {
      $personal = PersonalPeer::retrieveByPk($this->getRequestParameter('id'));
      $this->forward404Unless($personal);
    }
	if($this->getRequest()->getFileName('image'))
	{
    	$fileName = md5($this->getRequest()->getFileName('image').time().rand(0, 99999));
	 	$ext = $this->getRequest()->getFileExtension('image');
	 	$this->getRequest()->moveFile('image', sfConfig::get('sf_upload_dir')."//profilepic//".$fileName.$ext);
	 	$fullname = $fileName.$ext;
	 	$fullpath = '/uploads/profilepic/'.$fullname;
    	$personal->setImage($fullpath);
	}
    
    $personal->setId($this->getRequestParameter('id'));
    $personal->setUserId($this->getRequestParameter('user_id') ? $this->getRequestParameter('user_id') : null);

    $personal->setImageflag($this->getRequestParameter('imageflag'));
    $personal->setSalutation($this->getRequestParameter('salutation'));
    $personal->setFirstname($this->getRequestParameter('firstname'));
    $personal->setFirstnameflag($this->getRequestParameter('firstnameflag'));
    $personal->setMiddlename($this->getRequestParameter('middlename'));
    $personal->setMiddlenameflag($this->getRequestParameter('middlenameflag'));
    $personal->setLastname($this->getRequestParameter('lastname'));
    $personal->setLastnameflag($this->getRequestParameter('lastnameflag'));
    $personal->setMaidenname($this->getRequestParameter('maidenname'));
    $personal->setMaidennameflag($this->getRequestParameter('maidennameflag'));
    $personal->setItbhuname($this->getRequestParameter('itbhuname'));
    $personal->setItbhunameflag($this->getRequestParameter('itbhunameflag'));
    $personal->setGender($this->getRequestParameter('gender'));
    $personal->setGenderflag($this->getRequestParameter('genderflag'));
    if ($this->getRequestParameter('dob'))
    {
      list($d, $m, $y) = sfI18N::getDateForCulture($this->getRequestParameter('dob'), $this->getUser()->getCulture());
      $personal->setDob("$y-$m-$d");
    }
    $personal->setDobflag($this->getRequestParameter('dobflag'));
    $personal->setMaritalstatus($this->getRequestParameter('maritalstatus'));
    $personal->setMaritalstatusflag($this->getRequestParameter('maritalstatusflag'));
    $personal->setEmail($this->getRequestParameter('email'));
    $personal->setEmailflag($this->getRequestParameter('emailflag'));
    $personal->setWebsite($this->getRequestParameter('website'));
    $personal->setWebsiteflag($this->getRequestParameter('websiteflag'));
    $personal->setLinkedin($this->getRequestParameter('linkedin'));
    $personal->setLinkedinflag($this->getRequestParameter('linkedinflag'));

    $personal->save();

    return $this->redirect('personal/show?id='.$personal->getId());
  }

  public function executeDelete()
  {
    $personal = PersonalPeer::retrieveByPk($this->getRequestParameter('id'));

    $this->forward404Unless($personal);

    $personal->delete();

    return $this->redirect('personal/list');
  }
  
  public function executeLoraccept(){
  	$lor = LorvaluesPeer::retrieveByPK($this->getRequestParameter('lorid'));
  	
  	$c = new Criteria();
  	$c->add(PersonalPeer::USER_ID, $this->getUser()->getAttribute('userid'));
  	$personal = PersonalPeer::doSelectOne($c);
  	$personal->setLinkedin($lor->getData());
  	$personal->save();
  	
  	$c = new Criteria();
    $c->add(LoruserPeer::USER_ID, $this->getUser()->getAttribute('userid'));
    $c->addJoin(LoruserPeer::LORVALUES_ID, LorvaluesPeer::ID);
    $c->add(LorvaluesPeer::LORFIELDS_ID, sfConfig::get('app_lor_linkedin'));
    $lors = LorvaluesPeer::doSelect($c);
  	foreach ($lors as $lor){
  		$c = new Criteria();
  		$c->add(LoruserPeer::LORVALUES_ID, $lor->getId());
  		$loruser = LoruserPeer::doSelectOne($c);
  		$loruser->delete();
  		$lor->delete();
  	}
  	$this->redirect('/personal/show');
  }
  
  public function executeLorreject()
  {
  	$a = $this->getRequestParameter('a');
	$lorid = $this->getRequestParameter('lorid');
  	
  	$c = new Criteria();
    $c->add(LoruserPeer::USER_ID, $this->getUser()->getAttribute('userid'));
    $c->addJoin(LoruserPeer::LORVALUES_ID, LorvaluesPeer::ID);
    if($a == 'g'){
    	$c->add(LorvaluesPeer::LORFIELDS_ID, sfConfig::get('app_lor_general'));
    }else{
    	$c->add(LorvaluesPeer::LORFIELDS_ID, sfConfig::get('app_lor_linkedin'));
    }
    if($lorid){
    	$c->add(LorvaluesPeer::ID, $lorid);
    }
    $lors = LorvaluesPeer::doSelect($c);
  	foreach ($lors as $lor){
  		$c = new Criteria();
  		$c->add(LoruserPeer::LORVALUES_ID, $lor->getId());
  		$loruser = LoruserPeer::doSelectOne($c);
  		$loruser->delete();
  		$lor->delete();
  	}
  	$this->redirect('/personal/show');
  	
  }


}
