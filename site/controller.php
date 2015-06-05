<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Controller
 *
 * @package Joomla
 */
class TestController extends JControllerLegacy {

   	function display($cachable = false, $urlparams = array())
	{
		$this->input->set('view', $this->input->getCmd('view', 'test'));
		$this->input->set('layout', $this->input->getCmd('layout', 'edit'));

		parent::display($cachable, $urlparams);
	}
}