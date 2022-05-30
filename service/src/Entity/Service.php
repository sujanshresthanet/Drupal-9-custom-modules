<?php

namespace Drupal\service\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\service\ServiceInterface;
use Drupal\user\UserInterface;

/**
 * Defines the service entity class.
 *
 * @ContentEntityType(
 *   id = "service",
 *   label = @Translation("Service"),
 *   handlers = {
 *     "access" = "\Drupal\service\ServiceAccessControlHandler",
 *     "storage" = "Drupal\service\ServiceStorage",
 *     "translation" = "Drupal\content_translation\ContentTranslationHandler",
 *     "list_builder" = "Drupal\service\ServiceListBuilder",
 *     "view_builder" = "Drupal\service\ServiceViewBuilder",
 *     "views_data" = "Drupal\service\ServiceViewData",
 *     "form" = {
 *       "default" = "Drupal\service\Form\ServiceForm",
 *       "edit" = "Drupal\service\Form\ServiceForm",
 *       "delete" = "Drupal\service\Form\ServiceDeleteForm",
 *       "delete_items" = "Drupal\service\Form\ServiceItemsDeleteForm",
 *     }
 *   },
 *   links = {
 *     "canonical" = "/service/{service}",
 *     "edit-form" = "/service/{service}/edit",
 *     "delete-form" = "/service/{service}/delete"
 *   },
 *   base_table = "service",
 *   data_table = "service_field_data",
 *   admin_permission = "administer services",
 *   field_ui_base_route = "service.settings",
 *   translatable = TRUE,
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "title",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode"
 *   }
 * )
 */
class Service extends ContentEntityBase implements ServiceInterface {

