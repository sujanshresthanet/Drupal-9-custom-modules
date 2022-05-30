<?php

namespace Drupal\news_article\Form;

use Drupal\Core\Entity\ContentEntityConfirmFormBase;
use Drupal\Core\Url;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a form for deleting a news_article.
 */
class NewsArticleDeleteForm extends ContentEntityConfirmFormBase {

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return t('All associated contents will be deleted too. This action cannot be undone.');
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete this news_article %news_article', array('%news_article' => $this->entity->label()));
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('news_article.news_article_list');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->entity->delete();
    \Drupal::logger('news_article')->notice('NewsArticle %news_article deleted.', array('%news_article' => $this->entity->label()));
    $this->messenger()->addMessage($this->t('The news_article %news_article has been deleted.', array('%news_article' => $this->entity->label())));
    $form_state->setRedirect('news_article.news_article_list');
  }

}
