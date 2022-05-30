<?php

namespace Drupal\project;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines an access control handler for the project entity.
 *
 * @see \Drupal\project\Entity\Project
 */
class ProjectAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermissions($account, ['create projects', 'administer projects'], 'OR');
  }

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    // Allow view access if the user has the access projects permission.
    if ($operation == 'view') {
      return AccessResult::allowedIfHasPermission($account, 'access projects');
    }
    elseif ($operation == 'update' && !$account->isAnonymous() && $account->id() == $entity->get('uid')->target_id) {
      return AccessResult::allowedIfHasPermissions($account, [
        'edit own projects',
        'administer projects',
      ], 'OR');
    }
    // Otherwise fall back to the parent which checks the administer projects
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
      return AccessResult::allowedIfHasPermission($account, 'administer projects');
    }
    return parent::checkFieldAccess($operation, $field_definition, $account, $items);
  }
}
