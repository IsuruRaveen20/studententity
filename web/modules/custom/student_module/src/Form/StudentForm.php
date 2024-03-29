<?php

namespace Drupal\student_module\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the student entity edit forms.
 */
class StudentForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $result = parent::save($form, $form_state);

    $entity = $this->getEntity();

    $message_arguments = ['%label' => $entity->toLink()->toString()];
    $logger_arguments = [
      '%label' => $entity->label(),
      'link' => $entity->toLink($this->t('View'))->toString(),
    ];

    switch ($result) {
      case SAVED_NEW:
        $this->messenger()->addStatus($this->t('New student %label has been created.', $message_arguments));
        $this->logger('student_module')->notice('Created new student %label', $logger_arguments);
        break;

      case SAVED_UPDATED:
        $this->messenger()->addStatus($this->t('The student %label has been updated.', $message_arguments));
        $this->logger('student_module')->notice('Updated student %label.', $logger_arguments);
        break;
    }

    $form_state->setRedirect('entity.student.canonical', ['student' => $entity->id()]);

    return $result;
  }

}
