<?php
/*
Plugin Name: Linked in Jobs
Description: Use shortcode to show linkedin jobs added in your company account/page.
Version: 1.0.0
*/
/*https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id=123456789&redirect_uri=https%3A%2F%2Fwww.example.com%2Fauth%2Flinkedin&state=987654321&scope=r_basicprofile

Step 1 - https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id=868upln3rk99rn&redirect_uri=https://www.luminanetworks.com/linkedincallback&state=DCEeFWf45A53sdfKef424776885600&scope=rw_company_admin
Step 2 - get code params value and generate access token within 20 secods in postman call
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
include 'settings.php';
$client_id = '*********';
$client_secret = '*************';

function footag_func_jobs( $atts ) {
  $options = get_option('jobs_plugin_options');
  $headers = array(
    'headers' => array(
        'Authorization' => 'Bearer '.$options['access_code']
      )
    );

  $response = wp_remote_get( 'https://api.linkedin.com/v1/companies/18133819/updates?format=json&event-type=job-posting', $headers );
  if ( is_array( $response ) ) {
    $header = $response['headers']; // array of http header lines
    $body = json_decode($response['body']); // use the content
    $html = '';
    $i = 1;
    foreach ( $body->values as $job ) {
      $class = ( $i % 2 ? '': 'et-last-child' );
      $i++;
      $html .='<a target="_blank" href="'.$job->updateContent->companyJobUpdate->job->siteJobRequest->url.'" class="item et_pb_column et_pb_column_1_2 '.$class.'">';
      $html .='<h4>'.$job->updateContent->companyJobUpdate->job->position->title .'</h4>';
      $html .='<p><span class="location" data-icon="&#xe01d;"></span>'.$job->updateContent->companyJobUpdate->job->locationDescription .'</p>';
      //$more ='... <a target="_blank" href="'.$job->updateContent->companyJobUpdate->job->siteJobRequest->url.'">Click for Details</a>';
      //$more ='...';
      //$html .='<p>Description: '.nl2br($job->updateContent->companyJobUpdate->job->description).$more.'</p>';
      //$html .='<a href="'.$job->updateContent->companyJobUpdate->job->siteJobRequest->url.'">Click to read more and apply</a>';
      $html .='</a>';
    }
  }
	return "<div id='joblist'>$html<div style='clear:both;'></div></div>";
}
add_shortcode( 'linkedinjobs', 'footag_func_jobs' );
