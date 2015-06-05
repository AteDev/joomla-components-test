<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

// Require the base controller
require_once JPATH_COMPONENT.'/controller.php';

// Get an instance of the controller prefixed by *
$controller = JControllerLegacy::getInstance('Test');

// Perform the Request task
$controller->execute(JRequest::getCmd('task'));

// Redirect if set by the controller
$controller->redirect();