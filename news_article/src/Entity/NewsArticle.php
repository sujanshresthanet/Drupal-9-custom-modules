<?php

namespace Drupal\news_article\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\news_article\NewsArticleInterface;
use Drupal\user\UserInterface;

/**
 * Defines the news_article entity class.
 *
 * @ContentEntityType(
 *   id = "news_article",
 *   label = @Translation("News Article"),
 *   handlers = {
 *     "access" = "\Drupal\news_article\NewsArticleAccessControlHandler",
 *     "storage" = "Drupal\news_article\NewsArticleStorage",
 *     "translation" = "Drupal\content_translation\ContentTranslationHandler",
 *     "list_builder" = "Drupal\news_article\NewsArticleListBuilder",
 *     "view_builder" = "Drupal\news_article\NewsArticleViewBuilder",
 *     "views_data" = "Drupal\news_article\NewsArticleViewData",
 *     "form" = {
 *       "default" = "Drupal\news_article\Form\NewsArticleForm",
 *       "edit" = "Drupal\news_article\Form\NewsArticleForm",
 *       "delete" = "Drupal\news_article\Form\NewsArticleDeleteForm",
 *       "delete_items" = "Drupal\news_article\Form\NewsArticleItemsDeleteForm",
 *     }
 *   },
 *   links = {
 *     "canonical" = "/news_article/{news_article}",
 *     "edit-form" = "/news_article/{news_article}/edit",
 *     "delete-form" = "/news_article/{news_article}/delete"
 *   },
 *   base_table = "news_article",
 *   data_table = "news_article_field_data",
 *   admin_permission = "administer news_articles",
 *   field_ui_base_route = "news_article.settings",
 *   translatable = TRUE,
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "title",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode"
 *   }
 * )
 */
class NewsArticle extends ContentEntityBase implements NewsArticleInterface {

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
  public function getTags() {
    return $this->get('tags')->value;
  }

    /**
   * {@inheritdoc}
   */
    public function getRelatedNewsArticle() {
      return $this->get('related_news_articles')->value;
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
    public function getOwnerName() {
      $author = $this->get('uid')->entity->name->value;
      $author = ($author != '')?$author:'Anonymous';
      return $author;
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
    ->setLabel(t('News Article ID'))
    ->setDescription(t('The ID of the newa and article.'))
    ->setReadOnly(TRUE)
    ->setSetting('unsigned', TRUE);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
    ->setLabel(t('UUID'))
    ->setDescription(t('The news article UUID.'))
    ->setReadOnly(TRUE);

    $fields['title'] = BaseFieldDefinition::create('string')
    ->setLabel(t('Title'))
    ->setDescription(t('The news and article title.'))
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
    ->setDescription(t('Short description of the news and article.'))
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
    ->setDescription(t('Main content of the news and articel'))
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
    ->setDescription(t('The featured image for this news and article.'))
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
    ->setDescription(t('The banner image for this news and article.'))
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


    $fields['related_news_articles'] = BaseFieldDefinition::create('string')
    ->setLabel(t('Related NewsArticles'))
    ->setDescription(t('The related articles'))
    ->setRequired(false)
    ->setTranslatable(TRUE)
    ->setSetting('max_length', 255)
    ->setDisplayOptions('form', array(
      'type' => 'string_textfield',
      'weight' => 6,
    ))
    ->setDisplayConfigurable('form', TRUE);

    $fields['news_category'] = BaseFieldDefinition::create('entity_reference')
    ->setLabel(t('Category'))
    ->setDescription(t('The news and article category.'))
    ->setSetting('target_type', 'taxonomy_term')
    ->setSetting('handler_settings',
      [
        'target_bundles' =>
        [
          'taxonomy_term' => 'news_category'
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
    ->setDescription(t('Tags for this news and article.'))
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
    ->setDescription(t('The news and article author.'))
    ->setSetting('target_type', 'user')
    ->setTranslatable(TRUE)
    ->setDefaultValueCallback('Drupal\news_article\Entity\NewsArticle::getCurrentUserId')
    ->setDisplayOptions('form', array(
      'type' => 'entity_reference_autocomplete',
      'weight' => 9,
      'settings' => array(
        'match_operator' => 'CONTAINS',
        'size' => 60,
        'autocomplete_type' => 'tags',
        'placeholder' => '',
      ),
    ))
    ->setDisplayConfigurable('form', TRUE);

    $fields['updated_date'] = BaseFieldDefinition::create('datetime')
    ->setLabel(t('Updated Date'))
    ->setDescription(t('News and article updated date.'))
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
    ->setDescription(t('A flag indicating whether the news_article is active.'))
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
    ->setDescription(t('The news_article language code.'));

    $fields['created'] = BaseFieldDefinition::create('created')
    ->setLabel(t('Created'))
    ->setDescription(t('When the news_article was created, as a Unix timestamp.'));

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
