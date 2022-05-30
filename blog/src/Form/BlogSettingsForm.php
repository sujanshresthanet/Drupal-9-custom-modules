<?php

namespace Drupal\blog\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form for blog settings.
 */
class BlogSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'blog_settings';
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

