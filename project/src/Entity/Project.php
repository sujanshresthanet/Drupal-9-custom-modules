<?php

namespace Drupal\project\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\project\ProjectInterface;
use Drupal\user\UserInterface;

/**
 * Defines the project entity class.
 *
 * @ContentEntityType(
 *   id = "project",
 *   label = @Translation("Project"),
 *   handlers = {
 *     "access" = "\Drupal\project\ProjectAccessControlHandler",
 *     "storage" = "Drupal\project\ProjectStorage",
 *     "translation" = "Drupal\content_translation\ContentTranslationHandler",
 *     "list_builder" = "Drupal\project\ProjectListBuilder",
 *     "view_builder" = "Drupal\project\ProjectViewBuilder",
 *     "views_data" = "Drupal\project\ProjectViewData",
 *     "form" = {
 *       "default" = "Drupal\project\Form\ProjectForm",
 *       "edit" = "Drupal\project\Form\ProjectForm",
 *       "delete" = "Drupal\project\Form\ProjectDeleteForm",
 *       "delete_items" = "Drupal\project\Form\ProjectItemsDeleteForm",
 *     }
 *   },
 *   links = {
 *     "canonical" = "/project/{project}",
 *     "edit-form" = "/project/{project}/edit",
 *     "delete-form" = "/project/{project}/delete"
 *   },
 *   base_table = "project",
 *   data_table = "project_field_data",
 *   admin_permission = "administer projects",
 *   field_ui_base_route = "project.settings",
 *   translatable = TRUE,
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "title",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode"
 *   }
 * )
 */
class Project extends ContentEntityBase implements ProjectInterface {

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
  public function getTags() {
    return $this->get('tags')->value;
  }


  /**
   * {@inheritdoc}
   */
  public function getRelatedProjects() {
    return $this->get('related_projects')->value;
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
    ->setLabel(t('Project ID'))
    ->setDescription(t('The ID of the project.'))
    ->setReadOnly(TRUE)
    ->setSetting('unsigned', TRUE);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
    ->setLabel(t('UUID'))
    ->setDescription(t('The project UUID.'))
    ->setReadOnly(TRUE);

    $fields['title'] = BaseFieldDefinition::create('string')
    ->setLabel(t('Title'))
    ->setDescription(t('The project title.'))
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
    ->setDescription(t('Short description of the project.'))
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
    ->setDescription(t('Main content of the project.'))
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
    ->setDescription(t('The featured image for this project which will be displayed at project listing.'))
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
    ->setDescription(t('The banner image for this project which will be displayed at the project details page.'))
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


    $fields['related_projects'] = BaseFieldDefinition::create('string')
    ->setLabel(t('Related Projects'))
    ->setDescription(t('The related project.'))
    ->setRequired(false)
    ->setTranslatable(TRUE)
    ->setSetting('max_length', 255)
    ->setDisplayOptions('form', array(
      'type' => 'string_textfield',
      'weight' => 6,
    ))
    ->setDisplayConfigurable('form', TRUE);

    $fields['project_category'] = BaseFieldDefinition::create('entity_reference')
    ->setLabel(t('Category'))
    ->setDescription(t('The project category.'))
    ->setSetting('target_type', 'taxonomy_term')
    ->setSetting('handler_settings',
      [
        'target_bundles' =>
        [
          'taxonomy_term' => 'project_category'
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
    ->setDescription(t('Tags for this project'))
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
    ->setDescription(t('The project author.'))
    ->setSetting('target_type', 'user')
    ->setTranslatable(TRUE)
    ->setDefaultValueCallback('Drupal\project\Entity\Project::getCurrentUserId')
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
    ->setDescription(t('Project updated date.'))
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
    ->setDescription(t('A flag indicating whether the project is active.'))
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
    ->setDescription(t('The project language code.'));

    $fields['created'] = BaseFieldDefinition::create('created')
    ->setLabel(t('Created'))
    ->setDescription(t('When the project was created, as a Unix timestamp.'));

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
