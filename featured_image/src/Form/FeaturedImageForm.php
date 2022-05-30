<?php

namespace Drupal\featured_image\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the featured_image edit forms.
 */
class FeaturedImageForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    $featured_image = $this->entity;

    if ($featured_image->isNew()) {
      $title = $this->t('Add new featured_image');
    }
    else {
      $title = $this->t('Edit @label', ['@label' => $featured_image->label()]);
    }
    $form['#title'] = $title;

    $form['#attached'] = ['library' => ['featured_image/admin']];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $featured_image = $this->buildEntity($form, $form_state);
    // Check for duplicate titles.
    $featured_image_storage = $this->entityTypeManager->getStorage('featured_image');
    $result = $featured_image_storage->getFeaturedImageDuplicates($featured_image);
    foreach ($result as $item) {
      if (strcasecmp($item->label(), $featured_image->label()) == 0) {
        $form_state->setErrorByName('title', $this->t('A feed named %feed already exists. Enter a unique title.', array('%feed' => $featured_image->label())));
      }
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $featured_image = $this->entity;
    $insert = (bool) $featured_image->id();
    $featured_image->save();
    if ($insert) {
      $this->messenger()->addMessage($this->t('The featured_image %featured_image has been updated.', array('%featured_image' => $featured_image->label())));
    }
    else {
      \Drupal::logger('featured_image')->notice('FeaturedImage %featured_image added.', array('%featured_image' => $featured_image->label(), 'link' => $featured_image->toLink()->toString()));
      $this->messenger()->addMessage($this->t('The featured_image %featured_image has been added.', array('%featured_image' => $featured_image->label())));
    }

    $form_state->setRedirect('featured_image.featured_image_list');
  }

}
