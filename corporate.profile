<?php
use Drupal\Core\Form\FormStateInterface;
use Drupal\language\Entity\ConfigurableLanguage;
use Drupal\corporate\Config\ConfigBit;
use Drupal\corporate\Form\ConfigureMultilingualForm;
use Drupal\corporate\Form\ConfigureMailChimpForm;
use Drupal\corporate\Form\AssemblerForm;
use Drupal\corporate\Form\DevelopmentToolsAssemblerForm;
use Drupal\mailchimp\Form\MailchimpAdminSettingsForm;
use Drupal\corporate\Form\ConfigureSocialLoginForm;


/**
 * Implements hook_install_tasks().
 */
function corporate_install_tasks(&$install_state) {
  // Determine whether the enable multiligual option is selected during the
  // Multilingual configuration task.
  $needs_configure_multilingual = (isset($install_state['corporate']['enable_multilingual']) && $install_state['corporate']['enable_multilingual'] == TRUE);
   $myprofile_needs_batch_processing = \Drupal::state()->get('myprofile.needs_batch_processing', FALSE);
  return array(
    'corporate_multilingual_configuration_form' => array(
      'display_name' => t('Multilingual configuration'),
      'display' => TRUE,
      'type' => 'form',
      'function' => ConfigureMultilingualForm::class,
    ),
    'corporate_configure_multilingual' => array(
      'display_name' => t('Configure multilingual'),
      'display' => $needs_configure_multilingual,
      'type' => 'batch',
    ),
    //'corporate_mailchimp_configuration_form' => array(
    //  'display_name' => t('Mail Chimp configuration'),
    //  'display' => TRUE,
    //  'type' => 'form',
    //  'function' => MailchimpAdminSettingsForm::class,
    //),
    'corporate_generate_contents' => array(
      'display_name' => t('Generating Contents'),
      'display' => TRUE,
      'type' => 'batch',
    ),
    'corporate_social_login_configuration_form' => array(
      'display_name' => t('Social Login configuration'),
      'display' => TRUE,
      'type' => 'form',
      'function' => ConfigureSocialLoginForm::class,
    )
  );
}

/**
 * Batch job to configure multilingual components.
 *
 * @param array $install_state
 *   The current install state.
 *
 * @return array
 *   The batch job definition.
 */
function corporate_configure_multilingual(array &$install_state) {
  $batch = array();

  // If the multiligual config checkbox were checked.
  if (isset($install_state['corporate']['enable_multilingual'])
         && $install_state['corporate']['enable_multilingual'] == TRUE) {

    // Install the Varbase internationalization feature module.
    $batch['operations'][] = ['corporate_assemble_extra_component_then_install', (array) 'corporate_internationalization'];

    // Add all selected languages and then translatvarbase_hide_messagesion
    // will fetched for theme.
    foreach ($install_state['corporate']['multilingual_languages'] as $language_code) {
      $batch['operations'][] = ['corporate_configure_language_and_fetch_traslation', (array) $language_code];
    }

    // Hide Wornings and status messages.
    $batch['operations'][] = ['corporate_hide_warning_and_status_messages', (array) TRUE];

    // Change configurations to work with enable_multilingual.
    # $batch['operations'][] = ['corporate_config_bit_for_multilingual', (array) TRUE];

  }
  else {
    // Change configurations to work with NO multilingual.
    # $batch['operations'][] = ['corporate_config_bit_for_multilingual', (array) FALSE];
  }

  // Fix entity updates to clear up any mismatched entity.
  $batch['operations'][] = ['corporate_fix_entity_update', (array) TRUE];

  return $batch;
}


/**
 * Batch function to fix entity updates to clear up any mismatched entity.
 *
 * Entity and/or field definitions, The following changes were detected in
 * the entity type and field definitions.
 *
 * @param string|array $entity_update
 *   To entity update or not.
 */
function corporate_fix_entity_update($entity_update) {
  if ($entity_update) {
    \Drupal::entityDefinitionUpdateManager()->applyUpdates();
  }
}

/**
 * Batch function to hide warning messages.
 *
 * @param bool $hide
 *   To hide or not.
 */
function corporate_hide_warning_and_status_messages($hide) {
  if ($hide && !isset($_SESSION['messages']['error'])) {
    unset($_SESSION['messages']);
  }
}

/**
 * Batch function to add selected langauges then fetch all traslation.
 *
 * @param string|array $language_code
 *   Language code to install and fetch all traslation.
 */
function corporate_configure_language_and_fetch_traslation($language_code) {
  ConfigurableLanguage::createFromLangcode($language_code)->save();
}
/**
 * Implements hook_form_FORM_ID_alter() for install_configure_form().
 *
 * Allows the profile to alter the site configuration form.
 */
/* function standard_form_install_configure_form_alter(&$form, FormStateInterface $form_state) {
  // Add a placeholder as example that one can choose an arbitrary site name.
  $form['site_information']['site_name']['#attributes']['placeholder'] = t('My site');
  $form['#submit'][] = 'standard_form_install_configure_submit';
} */


/**
 * Batch job to create contents
 *
 * @param array $install_state
 *   The current install state.
 *
 * @return array
 *   The batch job definition.
 */
function corporate_generate_contents(array &$install_state) {
  $batch = array();
  // Fix entity updates to clear up any mismatched entity.
  $batch['operations'][] = ['generate_contents', (array) TRUE];
  return $batch;
}

function generate_contents(){
  //to import the default content during installation
  \Drupal::service('default_content.importer')->importContent('content_export');
}

function techaspect_form_alter(&$form, \Drupal\Core\Form\FormStateInterface $form_state, $form_id){
  if($form_id=='techaspect_social_login_configuration')
  {

  }
}