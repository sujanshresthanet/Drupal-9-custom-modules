<?php

namespace Drupal\slider\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\slider\SliderInterface;
use Drupal\user\UserInterface;

/**
 * Defines the slider entity class.
 *
 * @ContentEntityType(
 *   id = "slider",
 *   label = @Translation("Slider"),
 *   handlers = {
 *     "access" = "\Drupal\slider\SliderAccessControlHandler",
 *     "storage" = "Drupal\slider\SliderStorage",
 *     "translation" = "Drupal\content_translation\ContentTranslationHandler",
 *     "list_builder" = "Drupal\slider\SliderListBuilder",
 *     "view_builder" = "Drupal\slider\SliderViewBuilder",
 *     "views_data" = "Drupal\slider\SliderViewData",
 *     "form" = {
 *       "default" = "Drupal\slider\Form\SliderForm",
 *       "edit" = "Drupal\slider\Form\SliderForm",
 *       "delete" = "Drupal\slider\Form\SliderDeleteForm",
 *       "delete_items" = "Drupal\slider\Form\SliderItemsDeleteForm",
 *     }
 *   },
 *   links = {
 *     "canonical" = "/slider/{slider}",
 *     "edit-form" = "/slider/{slider}/edit",
 *     "delete-form" = "/slider/{slider}/delete"
 *   },
 *   base_table = "slider",
 *   data_table = "slider_field_data",
 *   admin_permission = "administer sliders",
 *   field_ui_base_route = "slider.settings",
 *   translatable = TRUE,
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "title",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode"
 *   }
 * )
 */
class Slider extends ContentEntityBase implements SliderInterface {

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
    ->setLabel(t('Slider ID'))
    ->setDescription(t('The ID of the slider.'))
    ->setReadOnly(TRUE)
    ->setSetting('unsigned', TRUE);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
    ->setLabel(t('UUID'))
    ->setDescription(t('The slider UUID.'))
    ->setReadOnly(TRUE);

    $fields['title'] = BaseFieldDefinition::create('string')
    ->setLabel(t('Title'))
    ->setDescription(t('The slider title.'))
    ->setRequired(TRUE)
    ->setTranslatable(TRUE)
    ->setSetting('max_length', 255)
    ->setDisplayOptions('form', array(
      'type' => 'string_textfield',
      'weight' => 1,
    ))
    ->setDisplayConfigurable('form', TRUE);


    $fields['description'] = BaseFieldDefinition::create('text_long')
    ->setLabel(t('Content (formatted, long)'))
    ->setDescription(t('Content'))
    ->setDisplayOptions('view', [
      'label' => 'visible',
      'type' => 'text_default',
      'weight' => 2,
    ])
    ->setDisplayOptions('form', [
      'type' => 'text_textarea',
      'weight' => 2,
      'rows' => 6,
    ])
    ->setDisplayConfigurable('view', TRUE)
    ->setDisplayConfigurable('form', TRUE);

    $fields['banner_image'] = BaseFieldDefinition::create('entity_reference')
    ->setLabel(t('Banner Image'))
    ->setDescription(t('The Banner Image for this slider.'))
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

    $fields['slider_for'] = BaseFieldDefinition::create('list_string')
    ->setLabel(t('Slider For'))
    ->setDescription(t('Slider for the sections of the site'))
    ->setDefaultValue(200)
    ->setSettings([
      'allowed_values' => [
        'homepage_main' => 'Home Page Main Slider',
      ],
    ])
    ->setDefaultValue('homepage_main')
    ->setDisplayOptions('view', [
      'label' => 'visible',
      'type' => 'list_default',
      'weight' => 6,
    ])
    ->setDisplayOptions('form', [
      'type' => 'options_select',
      'weight' => 6,
    ])
    ->setDisplayConfigurable('view', TRUE)
    ->setDisplayConfigurable('form', TRUE);

    $fields['weight'] = BaseFieldDefinition::create('integer')
    ->setLabel(t('Weight'))
    ->setDescription(t('Weight of the slider'))
    ->setTranslatable(TRUE)
    ->setRequired(false)
    ->setDefaultValue(0)
    ->setDisplayOptions('view', array(
      'label' => 'above',
      'weight' => 6,
    ))
    ->setDisplayOptions('form', array(
      'weight' => 6,
    ))
    ->setDisplayConfigurable('form', true)
    ->setDisplayConfigurable('view', true);


    $fields['link_title'] = BaseFieldDefinition::create('string')
    ->setLabel(t('Link Title'))
    ->setDescription(t('The slider link title.'))
    ->setRequired(false)
    ->setTranslatable(TRUE)
    ->setSetting('max_length', 255)
    ->setDisplayOptions('form', array(
      'type' => 'string_textfield',
      'weight' => 7,
    ))
    ->setDisplayConfigurable('form', TRUE);

    $fields['link_url'] = BaseFieldDefinition::create('string')
    ->setLabel(t('Link Url'))
    ->setDescription(t('The slider link url.'))
    ->setRequired(false)
    ->setTranslatable(TRUE)
    ->setSetting('max_length', 255)
    ->setDisplayOptions('form', array(
      'type' => 'string_textfield',
      'weight' => 8,
    ))
    ->setDisplayConfigurable('form', TRUE);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
    ->setLabel(t('Author'))
    ->setDescription(t('The slider author.'))
    ->setSetting('target_type', 'user')
    ->setTranslatable(TRUE)
    ->setDefaultValueCallback('Drupal\slider\Entity\Slider::getCurrentUserId')
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
    ->setDescription(t('A flag indicating whether the slider is active.'))
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
    ->setDescription(t('The slider language code.'));

    $fields['created'] = BaseFieldDefinition::create('created')
    ->setLabel(t('Created'))
    ->setDescription(t('When the slider was created, as a Unix timestamp.'));

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
