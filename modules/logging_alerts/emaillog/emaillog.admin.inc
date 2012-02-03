<?php

/**
 * @file
 *   Admin callbacks for the Email Logging and Alerts module.
 */

/**
 * Returns admin settings form.
 */
function emaillog_admin_settings() {
  $severity_levels = watchdog_severity_levels();
  $form['emaillog'] = array(
    '#type'           => 'fieldset',
    '#title'          => t('Email addresses for each severity level'),
    '#description'    => t('Enter an email address for each severity level. For example, you may want to get emergency and critical levels to your pager or mobile phone, while notice level messages can go to a regular email. If you leave the email address blank for a severity level, no email messages will be sent for that severity level.'),
    '#collapsible'    => TRUE,
    '#collapsed'      => FALSE,
  );
  foreach ($severity_levels as $severity => $description) {
    $key = 'emaillog_' . $severity;
    $form['emaillog'][$key] = array(
      '#type'           => 'textfield',
      '#title'          => t('Email address for severity %description', array('%description' => drupal_ucfirst($description))),
      '#default_value'  => variable_get($key, NULL),
      '#description'    => t('The email address to send log entries of severity %description to.', array('%description' => drupal_ucfirst($description))),
    );
  }
  
  $form['debug_info'] = array(
    '#type'           => 'fieldset',
    '#title'          => t('Additional debug info'),
    '#description'    => t('Additional debug information that should be attached to email alerts. Note that this information could be altered by other modules using <em>hook_emaillog_debug_info_alter(&$debug_info)</em>'),
    '#collapsible'    => TRUE,
    '#collapsed'      => TRUE,
  );
  $debug_info_settings = variable_get('emaillog_debug_info', NULL);
  foreach (_emaillog_get_debug_info_callbacks() as $debug_info_key => $debug_info_callback) {
    $options[$debug_info_key] = '';
    $form['debug_info']['variable'][$debug_info_key] = array(
      '#value' => $debug_info_callback,
    );
    foreach ($severity_levels as $level_id => $level) {
      // Builds arrays for checked boxes for each role
      if (isset($debug_info_settings[$level_id][$debug_info_key]) && $debug_info_settings[$level_id][$debug_info_key]) {
        $status[$level][] = $debug_info_key;
      }
    }
  }
  foreach ($severity_levels as $level_id => $description) {
    $level = drupal_strtolower($description);
    $form['debug_info']['checkboxes'][$level] = array(
      '#type'           => 'checkboxes',
      '#options'        => $options,
      '#default_value'  => isset($status[$level]) ? $status[$level] : array(),
    );
    $form['debug_info']['severity_levels'][$level] = array('#value' => check_plain($description), '#tree' => TRUE);
  }
  $form['debug_info']['emaillog_backtrace_replace_args'] = array(
    '#type'           => 'checkbox',
    '#title'          => t('Replace debug_backtrace() argument values with types'),
    '#description'    => t('By default <em>debug_backtrace()</em> will return full variable information in the stack traces that it produces. Variable information can take quite a bit of resources, both while collecting and adding to the alert email, therefore here by default all variable values are replaced with their types only. Warning - unchecking this option could cause your site to crash when it tries to send an alert email with too big stack trace!'),
    '#default_value'  => variable_get('emaillog_backtrace_replace_args', TRUE),
    '#weight'         => 1,
  );

  $form['limits'] = array(
    '#type' => 'fieldset',
    '#title'          => t('Email sending limits'),
    '#collapsible'    => TRUE,
    '#collapsed'      => TRUE,
  );
  $form['limits']['emaillog_max_similar_emails'] = array(
    '#type'           => 'textfield',
    '#title'          => t('Maximum number of allowed consecutive similar email alerts'),
    '#description'    => t('Upper limit of email alerts sent consecutively with the same or very similar message. Leave empty for no limit.'),
    '#default_value'  => variable_get('emaillog_max_similar_emails', NULL),
  );
  $form['limits']['emaillog_max_consecutive_timespan'] = array(
    '#type'           => 'textfield',
    '#title'          => t('Email alerts should be considered "consecutive" if sent within'),
    '#field_suffix'   => t('minutes from each other'),
    '#description'    => t('Longest possible period between two email alerts being sent to still be considered consecutive. Leave empty for no limit.'),
    '#default_value'  => variable_get('emaillog_max_consecutive_timespan', NULL),
  );
  $form['limits']['emaillog_max_similarity_level'] = array(
    '#type'           => 'textfield',
    '#title'          => t('Maximum allowed similarity level between consecutive email alerts'),
    '#description'    => '<p>' . t('Highest similarity level above which new email alerts will not be sent anymore if "Maximum number of allowed consecutive similar email alerts" has been reached and email alerts are considered "consecutive" (time period between each previous and next one is smaller than defined above). Possible values range from 0 to 1, where stands for two identical emails.') . '</p>'
                       . '<p>' . t('For example setting "Maximum number of allowed consecutive similar email alerts" to 5, "Email alerts should be considered consecutive if sent within" to 5 minutes and "Similarity level" to 0.9 would mean that only 5 email alerts would be sent within 5 minutes if Watchdog entries are similar in at least 90%.') . '</p>',
    '#default_value'  => variable_get('emaillog_max_similarity_level', NULL),
  );
  
  $form['legacy'] = array(
    '#type' => 'fieldset',
    '#title'          => t('Legacy settings'),
    '#collapsible'    => TRUE,
    '#collapsed'      => TRUE,
  );
  $form['legacy']['emaillog_legacy_subject'] = array(
    '#type'           => 'checkbox',
    '#title'          => t('Use legacy email subject'),
    '#description'    => t('Older versions of this module were using email subject "%subject", while currently it is being set to beginning of Watchdog message. This option allows to switch back to previous version of email subject.', array(
      '%subject'        => t('[@site_name] @severity_desc: Alert from your web site'),
    )),
    '#default_value'  => variable_get('emaillog_legacy_subject', FALSE),
  );

  $form['#validate'][] = 'emaillog_admin_settings_validate';
  $form['#submit'][] = 'emaillog_admin_settings_submit';
  
  // We need our own theme function to put all checkboxes in the table.
  $form = system_settings_form($form);
  $form['#theme'] = 'emaillog_admin_settings';
  return $form;
}

