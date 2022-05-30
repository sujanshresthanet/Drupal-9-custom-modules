<?php

namespace Drupal\featured_image\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form for featured_image settings.
 */
class FeaturedImageSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'featured_image_settings';
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

