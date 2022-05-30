<?php

namespace Drupal\slider\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Most recent slider' block.
 *
 * @Block(
 *   id = "slider_recent_block",
 *   admin_label = @Translation("Most recent slider"),
 *   category = @Translation("Lists (Views)")
 * )
 */
class SliderRecentBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Construct a new SliderRecentBlock object.
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
    return AccessResult::allowedIfHasPermission($account, 'access sliders');
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    return array('slider_list');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $sliders = $this->entityTypeManager->getStorage('slider')->getMostRecentSlider();
    if ($sliders) {
      $slider = reset($sliders);
      // If we're viewing this slider, don't show this block.
//      $page = \Drupal::request()->attributes->get('slider');
//      if ($page instanceof SliderInterface && $page->id() == $slider->id()) {
//        return;
//      }
      // @todo: new view mode using ajax
      $build = $this->entityTypeManager->getViewBuilder('slider')->view($slider, 'block');
      $build['#title'] = $slider->label();
    }

    return $build;
  }

}
