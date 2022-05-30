<?php

namespace Drupal\featured_image\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\featured_image\FeaturedImageInterface;
use Drupal\user\UserInterface;

/**
 * Defines the featured_image entity class.
 *
 * @ContentEntityType(
 *   id = "featured_image",
 *   label = @Translation("Featured Image"),
 *   handlers = {
 *     "access" = "\Drupal\featured_image\FeaturedImageAccessControlHandler",
 *     "storage" = "Drupal\featured_image\FeaturedImageStorage",
 *     "translation" = "Drupal\content_translation\ContentTranslationHandler",
 *     "list_builder" = "Drupal\featured_image\FeaturedImageListBuilder",
 *     "view_builder" = "Drupal\featured_image\FeaturedImageViewBuilder",
 *     "views_data" = "Drupal\featured_image\FeaturedImageViewData",
 *     "form" = {
 *       "default" = "Drupal\featured_image\Form\FeaturedImageForm",
 *       "edit" = "Drupal\featured_image\Form\FeaturedImageForm",
 *       "delete" = "Drupal\featured_image\Form\FeaturedImageDeleteForm",
 *       "delete_items" = "Drupal\featured_image\Form\FeaturedImageItemsDeleteForm",
 *     }
 *   },
 *   links = {
 *     "canonical" = "/featured_image/{featured_image}",
 *     "edit-form" = "/featured_image/{featured_image}/edit",
 *     "delete-form" = "/featured_image/{featured_image}/delete"
 *   },
 *   base_table = "featured_image",
 *   data_table = "featured_image_field_data",
 *   admin_permission = "administer featured_images",
 *   field_ui_base_route = "featured_image.settings",
 *   translatable = TRUE,
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "title",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode"
 *   }
 * )
 */
class FeaturedImage extends ContentEntityBase implements FeaturedImageInterface {

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
  public function getImageUrl() {
    $path = '';
    if($this->get('image')) {
      $uri = $this->get('image')->entity->getFileUri();
      $path = \Drupal\image\Entity\ImageStyle::load('medium')->buildUrl($uri);
    }
    return $path;
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
    ->setLabel(t('Featured Image ID'))
    ->setDescription(t('The ID of the featured_image.'))
    ->setReadOnly(TRUE)
    ->setSetting('unsigned', TRUE);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
    ->setLabel(t('Author'))
    ->setDescription(t('The featured_image author.'))
    ->setSetting('target_type', 'user')
    ->setTranslatable(TRUE)
    ->setDefaultValueCallback('Drupal\featured_image\Entity\FeaturedImage::getCurrentUserId')
    ->setDisplayOptions('form', array(
      'type' => 'entity_reference_autocomplete',
      'weight' => -10,
      'settings' => array(
        'match_operator' => 'CONTAINS',
        'size' => '60',
        'autocomplete_type' => 'tags',
        'placeholder' => '',
      ),
    ))
    ->setDisplayConfigurable('form', TRUE);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
    ->setLabel(t('UUID'))
    ->setDescription(t('The featured_image UUID.'))
    ->setReadOnly(TRUE);

    $fields['title'] = BaseFieldDefinition::create('string')
    ->setLabel(t('Title'))
    ->setDescription(t('The featured_image title.'))
    ->setRequired(TRUE)
    ->setTranslatable(TRUE)
    ->setSetting('max_length', 255)
    ->setDisplayOptions('form', array(
      'type' => 'string_textfield',
      'weight' => -100,
    ))
    ->setDisplayConfigurable('form', TRUE);


    $fields['image_type'] = BaseFieldDefinition::create('list_string')
    ->setLabel(t('Image Type'))
    ->setDescription(t('Image display as banner or featured image.'))
    ->setDefaultValue(200)
    ->setSettings([
      'allowed_values' => [
        'featured' => 'Featured Image',
        'banner' => 'Banner Image',
      ],
    ])
    ->setDefaultValue('featured')
    ->setDisplayOptions('view', [
      'label' => 'visible',
      'type' => 'list_default',
      'weight' => 1,
    ])
    ->setDisplayOptions('form', [
      'type' => 'options_select',
      'weight' => 1,
    ])
    ->setDisplayConfigurable('view', TRUE)
    ->setDisplayConfigurable('form', TRUE);

    $fields['image'] = BaseFieldDefinition::create('image')
    ->setLabel(t('Image'))
    ->setDescription(t('Image field'))
    ->setSettings([
      'file_directory' => 'public://content-images/'.date('Y-m-d').'/',
      'alt_field_required' => FALSE,
      'file_extensions' => 'png jpg jpeg',
    ])
    ->setDisplayOptions('view', array(
      'label' => 'hidden',
      'type' => 'default',
      'weight' => 2,
    ))
    ->setDisplayOptions('form', array(
      'label' => 'hidden',
      'type' => 'image_image',
      'weight' => 2,
    ))
    ->setDisplayConfigurable('form', TRUE)
    ->setDisplayConfigurable('view', TRUE);

    $fields['langcode'] = BaseFieldDefinition::create('language')
    ->setLabel(t('Language code'))
    ->setDescription(t('The featured_image language code.'));

    $fields['status'] = BaseFieldDefinition::create('boolean')
    ->setLabel(t('Active'))
    ->setDescription(t('A flag indicating whether the featured_image is active.'))
    ->setDefaultValue(1)
    ->setDisplayOptions('form', array(
      'type' => 'boolean_checkbox',
      'settings' => array(
        'display_label' => TRUE,
      ),
      'weight' => 3,
    ))
    ->setDisplayConfigurable('form', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
    ->setLabel(t('Created'))
    ->setDescription(t('When the featured_image was created, as a Unix timestamp.'));

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
