<?php
// no direct access
defined( '_JEXEC' ) or die();

use Joomla\Registry\Registry;

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

	public function signin()
	{
		$network = new TestNetworkLinkedin();
		$network->authenticate();
	}

	public function network()
	{
		$network = new TestNetworkLinkedin();

		$app = JFactory::getApplication();
		$input = $app->input;

		$error = $input->get('error',0,'int');
		if($error>0)
		{
			$error_description = $input->get('error_description','','string');
			jexit($error_description);
		}
		else
		{
			$state = $input->get('state', false, 'raw');

			if($state !== JSession::getFormToken())
			{
				throw new Exception(JText::_('JINVALID_TOKEN'),401);
			}
		}

		/* TODO: not working coz headers include charset not only application/json ! */
		$token = $network->authenticate();
		//$token = $network->testReponse($this->input);

		var_dump($token);
		jexit();

		$app = JFactory::getApplication();
		$session = JFactory::getSession();
		$session->set('application.queue', $app->getMessageQueue());
		echo '<html><head><script>window.opener.location.reload(true);window.close();</script></head><body></body></html>';
		jexit();
	}
}

class TestNetworkLinkedin extends JOAuth2Client
{
	// Todo: ID Client
	private $consumer_key 		= '';
	// Todo: Secret client
	private $consumer_secret 	= '';

	public function __construct(Registry $options = null, JHttp $http = null, JInput $input = null, JApplicationWeb $application = null)
	{
		$this->options = isset($options) ? $options : new Registry;

		$base = rtrim(JUri::root(),'/');
		// TODO: fix for localhost (eg: http://localhost/joomla_test) / remove/comment if not on localhost ...
		$base = 'http://localhost';

		$this->options->def('redirecturi',$base.JRoute::_( 'index.php?option=com_test&task=test.network', false ));
		$this->options->def('clientid',$this->consumer_key);
		$this->options->def('clientsecret',$this->consumer_secret);

		$this->options->def('sendheaders',true);

		$this->options->def('authmethod','get');
		$this->options->def('getparam', 'oauth2_access_token');

		$this->options->def('authurl','https://www.linkedin.com/uas/oauth2/authorization');
		$this->options->def('tokenurl','https://www.linkedin.com/uas/oauth2/accessToken');

		parent::__construct($this->options, $http, $input, $application);
	}

	public function authenticate()
	{
		$this->setOption('state',JSession::getFormToken());
		return parent::authenticate();
	}

	public function testreponse($input)
	{
		if ($data['code'] = $input->get('code', false, 'raw'))
		{
			$data['grant_type'] = 'authorization_code';
			$data['redirect_uri'] = $this->getOption('redirecturi');
			$data['client_id'] = $this->getOption('clientid');
			$data['client_secret'] = $this->getOption('clientsecret');
			$response = $this->http->post($this->getOption('tokenurl'), $data);

			if ($response->code >= 200 && $response->code < 400)
			{
				if (strpos($response->headers['Content-Type'],'application/json') === 0)
				{
					$token = array_merge(json_decode($response->body, true), array('created' => time()));
				}
				else
				{
					parse_str($response->body, $token);
					$token = array_merge($token, array('created' => time()));
				}

				$this->setToken($token);

				$session = JFactory::getSession();
				$session->set('key', $token, 'oauth_token');

				$response = $this->query('https://api.linkedin.com/v1/people/~:(id,first-name,last-name,email-address)?format=json');
				if ($response->code >= 200 && $response->code < 400)
				{
					if (strpos($response->headers['Content-Type'],'application/json') === 0)
					{
						return json_decode($response->body, true);
					}
					else
					{
						parse_str($response->body, $profil);
						return $profil;
					}
				}

				return false;
			}
			else
			{
				return false;//throw new RuntimeException('Error code ' . $response->code . ' received requesting access token: ' . $response->body . '.');
			}
		}
		else
			return false;
	}
}