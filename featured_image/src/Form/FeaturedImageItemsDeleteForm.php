<?php

namespace Drupal\featured_image\Form;

use Drupal\Core\Entity\ContentEntityConfirmFormBase;
use Drupal\Core\Url;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a deletion confirmation form for items that belong to a feed.
 */
class FeaturedImageItemsDeleteForm extends ContentEntityConfirmFormBase {

  /**
   * {@inheritdoc}
   */
  public function getTitle() {
    return $this->t('Are you sure you want to delete all items from the feed %feed?', array('%feed' => $this->entity->label()));
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
    return $this->t('Delete items');
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->entity->deleteItems();
    $form_state->setRedirect('featured_image.featured_image_list');
  }

}
