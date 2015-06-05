<?php
// no direct access
defined( '_JEXEC' ) or die();

/**
 * @package		Joomla.Site
 * @since		1.5
 */
class TestControllerTest extends JControllerForm
{
	public function getModel($name = 'Test', $prefix = 'TestModel', $config = array())
	{
		return parent::getModel($name, $prefix, $config);
	}
}