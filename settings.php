<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

add_action('admin_menu', 'plugin_admin_add_page_jobs');
function plugin_admin_add_page_jobs() {
  add_options_page('Jobs Settings Page', 'Jobs Settings', 'manage_options', 'p_jobs', 'plugin_options_page_jobs');
}

function plugin_options_page_jobs() {
  ?>
  <h2>Jobs Settings</h2>
  <p>Save access token ( valid for 60 days max ) here.</p>
  <form action="options.php" method="post">
  <?php settings_fields('jobs_plugin_options'); ?>
  <?php do_settings_sections('jobs_setting'); ?>
  <input class="button button-primary" name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
  </form>
  <?php

}
add_action('admin_init', 'plugin_admin_init_jobs');
function plugin_admin_init_jobs(){
  register_setting( 'jobs_plugin_options', 'jobs_plugin_options', 'jobs_plugin_options_validate' );
  add_settings_section('plugin_main', 'Main Settings', 'plugin_section_text', 'jobs_setting');
  add_settings_field('company_id', 'Company id', 'plugin_setting_company_id', 'jobs_setting', 'plugin_main');
  add_settings_field('client_secret', 'Client secret', 'plugin_setting_client_sec', 'jobs_setting', 'plugin_main');
  add_settings_field('client_id', 'Client id', 'plugin_setting_client_id', 'jobs_setting', 'plugin_main');
  add_settings_field('access_code', 'Code ( valid for 60 days )', 'plugin_setting_client_code', 'jobs_setting', 'plugin_main');
}

function plugin_section_text() {
  echo '<p>Main description of this section here.</p>';
}
function plugin_setting_company_id(){
  $options = get_option('jobs_plugin_options');
  echo "<input id='company_id' name='jobs_plugin_options[company_id]' size='40' type='text' value='{$options['company_id']}' />";
}
function plugin_setting_client_code(){
  $options = get_option('jobs_plugin_options');
  echo "<textarea id='access_code' name='jobs_plugin_options[access_code]' rows='5' cols='100'>{$options['access_code']}</textarea>";
}
function plugin_setting_client_id() {
  $options = get_option('jobs_plugin_options');
  echo "<input id='client_id' name='jobs_plugin_options[client_id]' size='40' type='text' value='{$options['client_id']}' />";
}
function plugin_setting_client_sec() {
  $options = get_option('jobs_plugin_options');
  echo "<input id='client_secret' name='jobs_plugin_options[client_secret]' size='40' type='text' value='{$options['client_secret']}' />";
}

function jobs_plugin_options_validate($input) {
  $newinput['client_id'] = trim($input['client_id']);
  $newinput['client_secret'] = trim($input['client_secret']);
  $newinput['access_code'] = trim($input['access_code']);
  $newinput['company_id'] = trim($input['company_id']);
  // if(!preg_match('/^[a-z0-9]{32}$/i', $newinput['client_id'])) {
  //   $newinput['client_id'] = '';
  // }
  return $newinput;
}
