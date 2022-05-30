<?php

namespace Drupal\project\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form for project settings.
 */
class ProjectSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'project_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // This exists to make the field UI pages visible and must not be removed.
    $form['account'] = array(
      '#markup' => '<p>' . t('There are no settings yet.') . '</p>',
    );

    return $form;
  }
}

