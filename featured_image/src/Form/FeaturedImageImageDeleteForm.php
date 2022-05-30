<?php

namespace Drupal\featured_image\Form;

use Drupal\Core\Entity\ContentEntityConfirmFormBase;
use Drupal\Core\Url;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a form for deleting a featured_image.
 */
class FeaturedImageDeleteForm extends ContentEntityConfirmFormBase {

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
    return $this->t('Are you sure you want to delete this featured_image %featured_image', array('%featured_image' => $this->entity->label()));
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('featured_image.featured_image_list');
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
    \Drupal::logger('featured_image')->notice('Featured Image %featured_image deleted.', array('%featured_image' => $this->entity->label()));
    $this->messenger()->addMessage($this->t('The featured_image %featured_image has been deleted.', array('%featured_image' => $this->entity->label())));
    $form_state->setRedirect('featured_image.featured_image_list');
  }

}
