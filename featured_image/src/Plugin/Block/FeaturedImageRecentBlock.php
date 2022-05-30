<?php

namespace Drupal\featured_image\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Most recent featured_image' block.
 *
 * @Block(
 *   id = "featured_image_recent_block",
 *   admin_label = @Translation("Most recent featured_image"),
 *   category = @Translation("Lists (Views)")
 * )
 */
class FeaturedImageRecentBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Construct a new FeaturedImageRecentBlock object.
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
    return AccessResult::allowedIfHasPermission($account, 'access featured_images');
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    return array('featured_image_list');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $featured_images = $this->entityTypeManager->getStorage('featured_image')->getMostRecentFeaturedImage();
    if ($featured_images) {
      $featured_image = reset($featured_images);
      // If we're viewing this featured_image, don't show this block.
//      $page = \Drupal::request()->attributes->get('featured_image');
//      if ($page instanceof FeaturedImageInterface && $page->id() == $featured_image->id()) {
//        return;
//      }
      // @todo: new view mode using ajax
      $build = $this->entityTypeManager->getViewBuilder('featured_image')->view($featured_image, 'block');
      $build['#title'] = $featured_image->label();
    }

    return $build;
  }

}
