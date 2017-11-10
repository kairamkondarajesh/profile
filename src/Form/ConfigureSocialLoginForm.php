<?php

namespace Drupal\corporate\Form;

use Drupal\social_login\Form\SocialLoginAdminSettings;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\Html;


/**
 * Defines form for selecting Varbase's Multiligual configuration options form.
 */
class ConfigureSocialLoginForm extends SocialLoginAdminSettings {

	/**
	 * {@inheritdoc}
	 */
	public function getFormId() {
		return 'corporate_social_login_configuration';
	}

	function buildForm(array $form, FormStateInterface $form_state) {
		$form['#attached'] = array(
			'library' => array(
				'social_login/configuration',
			),
		);

		// Read Settings.
		$settings = \social_login_get_settings();

		// API Connection.
		$form['social_login_api_connection'] = array(
			'#type' => 'fieldset',
			'#title' => t('API Communication'),
			'#id' => 'social_login_api_connection',
		);

		// Default value for handler.
		if ($form_state->getValue(array(
			'http_handler',
		))) {
			$default = $form_state->getValue([
				'http_handler',
			]);
		}
		elseif (!empty($settings['http_handler'])) {
			$default = $settings['http_handler'];
		}
		else {
			$default = 'curl';
		}

		$form['social_login_api_connection']['http_handler'] = [
			'#type' => 'select',
			'#title' => t('API Communication Handler'),
			'#description' => t('Either <a href="@link_curl" target="_blank">PHP cURL</a> or the <a href="@link_guzzle" target="_blank">Drupal Guzzle client</a> must be available on your server.', [
				'@link_curl' => 'http://www.php.net/manual/en/book.curl.php',
				'@link_guzzle' => 'http://docs.guzzlephp.org/en/latest/',
			]),
			'#options' => [
				'curl' => t('PHP cURL library'),
				'fsockopen' => t('Drupal Guzzle client'),
			],
			'#default_value' => $default,
		];

		// Default value for protocol.
		if ($form_state->getValue([
			'http_protocol',
		])) {
			$default = $form_state->getValue([
				'http_protocol',
			]);
		}
		elseif (!empty($settings['http_protocol'])) {
			$default = $settings['http_protocol'];
		}
		else {
			$default = 'https';
		}

		$form['social_login_api_connection']['http_protocol'] = array(
			'#type' => 'select',
			'#title' => t('API Communication Protocol'),
			'#description' => t('Your firewall must allow outbound requests either on port 443/HTTPS or on port 80/HTTP.'),
			'#options' => array(
				'https' => t('Port 443/HTTPS'),
				'http' => t('Port 80/HTTP'),
			),
			'#default_value' => $default,
		);

		// AutoDetect Button.
		$form['social_login_api_connection']['autodetect'] = array(
			'#type' => 'button',
			'#value' => t('Autodetect communication settings'),
			'#weight' => 30,
			'#ajax' => array(
				'callback' => 'Drupal\social_login\Form\ajax_api_connection_autodetect',
				'wrapper' => 'social_login_api_connection',
				'method' => 'replace',
				'effect' => 'fade',
			),
		);

		// API Settings.
		$form['social_login_api_settings'] = array(
			'#type' => 'fieldset',
			'#title' => t('API Settings'),
			'#id' => 'social_login_api_settings',
			'#description' => t('<a href="@setup_social_login" target="_blank">Click here to create and view your API Credentials</a>', array(
				'@setup_social_login' => 'https://app.oneall.com/applications/',
			)),
		);

		// API Subdomain.
		$form['social_login_api_settings']['api_subdomain'] = array(
			'#id' => 'api_subdomain',
			'#type' => 'textfield',
			'#title' => t('API Subdomain'),
			'#default_value' => (!empty($settings['api_subdomain']) ? $settings['api_subdomain'] : ''),
			'#size' => 60,
			'#maxlength' => 60,
		);

		// API Public Key.
		$form['social_login_api_settings']['api_key'] = array(
			'#id' => 'api_key',
			'#type' => 'textfield',
			'#title' => t('API Public Key'),
			'#default_value' => (!empty($settings['api_key']) ? $settings['api_key'] : ''),
			'#size' => 60,
			'#maxlength' => 60,
		);

		// API Private Key.
		$form['social_login_api_settings']['api_secret'] = array(
			'#id' => 'api_secret',
			'#type' => 'textfield',
			'#title' => t('API Private Key'),
			'#default_value' => (!empty($settings['api_secret']) ? $settings['api_secret'] : ''),
			'#size' => 60,
			'#maxlength' => 60,
		);

		// API Verify Settings Button.
		$form['social_login_api_settings']['verify'] = array(
			'#id' => 'social_login_check_api_button',
			'#type' => 'button',
			'#value' => t('Verify API Settings'),
			'#weight' => 1,
			'#ajax' => array(
				'callback' => 'Drupal\social_login\Form\ajax_check_api_connection_settings',
				'wrapper' => 'social_login_api_settings',
				'method' => 'replace',
				'effect' => 'fade',
			),
		);

		// Login page settings.
		$form['social_login_settings_login_page'] = array(
			'#type' => 'fieldset',
			'#title' => t('Login Page Settings'),
		);

		$form['social_login_settings_login_page']['login_page_icons'] = array(
			'#type' => 'select',
			'#title' => t('Social Login Icons'),
			'#description' => t('Allows the users to login either with their social network account or with their already existing account.'),
			'#options' => array(
				'above' => t('Show the Social Login icons above the existing login form'),
				'below' => t('Show the Social Login icons below the existing login form (Default, recommended)'),
				'disable' => t('Do not show the Social Login icons on the login page'),
			),
			'#default_value' => (empty($settings['login_page_icons']) ? 'below' : $settings['login_page_icons']),
		);

		$form['social_login_settings_login_page']['login_page_caption'] = array(
			'#type' => 'textfield',
			'#title' => t('Social Login Icons: Caption [Leave empty for none]'),
			'#default_value' => (!isset($settings['login_page_caption']) ? t('Login with:') : $settings['login_page_caption']),
			'#size' => 60,
			'#maxlength' => 60,
			'#description' => t('This is the title displayed above the social network icons.'),
		);

		// Registration page settings.
		$form['social_login_settings_registration_page'] = array(
			'#type' => 'fieldset',
			'#title' => t('Registration Page Settings'),
		);

		$form['social_login_settings_registration_page']['registration_page_icons'] = array(
			'#type' => 'select',
			'#title' => t('Social Login Icons'),
			'#description' => t('Allows the users to register by using either their social network account or by creating a new account.'),
			'#options' => array(
				'above' => t('Show the Social Login icons above the existing login form (Default, recommended)'),
				'below' => t('Show the Social Login icons below the existing login form'),
				'disable' => t('Do not show the Social Login icons on the registration page'),
			),
			'#default_value' => (empty($settings['registration_page_icons']) ? 'above' : $settings['registration_page_icons']),
		);

		$form['social_login_settings_registration_page']['registration_page_caption'] = array(
			'#type' => 'textfield',
			'#title' => t('Social Login Icons: Caption [Leave empty for none]'),
			'#default_value' => (!isset($settings['registration_page_caption']) ? t('Instantly register with:') : $settings['registration_page_caption']),
			'#size' => 60,
			'#maxlength' => 60,
			'#description' => t('This is the title displayed above the social network icons.'),
		);

		// Edit profile page settings.
		$form['social_login_settings_profile_page'] = array(
			'#type' => 'fieldset',
			'#title' => t('Edit Profile Page Settings'),
		);

		$form['social_login_settings_profile_page']['profile_page_icons'] = array(
			'#type' => 'select',
			'#title' => t('Social Login Icons'),
			'#description' => t('Allows the users to link a social network account to their regular account.'),
			'#options' => array(
				'above' => t('Show the Social Login icons above the profile settings'),
				'below' => t('Show the Social Login icons below the profile settings (Default, recommended)'),
				'disable' => t('Do not show the Social Login icons on the profile page'),
			),
			'#default_value' => (empty($settings['profile_page_icons']) ? 'below' : $settings['profile_page_icons']),
		);

		$form['social_login_settings_profile_page']['profile_page_caption'] = array(
			'#type' => 'textfield',
			'#title' => t('Social Login Icons: Caption [Leave empty for none]'),
			'#default_value' => (!isset($settings['profile_page_caption']) ? t('Link your account to a social network') : $settings['profile_page_caption']),
			'#size' => 60,
			'#maxlength' => 60,
			'#description' => t('This is the title displayed above the social network icons.'),
		);

		// Account creation settings.
		$form['social_login_settings_account_creation'] = array(
			'#type' => 'fieldset',
			'#title' => t('Account creation settings'),
		);

		$form['social_login_settings_account_creation']['registration_approval'] = array(
			'#type' => 'select',
			'#title' => t('Do user that register with Social Login have to be approved by an administrator?'),
			'#description' => t('Manual approval should not be required as Social Login eliminates SPAM issues almost entirely.'),
			'#options' => array(
				'inherit' => t('Use the system-wide setting from the Drupal account settings (Default)'),
				'disable' => t('Automatically approve users that register with Social Login'),
				'enable' => t('Always require administrators to approve users that register with Social Login'),
			),
			'#default_value' => (empty($settings['registration_approval']) ? 'inherit' : $settings['registration_approval']),
		);

		$form['social_login_settings_account_creation']['registration_retrieve_avatars'] = array(
			'#type' => 'select',
			'#title' => t('Retrieve the user picture from the social network when a user registers with Social Login?'),
			'#description' => t('Social Login grabs the user picture from the social network, saves it locally and uses it as avatar for the new account.'),
			'#options' => array(
				'enable' => t('Yes, retrieve the user picture from the social network and use it as avatar for the user (Default)'),
				'disable' => t('No, do not retrieve the user picture from the social network'),
			),
			'#default_value' => (empty($settings['registration_retrieve_avatars']) ? 'enable' : $settings['registration_retrieve_avatars']),
		);

		$form['social_login_settings_account_creation']['registration_method'] = array(
			'#type' => 'select',
			'#title' => t('Automatically create a new user account when a user registers with Social Login?'),
			'#description' => t('If a user registers for example with Facebook, Social Login grabs his Facebook profile data and uses it to simply the user registration.'),
			'#options' => array(
				'manual' => t('Do not create new accounts automatically, just pre-populate the default registration form and let users complete the registration manually (Default)'),
				'auto_random_email' => t('Automatically create new user accounts and generate a bogus email address if the social network provides no email address'),
				'auto_manual_email' => t('Automatically create new user accounts BUT fall back to the default registration form when the social network provides no email address'),
			),
			'#default_value' => (empty($settings['registration_method']) ? 'manual' : $settings['registration_method']),
		);

		// Enable the social networks/identity providers.
		$form['social_login_providers'] = array(
			'#type' => 'fieldset',
			'#title' => t('Enable the social networks/identity providers of your choice'),
		);

		// Include the list of providers.
		$social_login_available_providers = $this->social_login_platforms_get_available_providers();

		// Add providers.
		foreach ($social_login_available_providers as $key => $provider_data) {
			$form['social_login_providers']['social_login_icon_' . $key] = array(
				'#title' => Html::escape($provider_data['name']),
				'#type' => 'container',
				'#attributes' => array(
					'class' => array(
						'social_login_provider',
						'social_login_provider_' . $key,
					),
					'style' => array(
						'float: left;',
						'margin: 5px;',
					),
				),
			);

			$form['social_login_providers']['provider_' . $key] = array(
				'#type' => 'checkbox',
				'#title' => Html::escape($provider_data['name']),
				'#default_value' => (empty($settings['provider_' . $key]) ? 0 : 1),
				'#attributes' => array(
					'style' => array(
						'margin: 15px;',
					),
				),
			);

			$form['social_login_providers']['clear_' . $key] = array(
				'#type' => 'container',
				'#attributes' => array(
					'style' => array(
						'clear: both;',
					),
				),
			);
		}

		$form['actions']['#type'] = 'actions';
		$form['actions']['submit'] = array(
			'#type' => 'submit',
			'#value' => t('Save Settings'),
		);

		return $form;
	}

	function social_login_platforms_get_available_providers() {
		$providers = array(
			'facebook' => array(
				'name' => 'Facebook'
			),
			'github' => array(
				'name' => 'Github.com'
			),
			'google' => array(
				'name' => 'Google'
			),
			'instagram' => array(
				'name' => 'Instagram'
			),
			'linkedin' => array(
				'name' => 'LinkedIn'
			),
			'paypal' => array(
				'name' => 'PayPal'
			),
			'pinterest' => array(
				'name' => 'Pinterest'
			),
			'reddit' => array(
				'name' => 'Reddit'
			),
			'twitter' => array(
				'name' => 'Twitter'
			),
			'vimeo' => array(
				'name' => 'Vimeo'
			),
			'youtube' => array(
				'name' => 'YouTube'
			)
		);

		return $providers;
	}

}
