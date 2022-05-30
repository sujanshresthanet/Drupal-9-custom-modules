<?php

/**
 * @file
 * Builds placeholder replacement tokens for values specific to Blog nodes.
 */

use Drupal\Core\Render\BubbleableMetadata;

/**
 * Implements hook_token_info().
 */
function blog_token_info() {
  $node['duration'] = array(
    'name' => t("Blog duration"),
    'description' => t("The length of time the blog is set to run."),
  );

  return array(
    'tokens' => array('blog' => $node),
  );
}

/**
 * Implements hook_tokens().
 */
function blog_tokens($type, $tokens, array $data, array $options, BubbleableMetadata $bubbleable_metadata) {
  $replacements = array();

  if ($type == 'blog' && !empty($data['blog'])) {
    /** @var \Drupal\blog\Entity\Blog $blog */
    $blog = $data['blog'];

    foreach ($tokens as $name => $original) {
      switch ($name) {

        case 'duration':
        $replacements[$original] = \Drupal::service('date.formatter')->formatInterval($blog->getRuntime());
        break;
      }
    }
  }

  return $replacements;
}
