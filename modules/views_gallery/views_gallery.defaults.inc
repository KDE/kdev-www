<?php

/**
 * Helper to implemetation of hook_content_default_fields().
 */
function _views_gallery_content_default_fields() {
  $fields = array();

  // Exported field: field_gallery
  $fields[] = array(
    'field_name' => 'field_gallery',
    'type_name' => 'photo',
    'display_settings' => array(
      'weight' => '-2',
      'parent' => '',
      '4' => array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'label' => array(
        'format' => 'inline',
      ),
      'teaser' => array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'full' => array(
        'format' => 'default',
        'exclude' => 0,
      ),
    ),
    'widget_active' => '1',
    'type' => 'nodereference',
    'required' => '1',
    'multiple' => '0',
    'module' => 'nodereference',
    'active' => '1',
    'referenceable_types' => array(
      'gallery' => 'gallery',
      'photo' => 0,
    ),
    'advanced_view' => '--',
    'advanced_view_args' => '',
    'widget' => array(
      'node_link' => array(
        'full' => 1,
        'teaser' => 0,
        'title' => 'Add photo',
        'hover_title' => '',
        'destination' => 'node',
      ),
      'fallback' => 'select',
      'label' => 'Gallery',
      'weight' => '-2',
      'description' => '',
      'type' => 'nodereference_url',
      'module' => 'nodereference_url',
    ),
  );

  // Exported field: field_image
  $fields[] = array(
    'field_name' => 'field_image',
    'type_name' => 'photo',
    'display_settings' => array(
      'weight' => '-3',
      'parent' => '',
      '4' => array(
        'format' => 'reg_linked',
        'exclude' => 0,
      ),
      'label' => array(
        'format' => 'hidden',
      ),
      'teaser' => array(
        'format' => 'thumb_linked',
        'exclude' => 0,
      ),
      'full' => array(
        'format' => 'reg_imagelink',
        'exclude' => 0,
      ),
    ),
    'widget_active' => '1',
    'type' => 'filefield',
    'required' => '1',
    'multiple' => '0',
    'module' => 'filefield',
    'active' => '1',
    'list_field' => '0',
    'list_default' => 1,
    'description_field' => '0',
    'widget' => array(
      'file_extensions' => 'jpg png gif jpeg',
      'file_path' => 'photos',
      'progress_indicator' => 'bar',
      'max_filesize_per_file' => '',
      'max_filesize_per_node' => '',
      'max_resolution' => 0,
      'min_resolution' => 0,
      'alt' => '',
      'custom_alt' => 0,
      'title' => '',
      'custom_title' => 0,
      'title_type' => 'textfield',
      'default_image' => NULL,
      'use_default_image' => 0,
      'label' => 'Image',
      'weight' => '-3',
      'description' => '',
      'type' => 'imagefield_widget',
      'module' => 'imagefield',
    ),
  );

  return $fields;
}

/**
 * Helper to implemetation of hook_context_default_contexts().
 */
function _views_gallery_context_default_contexts() {
  $items = array();

  $items[] = array(
    'namespace' => 'views_gallery',
    'attribute' => 'section',
    'value' => 'views_gallery',
    'description' => 'A photo gallery built using the Views module.',
    'node' => array(
      '0' => 'gallery',
      '1' => 'photo',
    ),
    'css_injector' => array(
      '1' => 1,
    ),
    'block' => array(),
  );
  return $items;
}

/**
 * Helper to implemetation of hook_imagecache_default_presets().
 */
function _views_gallery_imagecache_default_presets() {
  $items = array(
    'reg' => array(
      'presetname' => 'reg',
      'actions' => array(
        '0' => array(
          'actionid' => '2',
          'presetid' => '1',
          'weight' => '0',
          'module' => 'imagecache',
          'action' => 'imagecache_deprecated_scale',
          'data' => array(
            'fit' => 'inside',
            'width' => '500',
            'height' => '1000',
          ),
        ),
      ),
    ),
    'short' => array(
      'presetname' => 'short',
      'actions' => array(
        '0' => array(
          'actionid' => '5',
          'presetid' => '3',
          'weight' => '0',
          'module' => 'imagecache',
          'action' => 'imagecache_deprecated_scale',
          'data' => array(
            'fit' => 'outside',
            'width' => '150',
            'height' => '150',
          ),
        ),
        '1' => array(
          'actionid' => '6',
          'presetid' => '3',
          'weight' => '0',
          'module' => 'imagecache',
          'action' => 'imagecache_crop',
          'data' => array(
            'width' => '150',
            'height' => '75',
            'xoffset' => 'center',
            'yoffset' => 'center',
          ),
        ),
      ),
    ),
    'thumb' => array(
      'presetname' => 'thumb',
      'actions' => array(
        '0' => array(
          'actionid' => '3',
          'presetid' => '2',
          'weight' => '0',
          'module' => 'imagecache',
          'action' => 'imagecache_deprecated_scale',
          'data' => array(
            'fit' => 'outside',
            'width' => '200',
            'height' => '150',
          ),
        ),
        '1' => array(
          'actionid' => '4',
          'presetid' => '2',
          'weight' => '0',
          'module' => 'imagecache',
          'action' => 'imagecache_crop',
          'data' => array(
            'width' => '200',
            'height' => '150',
            'xoffset' => 'center',
            'yoffset' => 'center',
          ),
        ),
      ),
    ),
  );
  return $items;
}

/**
 * Helper to implemetation of hook_node_info().
 */
function _views_gallery_node_info() {
  $items = array(
    'gallery' => array(
      'name' => t('Gallery'),
      'module' => 'node',
      'description' => t('A collection of photographs maintained by a user.'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'has_body' => '1',
      'body_label' => t('Description'),
    ),
    'photo' => array(
      'name' => t('Photo'),
      'module' => 'node',
      'description' => t('An individual photograph posted by a site user.'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'has_body' => '0',
      'body_label' => t(''),
    ),
  );
  return $items;
}
