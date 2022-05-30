<?php

namespace Drupal\project\Form;

use Drupal\Core\Entity\ContentEntityConfirmFormBase;
use Drupal\Core\Url;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a form for deleting a project.
 */
class ProjectDeleteForm extends ContentEntityConfirmFormBase {

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
    return $this->t('Are you sure you want to delete this project %project', array('%project' => $this->entity->label()));
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('project.project_list');
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
    \Drupal::logger('project')->notice('Project %project deleted.', array('%project' => $this->entity->label()));
    $this->messenger()->addMessage($this->t('The project %project has been deleted.', array('%project' => $this->entity->label())));
    $form_state->setRedirect('project.project_list');
  }

}
