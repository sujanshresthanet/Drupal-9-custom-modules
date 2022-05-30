<?php

namespace Drupal\blog\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Most recent blog' block.
 *
 * @Block(
 *   id = "blog_recent_block",
 *   admin_label = @Translation("Most recent blog"),
 *   category = @Translation("Lists (Views)")
 * )
 */
class BlogRecentBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Construct a new BlogRecentBlock object.
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
    return AccessResult::allowedIfHasPermission($account, 'access blogs');
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    return array('blog_list');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $blogs = $this->entityTypeManager->getStorage('blog')->getMostRecentBlog();
    if ($blogs) {
      $blog = reset($blogs);
      // If we're viewing this blog, don't show this block.
//      $page = \Drupal::request()->attributes->get('blog');
//      if ($page instanceof BlogInterface && $page->id() == $blog->id()) {
//        return;
//      }
      // @todo: new view mode using ajax
      $build = $this->entityTypeManager->getViewBuilder('blog')->view($blog, 'block');
      $build['#title'] = $blog->label();
    }

    return $build;
  }

}
