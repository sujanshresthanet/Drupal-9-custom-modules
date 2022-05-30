<?php

namespace Drupal\news_article\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the news_article edit forms.
 */
class NewsArticleForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    $news_article = $this->entity;

    if ($news_article->isNew()) {
      $title = $this->t('Add new news_article');
    }
    else {
      $title = $this->t('Edit @label', ['@label' => $news_article->label()]);
    }
    $form['#title'] = $title;

    $form['#attached'] = ['library' => ['news_article/admin']];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $news_article = $this->buildEntity($form, $form_state);
    // Check for duplicate titles.
    $news_article_storage = $this->entityTypeManager->getStorage('news_article');
    $result = $news_article_storage->getNewsArticleDuplicates($news_article);
    foreach ($result as $item) {
      if (strcasecmp($item->label(), $news_article->label()) == 0) {
        $form_state->setErrorByName('title', $this->t('A feed named %feed already exists. Enter a unique title.', array('%feed' => $news_article->label())));
      }
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $news_article = $this->entity;
    $insert = (bool) $news_article->id();
    $news_article->save();
    if ($insert) {
      $this->messenger()->addMessage($this->t('The news_article %news_article has been updated.', array('%news_article' => $news_article->label())));
    }
    else {
      \Drupal::logger('news_article')->notice('NewsArticle %news_article added.', array('%news_article' => $news_article->label(), 'link' => $news_article->toLink()->toString()));
      $this->messenger()->addMessage($this->t('The news_article %news_article has been added.', array('%news_article' => $news_article->label())));
    }

    $form_state->setRedirect('news_article.news_article_list');
  }

}
