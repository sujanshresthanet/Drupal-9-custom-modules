<?php

namespace Drupal\blog\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\blog\BlogInterface;
use Drupal\user\UserInterface;

/**
 * Defines the blog entity class.
 *
 * @ContentEntityType(
 *   id = "blog",
 *   label = @Translation("Blog"),
 *   handlers = {
 *     "access" = "\Drupal\blog\BlogAccessControlHandler",
 *     "storage" = "Drupal\blog\BlogStorage",
 *     "translation" = "Drupal\content_translation\ContentTranslationHandler",
 *     "list_builder" = "Drupal\blog\BlogListBuilder",
 *     "view_builder" = "Drupal\blog\BlogViewBuilder",
 *     "views_data" = "Drupal\blog\BlogViewData",
 *     "form" = {
 *       "default" = "Drupal\blog\Form\BlogForm",
 *       "edit" = "Drupal\blog\Form\BlogForm",
 *       "delete" = "Drupal\blog\Form\BlogDeleteForm",
 *       "delete_items" = "Drupal\blog\Form\BlogItemsDeleteForm",
 *     }
 *   },
 *   links = {
 *     "canonical" = "/blog/{blog}",
 *     "edit-form" = "/blog/{blog}/edit",
 *     "delete-form" = "/blog/{blog}/delete"
 *   },
 *   base_table = "blog",
 *   data_table = "blog_field_data",
 *   admin_permission = "administer blogs",
 *   field_ui_base_route = "blog.settings",
 *   translatable = TRUE,
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "title",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode"
 *   }
 * )
 */
class Blog extends ContentEntityBase implements BlogInterface {

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
  public function getOwnerName() {
    $author = $this->get('uid')->entity->name->value;
    $author = ($author != '')?$author:'Anonymous';
    return $author;
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
  public function getRelatedBlogs() {
    return $this->get('related_blogs')->value;
  }
  /**
   * {@inheritdoc}
   */
  public function getTags() {
    return $this->get('tags')->value;
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
    ->setLabel(t('Blog ID'))
    ->setDescription(t('The ID of the blog.'))
    ->setReadOnly(TRUE)
    ->setSetting('unsigned', TRUE);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
    ->setLabel(t('UUID'))
    ->setDescription(t('The blog UUID.'))
    ->setReadOnly(TRUE);

    $fields['title'] = BaseFieldDefinition::create('string')
    ->setLabel(t('Title'))
    ->setDescription(t('The blog title.'))
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
    ->setDescription(t('Short description of the blog.'))
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
    ->setLabel(t('Content'))
    ->setDescription(t('Main content of the blog.'))
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


    $fields['featured_image'] = BaseFieldDefinition::create('entity_reference')
    ->setLabel(t('Featured Image'))
    ->setDescription(t('The featured image for this blog which will be displayed at blog listing.'))
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
    ->setDescription(t('The banner image for this blog which will be displayed at the blog details page.'))
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


    $fields['related_blogs'] = BaseFieldDefinition::create('string')
    ->setLabel(t('Related Blogs'))
    ->setDescription(t('The related blog.'))
    ->setRequired(false)
    ->setTranslatable(TRUE)
    ->setSetting('max_length', 255)
    ->setDisplayOptions('form', array(
      'type' => 'string_textfield',
      'weight' => 6,
    ))
    ->setDisplayConfigurable('form', TRUE);

    $fields['blog_category'] = BaseFieldDefinition::create('entity_reference')
    ->setLabel(t('Category'))
    ->setDescription(t('The blog category.'))
    ->setSetting('target_type', 'taxonomy_term')
    ->setSetting('handler_settings',
      [
        'target_bundles' =>
        [
          'taxonomy_term' => 'blog_category'
        ]
      ]
    )
    ->setDisplayOptions('view', array(
      'label' => 'above',
      'type' => 'taxonomy_term',
    ))
    ->setDefaultValue(NULL)
    ->setDisplayOptions('form', [
      'type' => 'options_select',
      'settings' => [
        'match_operator' => 'CONTAINS',
        'size' => 60,
        'placeholder' => '',
      ],
      'weight' => 7,
    ])
    ->setDisplayConfigurable('form', TRUE)
    ->setDisplayConfigurable('view', TRUE);


    $fields['tags'] = BaseFieldDefinition::create('string')
    ->setLabel(t('Tags'))
    ->setDescription(t('Tags for this blog'))
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
    ->setDescription(t('The blog author.'))
    ->setSetting('target_type', 'user')
    ->setTranslatable(TRUE)
    ->setDefaultValueCallback('Drupal\blog\Entity\Blog::getCurrentUserId')
    ->setDisplayOptions('form', array(
      'type' => 'entity_reference_autocomplete',
      'weight' => 9,
      'settings' => array(
        'match_operator' => 'CONTAINS',
        'size' => '60',
        'autocomplete_type' => 'tags',
        'placeholder' => '',
      ),
    ))
    ->setDisplayConfigurable('form', TRUE);

    $fields['updated_date'] = BaseFieldDefinition::create('datetime')
    ->setLabel(t('Updated Date'))
    ->setDescription(t('Blog updated date.'))
    ->setRevisionable(TRUE)
    ->setSettings([
      'datetime_type' => 'date'
    ])
    ->setDefaultValue('')
    ->setDisplayOptions('view', [
      'label' => 'above',
      'type' => 'datetime_default',
      'settings' => [
        'format_type' => 'medium',
      ],
      'weight' => 10,
    ])
    ->setDisplayOptions('form', [
      'type' => 'datetime_default',
      'weight' => 10,
    ])
    ->setDisplayConfigurable('form', TRUE)
    ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
    ->setLabel(t('Active'))
    ->setDescription(t('A flag indicating whether the blog is active.'))
    ->setDefaultValue(1)
    ->setDisplayOptions('form', array(
      'type' => 'boolean_checkbox',
      'settings' => array(
        'display_label' => TRUE,
      ),
      'weight' => 11,
    ))
    ->setDisplayConfigurable('form', TRUE);

    $fields['langcode'] = BaseFieldDefinition::create('language')
    ->setLabel(t('Language code'))
    ->setDescription(t('The blog language code.'));

    $fields['created'] = BaseFieldDefinition::create('created')
    ->setLabel(t('Created'))
    ->setDescription(t('When the blog was created, as a Unix timestamp.'));

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
