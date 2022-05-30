<?php

namespace Drupal\news_article\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Most recent news_article' block.
 *
 * @Block(
 *   id = "news_article_recent_block",
 *   admin_label = @Translation("Most recent news_article"),
 *   category = @Translation("Lists (Views)")
 * )
 */
class NewsArticleRecentBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Construct a new NewsArticleRecentBlock object.
   *
   * @param array $configuration
   *   The plugin configuration, i.e. an array with configuration values keyed
   *   by configuration option name. The special key 'context' may be used to
   *   initialize the defined contexts by setting it to an array of context
   *   values keyed by context names.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct($configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'access news_articles');
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    return array('news_article_list');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $news_articles = $this->entityTypeManager->getStorage('news_article')->getMostRecentNewsArticle();
    if ($news_articles) {
      $news_article = reset($news_articles);
      // If we're viewing this news_article, don't show this block.
//      $page = \Drupal::request()->attributes->get('news_article');
//      if ($page instanceof NewsArticleInterface && $page->id() == $news_article->id()) {
//        return;
//      }
      // @todo: new view mode using ajax
      $build = $this->entityTypeManager->getViewBuilder('news_article')->view($news_article, 'block');
      $build['#title'] = $news_article->label();
    }

    return $build;
  }

}