/**
 * Themes admin settings form.
 */
function theme_emaillog_admin_settings($form) {

  $severity_levels = watchdog_severity_levels();
  foreach (element_children($form['debug_info']['variable']) as $key) {
    $row = array();
    // Permission row.
    $row[] = array(
      'data' => drupal_render($form['debug_info']['variable'][$key]),
      'class' => 'variable',
    );
    foreach (element_children($form['debug_info']['checkboxes']) as $level) {
      $form['debug_info']['checkboxes'][$level][$key]['#attributes']['title'] = $level . ' : ' . $form['debug_info']['variable'][$key]['#value'];
      $form['debug_info']['checkboxes'][$level][$key]['#title_display'] = 'invisible';
      $row[] = array('data' => drupal_render($form['debug_info']['checkboxes'][$level][$key]), 'class' => 'checkbox');
    }
    $rows[] = $row;
  }
  $header = array('');
  foreach (element_children($form['debug_info']['severity_levels']) as $level) {
    if (is_array($form['debug_info']['severity_levels'][$level])) {
      $header[] = array('data' => drupal_render($form['debug_info']['severity_levels'][$level]), 'class' => 'checkbox');
    }
  }
  
  // Put the table with all checkboxes inside a fieldset.
  $form['debug_info']['variables'] = array(
    '#type'   => 'markup',
    '#value' => theme('table', $header, $rows, array('id' => 'debug_info')),
  );

  // Render and return all remaining elements.
  return drupal_render($form);
}

/**
 * Validates admin settings form.
 */
function emaillog_admin_settings_validate($form, &$form_state) {
  if ($form_state['values']['emaillog_max_similar_emails'] && !$form_state['values']['emaillog_max_similarity_level']) {
    form_set_error('emaillog_max_similarity_level', t('You need to provide value for %field1 field when specifying %field2.', array(
      '%field1' => 'Maximum allowed similarity level between consecutive email alerts',
      '%field2' => 'Maximum number of allowed consecutive similar emails',
    )));
  }
  if ($form_state['values']['emaillog_max_similarity_level'] && !$form_state['values']['emaillog_max_similar_emails']) {
    form_set_error('emaillog_max_similar_emails', t('You need to provide value for %field1 field when specifying %field2.', array(
      '%field1' => 'Maximum number of allowed consecutive similar emails',
      '%field2' => 'Maximum allowed similarity level between consecutive email alerts',
    )));
  }
  if ($form_state['values']['emaillog_max_consecutive_timespan'] && !$form_state['values']['emaillog_max_similar_emails']) {
    form_set_error('emaillog_max_similar_emails', t('You need to provide value for %field1 field when specifying %field2.', array(
      '%field1' => 'Maximum number of allowed consecutive similar emails',
      '%field2' => 'Email alerts should be considered "consecutive" if sent within',
    )));
  }
  if ($form_state['values']['emaillog_max_consecutive_timespan'] && !$form_state['values']['emaillog_max_similarity_level']) {
    form_set_error('emaillog_max_similarity_level', t('You need to provide value for %field1 field when specifying %field2.', array(
      '%field1' => 'Maximum allowed similarity level between consecutive email alerts',
      '%field2' => 'Email alerts should be considered "consecutive" if sent within',
    )));
  }
  if ($form_state['values']['emaillog_max_similarity_level']) {
    if (!is_numeric($form_state['values']['emaillog_max_similarity_level'])) {
      form_set_error('emaillog_max_similarity_level', t('Value of %field cannot contain any non-numeric characters.', array(
        '%field' => 'Maximum allowed similarity level between consecutive email alerts',
      )));
    }
    if ($form_state['values']['emaillog_max_similarity_level'] < 0 || $form_state['values']['emaillog_max_similarity_level'] > 1) {
      form_set_error('emaillog_max_similarity_level', t('Value of %field needs to be in [0-1] range.', array(
        '%field' => 'Maximum allowed similarity level between consecutive email alerts',
      )));
    }
  }
}

/**
 * Saves additional debug info from submitted admin settings form.
 */
function emaillog_admin_settings_submit($form, &$form_state) {
  $severity_levels = watchdog_severity_levels();
  $debug_info = array();
  foreach ($severity_levels as $level_id => $level) {
    foreach (array_keys(_emaillog_get_debug_info_callbacks()) as $variable_id) {
      $debug_info[$level_id][$variable_id] = ($form_state['values'][$level][$variable_id]) ? 1 : 0;
    }
    unset($form_state['values'][$level]);
  }
  variable_set('emaillog_debug_info', $debug_info);
}