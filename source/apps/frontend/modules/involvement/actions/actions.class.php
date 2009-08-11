<?php
// auto-generated by sfPropelCrud
// date: 2009/07/17 06:52:55
?>
<?php

/**
 * involvement actions.
 *
 * @package    sf_sandbox
 * @subpackage involvement
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 3335 2007-01-23 16:19:56Z fabien $
 */
class involvementActions extends sfActions
{
  public function executeIndex()
  {
    return $this->forward('involvement', 'list');
  }

  public function executeList()
  {
    $this->involvements = InvolvementPeer::doSelect(new Criteria());
  }

  public function executeShow()
  {
    $this->involvement = InvolvementPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($this->involvement);
  }

  public function executeCreate()
  {
    $this->involvement = new Involvement();

    $this->setTemplate('edit');
  }

  public function executeEdit()
  {
    $this->involvement = InvolvementPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($this->involvement);
  }

  public function executeUpdate()
  {
    if (!$this->getRequestParameter('id'))
    {
      $involvement = new Involvement();
    }
    else
    {
      $involvement = InvolvementPeer::retrieveByPk($this->getRequestParameter('id'));
      $this->forward404Unless($involvement);
    }

    $involvement->setId($this->getRequestParameter('id'));
    $involvement->setName($this->getRequestParameter('name'));

    $involvement->save();

    return $this->redirect('involvement/show?id='.$involvement->getId());
  }

  public function executeDelete()
  {
    $involvement = InvolvementPeer::retrieveByPk($this->getRequestParameter('id'));

    $this->forward404Unless($involvement);

    $involvement->delete();

    return $this->redirect('involvement/list');
  }

  public function executeProjects(){
  	
  }
  
  public function executeDonate(){
  	
  }
  public function executeVolunteer(){
  	
  }
}