<?php

/**
 * @file
 * Builds placeholder replacement tokens for values specific to NewsArticle nodes.
 */

use Drupal\Core\Render\BubbleableMetadata;

/**
 * Implements hook_token_info().
 */
function news_article_token_info() {
  $node['duration'] = array(
    'name' => t("NewsArticle duration"),
    'description' => t("The length of time the news_article is set to run."),
  );

  return array(
    'tokens' => array('news_article' => $node),
  );
}

/**
 * Implements hook_tokens().
 */
function news_article_tokens($type, $tokens, array $data, array $options, BubbleableMetadata $bubbleable_metadata) {
  $replacements = array();

  if ($type == 'news_article' && !empty($data['news_article'])) {
    /** @var \Drupal\news_article\Entity\NewsArticle $news_article */
    $news_article = $data['news_article'];

    foreach ($tokens as $name => $original) {
      switch ($name) {

        case 'duration':
        $replacements[$original] = \Drupal::service('date.formatter')->formatInterval($news_article->getRuntime());
        break;
      }
    }
  }

  return $replacements;
}
