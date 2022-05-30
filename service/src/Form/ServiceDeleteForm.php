<?php

namespace Drupal\service\Form;

use Drupal\Core\Entity\ContentEntityConfirmFormBase;
use Drupal\Core\Url;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a form for deleting a service.
 */
class ServiceDeleteForm extends ContentEntityConfirmFormBase {

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
    return $this->t('Are you sure you want to delete this service %service', array('%service' => $this->entity->label()));
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('service.service_list');
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
    \Drupal::logger('service')->notice('Service %service deleted.', array('%service' => $this->entity->label()));
    $this->messenger()->addMessage($this->t('The service %service has been deleted.', array('%service' => $this->entity->label())));
    $form_state->setRedirect('service.service_list');
  }

}
