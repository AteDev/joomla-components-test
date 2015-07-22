<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_test
 *
 *
 */

defined('_JEXEC') or die;

/**
 * HTML View class for the component
 *
 * @package		Joomla.Site
 * @subpackage	com_test
 * @since		1.5
 */
class TestViewTest extends JViewLegacy
{
	function display($tpl = null)
	{
		// Initialise variables.
		$app			= JFactory::getApplication();

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			//JError::raiseWarning(500, implode("\n", $errors));
			throw new Exception(implode("\n", $errors),500);

			return false;
		}

		$this->params = $app->getParams();
		$this->pageclass_sfx = $this->params->get('pageclass_sfx', '');

		parent::display($tpl);
	}
}
