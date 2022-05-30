<?php

/**
 * Contains \Drupal\blog\BlogListBuilder.
 */

namespace Drupal\blog;

use Drupal;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Config\Entity\DraggableListBuilder;

/**
 * Defines a class to build a listing of user role entities.
 *
 * @see \Drupal\user\Entity\Role
 */
class BlogListBuilder extends DraggableListBuilder {

  /**
   * {@inheritdoc}
   */
  public function load() {
    $entities = $this->storage->loadMultiple();

    // Sort the entities using the entity class's sort() method.
    // See \Drupal\Core\Config\Entity\ConfigEntityBase::sort().
    uasort($entities, array($this->entityType->getClass(), 'sort'));
    return $entities;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'blog_list_form';
  }

  /**
   * Overrides Drupal\Core\Entity\EntityListController::buildHeader().
   */
  public function buildHeader() {

    $header['title'] = t('Title');
    $header['author'] = t('Author');
    $header['status'] = t('Status');
    $header['created'] = t('Created');
    $header['operations'] = t('Operations');
    return $header + parent::buildHeader();
  }

  /**
   * Overrides Drupal\Core\Entity\EntityListController::buildRow().
   */
  public function buildRow(EntityInterface $entity) {
    $row['title'] = $entity->toLink()->toString();
    $row['author']['data'] = array(
      '#theme' => 'username',
      '#account' => $entity->getOwner(),
    );
    $row['status'] = ($entity->isOpen()) ? t('Y') : t('N');
    $row['created'] = ($entity->getCreated()) ? Drupal::service('date.formatter')
    ->format($entity->getCreated(), 'long') : t('n/a');
    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function getOperations(EntityInterface $entity) {
    $operations = parent::getOperations($entity);

    return $operations;
  }

}
