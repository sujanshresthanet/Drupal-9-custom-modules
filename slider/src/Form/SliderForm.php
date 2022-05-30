<?php

namespace Drupal\slider\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the slider edit forms.
 */
class SliderForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    $slider = $this->entity;

    if ($slider->isNew()) {
      $title = $this->t('Add new slider');
    }
    else {
      $title = $this->t('Edit @label', ['@label' => $slider->label()]);
    }
    $form['#title'] = $title;

    $form['#attached'] = ['library' => ['slider/admin']];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $slider = $this->buildEntity($form, $form_state);
    // Check for duplicate titles.
    $slider_storage = $this->entityTypeManager->getStorage('slider');
    $result = $slider_storage->getSliderDuplicates($slider);
    foreach ($result as $item) {
      if (strcasecmp($item->label(), $slider->label()) == 0) {
        $form_state->setErrorByName('title', $this->t('A feed named %feed already exists. Enter a unique title.', array('%feed' => $slider->label())));
      }
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $slider = $this->entity;
    $insert = (bool) $slider->id();
    $slider->save();
    if ($insert) {
      $this->messenger()->addMessage($this->t('The slider %slider has been updated.', array('%slider' => $slider->label())));
    }
    else {
      \Drupal::logger('slider')->notice('Slider %slider added.', array('%slider' => $slider->label(), 'link' => $slider->toLink()->toString()));
      $this->messenger()->addMessage($this->t('The slider %slider has been added.', array('%slider' => $slider->label())));
    }

    $form_state->setRedirect('slider.slider_list');
  }

}
