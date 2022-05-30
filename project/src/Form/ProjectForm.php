<?php

namespace Drupal\project\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the project edit forms.
 */
class ProjectForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    $project = $this->entity;

    if ($project->isNew()) {
      $title = $this->t('Add new project');
    }
    else {
      $title = $this->t('Edit @label', ['@label' => $project->label()]);
    }
    $form['#title'] = $title;

    $form['#attached'] = ['library' => ['project/admin']];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $project = $this->buildEntity($form, $form_state);
    // Check for duplicate titles.
    $project_storage = $this->entityTypeManager->getStorage('project');
    $result = $project_storage->getProjectDuplicates($project);
    foreach ($result as $item) {
      if (strcasecmp($item->label(), $project->label()) == 0) {
        $form_state->setErrorByName('title', $this->t('A feed named %feed already exists. Enter a unique title.', array('%feed' => $project->label())));
      }
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $project = $this->entity;
    $insert = (bool) $project->id();
    $project->save();
    if ($insert) {
      $this->messenger()->addMessage($this->t('The project %project has been updated.', array('%project' => $project->label())));
    }
    else {
      \Drupal::logger('project')->notice('Project %project added.', array('%project' => $project->label(), 'link' => $project->toLink()->toString()));
      $this->messenger()->addMessage($this->t('The project %project has been added.', array('%project' => $project->label())));
    }

    $form_state->setRedirect('project.project_list');
  }

}
