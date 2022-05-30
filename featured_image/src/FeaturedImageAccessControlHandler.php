<?php

namespace Drupal\featured_image;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines an access control handler for the featured_image entity.
 *
 * @see \Drupal\featured_image\Entity\FeaturedImage
 */
class FeaturedImageAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermissions($account, ['create featured_images', 'administer featured_images'], 'OR');
  }

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    // Allow view access if the user has the access featured_images permission.
    if ($operation == 'view') {
      return AccessResult::allowedIfHasPermission($account, 'access featured_images');
    }
    elseif ($operation == 'update' && !$account->isAnonymous() && $account->id() == $entity->get('uid')->target_id) {
      return AccessResult::allowedIfHasPermissions($account, [
        'edit own featured_images',
        'administer featured_images',
      ], 'OR');
    }
    // Otherwise fall back to the parent which checks the administer featured_images
    // permission.
    return parent::checkAccess($entity, $operation, $account);
  }

  /**
   * {@inheritdoc}
   */
  protected function checkFieldAccess($operation, FieldDefinitionInterface $field_definition, AccountInterface $account, FieldItemListInterface $items = NULL) {
    $restricted_fields = [
      'uid',
    ];
    if ($operation === 'edit' && in_array($field_definition->getName(), $restricted_fields, TRUE)) {
      return AccessResult::allowedIfHasPermission($account, 'administer featured_images');
    }
    return parent::checkFieldAccess($operation, $field_definition, $account, $items);
  }
}
