<?php

namespace Drupal\blog\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the blog edit forms.
 */
class BlogForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    $blog = $this->entity;

    if ($blog->isNew()) {
      $title = $this->t('Add new blog');
    }
    else {
      $title = $this->t('Edit @label', ['@label' => $blog->label()]);
    }
    $form['#title'] = $title;

    $form['#attached'] = ['library' => ['blog/admin']];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $blog = $this->buildEntity($form, $form_state);
    // Check for duplicate titles.
    $blog_storage = $this->entityTypeManager->getStorage('blog');
    $result = $blog_storage->getBlogDuplicates($blog);
    foreach ($result as $item) {
      if (strcasecmp($item->label(), $blog->label()) == 0) {
        $form_state->setErrorByName('title', $this->t('A feed named %feed already exists. Enter a unique title.', array('%feed' => $blog->label())));
      }
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $blog = $this->entity;
    $insert = (bool) $blog->id();
    $blog->save();
    if ($insert) {
      $this->messenger()->addMessage($this->t('The blog %blog has been updated.', array('%blog' => $blog->label())));
    }
    else {
      \Drupal::logger('blog')->notice('Blog %blog added.', array('%blog' => $blog->label(), 'link' => $blog->toLink()->toString()));
      $this->messenger()->addMessage($this->t('The blog %blog has been added.', array('%blog' => $blog->label())));
    }

    $form_state->setRedirect('blog.blog_list');
  }

}