  /**
   * {@inheritdoc}
   */
  public function setTitle($title) {
    $this->set('title', $title);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreated($created) {
    $this->set('created', $created);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreated() {
    return $this->get('created')->value;
  }


  /**
   * {@inheritdoc}
   */
  public function isOpen() {
    return (bool) $this->get('status')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('uid')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('uid')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('uid', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('uid', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isClosed() {
    return (bool) $this->get('status')->value == 0;
  }

  /**
   * {@inheritdoc}
   */
  public function close() {
    return $this->set('status', 0);
  }

  /**
   * {@inheritdoc}
   */
  public function open() {
    return $this->set('status', 1);
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields['id'] = BaseFieldDefinition::create('integer')
    ->setLabel(t('Service ID'))
    ->setDescription(t('The ID of the service.'))
    ->setReadOnly(TRUE)
    ->setSetting('unsigned', TRUE);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
    ->setLabel(t('UUID'))
    ->setDescription(t('The service UUID.'))
    ->setReadOnly(TRUE);

    $fields['title'] = BaseFieldDefinition::create('string')
    ->setLabel(t('Title'))
    ->setDescription(t('The service title.'))
    ->setRequired(TRUE)
    ->setTranslatable(TRUE)
    ->setSetting('max_length', 255)
    ->setDisplayOptions('form', array(
      'type' => 'string_textfield',
      'weight' => 1,
    ))
    ->setDisplayConfigurable('form', TRUE);


    $fields['short_description'] = BaseFieldDefinition::create('string_long')
    ->setLabel(t('Short Description'))
    ->setDescription(t('Add short description field.'))
    ->setDefaultValue('')
    ->setRequired(FALSE)
    ->setDisplayOptions('view', [
      'label' => 'visible',
      'type' => 'basic_string',
      'weight' => 2,
    ])
    ->setDisplayOptions('form', [
      'type' => 'string_textarea',
      'weight' => 2,
      'settings' => ['rows' => 4],
    ])
    ->setDisplayConfigurable('view', TRUE)
    ->setDisplayConfigurable('form', TRUE);

    $fields['text_long'] = BaseFieldDefinition::create('text_long')
    ->setLabel(t('Content (formatted, long)'))
    ->setDescription(t('Content'))
    ->setDisplayOptions('view', [
      'label' => 'visible',
      'type' => 'text_long',
      'weight' => 2,
    ])
    ->setDisplayOptions('form', [
      'type' => 'text_long',
      'weight' => 2,
      'rows' => 6,
    ])
    ->setDisplayConfigurable('view', TRUE)
    ->setDisplayConfigurable('form', TRUE);


    $fields['featured_image'] = BaseFieldDefinition::create('entity_reference')
    ->setLabel(t('Featured Image'))
    ->setDescription(t('The Featured Image for this service.'))
    ->setSetting('target_type', 'featured_image')
    ->setSetting('handler', 'default')
    ->setTargetEntityTypeId('featured_image')
    ->setDisplayOptions('view', array(
      'label'  => 'hidden',
      'type'   => 'featured_image',
      'weight' => 0,
    ))
    ->setDisplayOptions('view', array(
      'label' => 'above',
      'type' => 'list_default',
      'weight' => 4,
    ))
    ->setDisplayOptions('form', array(
      'type' => 'options_select',
      'settings' => array(
        'match_operator' => 'CONTAINS',
        'size' => 60,
        'placeholder' => '',
      ),
      'weight' => 4,
    ))
    ->setDisplayConfigurable('form', TRUE)
    ->setDisplayConfigurable('view', TRUE);

    $fields['banner_image'] = BaseFieldDefinition::create('entity_reference')
    ->setLabel(t('Banner Image'))
    ->setDescription(t('The Banner Image for this service.'))
    ->setSetting('target_type', 'featured_image')
    ->setSetting('handler', 'default')
    ->setTargetEntityTypeId('featured_image')
    ->setDisplayOptions('view', array(
      'label'  => 'hidden',
      'type'   => 'featured_image',
      'weight' => 0,
    ))
    ->setDisplayOptions('view', array(
      'label' => 'above',
      'type' => 'list_default',
      'weight' => 5,
    ))
    ->setDisplayOptions('form', array(
      'type' => 'options_select',
      'settings' => array(
        'match_operator' => 'CONTAINS',
        'size' => 60,
        'placeholder' => '',
      ),
      'weight' => 5,
    ))
    ->setDisplayConfigurable('form', TRUE)
    ->setDisplayConfigurable('view', TRUE);

    $fields['link'] = BaseFieldDefinition::create('string')
    ->setLabel(t('Link'))
    ->setDescription(t('The service link.'))
    ->setRequired(FALSE)
    ->setTranslatable(TRUE)
    ->setSetting('max_length', 255)
    ->setDisplayOptions('form', array(
      'type' => 'string_textfield',
      'weight' => 6,
    ))
    ->setDisplayConfigurable('form', TRUE);

    $fields['weight'] = BaseFieldDefinition::create('integer')
    ->setLabel(t('Weight'))
    ->setDescription(t('Weight of the slider'))
    ->setTranslatable(TRUE)
    ->setRequired(false)
    ->setDefaultValue(0)
    ->setDisplayOptions('view', array(
      'label' => 'above',
      'weight' => 7,
    ))
    ->setDisplayOptions('form', array(
      'weight' => 7,
    ))
    ->setDisplayConfigurable('form', true)
    ->setDisplayConfigurable('view', true);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
    ->setLabel(t('Author'))
    ->setDescription(t('The service author.'))
    ->setSetting('target_type', 'user')
    ->setTranslatable(TRUE)
    ->setDefaultValueCallback('Drupal\service\Entity\Service::getCurrentUserId')
    ->setDisplayOptions('form', array(
      'type' => 'entity_reference_autocomplete',
      'weight' => 8,
      'settings' => array(
        'match_operator' => 'CONTAINS',
        'size' => '60',
        'autocomplete_type' => 'tags',
        'placeholder' => '',
      ),
    ))
    ->setDisplayConfigurable('form', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
    ->setLabel(t('Active'))
    ->setDescription(t('A flag indicating whether the service is active.'))
    ->setDefaultValue(1)
    ->setDisplayOptions('form', array(
      'type' => 'boolean_checkbox',
      'settings' => array(
        'display_label' => TRUE,
      ),
      'weight' => 9,
    ))
    ->setDisplayConfigurable('form', TRUE);

    $fields['langcode'] = BaseFieldDefinition::create('language')
    ->setLabel(t('Language code'))
    ->setDescription(t('The service language code.'));

    $fields['created'] = BaseFieldDefinition::create('created')
    ->setLabel(t('Created'))
    ->setDescription(t('When the service was created, as a Unix timestamp.'));

    return $fields;
  }

  /**
   * Default value callback for 'uid' base field definition.
   *
   * @see ::baseFieldDefinitions()
   *
   * @return array
   *   An array of default values.
   */
  public static function getCurrentUserId() {
    return array(\Drupal::currentUser()->id());
  }

  /**
   *
   * {@inheritdoc}
   */
  public static function sort($a, $b) {
    return strcmp($a->label(), $b->label());
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);
  }

  /**
   * {@inheritdoc}
   */
  public static function postDelete(EntityStorageInterface $storage, array $entities) {
    parent::postDelete($storage, $entities);

    // codes here
  }
}
