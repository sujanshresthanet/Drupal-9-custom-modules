<?php

namespace Drupal\service\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the service edit forms.
 */
class ServiceForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    $service = $this->entity;

    if ($service->isNew()) {
      $title = $this->t('Add new service');
    }
    else {
      $title = $this->t('Edit @label', ['@label' => $service->label()]);
    }
    $form['#title'] = $title;

    $form['#attached'] = ['library' => ['service/admin']];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $service = $this->buildEntity($form, $form_state);
    // Check for duplicate titles.
    $service_storage = $this->entityTypeManager->getStorage('service');
    $result = $service_storage->getServiceDuplicates($service);
    foreach ($result as $item) {
      if (strcasecmp($item->label(), $service->label()) == 0) {
        $form_state->setErrorByName('title', $this->t('A feed named %feed already exists. Enter a unique title.', array('%feed' => $service->label())));
      }
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $service = $this->entity;
    $insert = (bool) $service->id();
    $service->save();
    if ($insert) {
      $this->messenger()->addMessage($this->t('The service %service has been updated.', array('%service' => $service->label())));
    }
    else {
      \Drupal::logger('service')->notice('Service %service added.', array('%service' => $service->label(), 'link' => $service->toLink()->toString()));
      $this->messenger()->addMessage($this->t('The service %service has been added.', array('%service' => $service->label())));
    }

    $form_state->setRedirect('service.service_list');
  }

}
