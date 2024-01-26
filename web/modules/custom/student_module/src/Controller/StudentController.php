<?php

namespace Drupal\student_module\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller for the Student entity.
 */
class StudentController extends ControllerBase {

    /**
     * The entity type manager.
     *
     * @var \Drupal\Core\Entity\EntityTypeManagerInterface
     */
    protected $entityTypeManager;

    /**
     * Constructs a StudentController object.
     *
     * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
     *   The entity type manager.
     */
    public function __construct(EntityTypeManagerInterface $entityTypeManager) {
        $this->entityTypeManager = $entityTypeManager;
    }

     /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container) {
        return new static(
        $container->get('entity_type.manager')
        );
    }

    /**
   * Controller function for the student entity.
   *
   * @return array
   *   Render array for the student entity page.
   */
    public function content() {
        // Check if the current user has created a Student entity.
        $uid = \Drupal::currentUser()->id();
        $storage = $this->entityTypeManager->getStorage('student');
        $student_entities = $storage->getQuery()
          ->condition('uid', $uid)
          ->range(0, 1)->execute();

        if(!empty($student_entities)) {
            //Load the Student entity for the current user
            $student_entity_id = reset($student_entities);
            $student_entity = $storage->load($student_entity_id);

            $form = $this->entityFormBuilder()->getForm($student_entity);

        }else {
            //Display the add form
            $storage = $this->entityTypeManager()
              ->getStorage('student');

            $entity = $storage
              ->create([
                'uid' => $uid,
                // Add other fields as needed.
              ]);

            // Save the entity.
            $entity->save();
            $form = $this->entityFormBuilder()->getForm($entity);
        }


        $build = [
            '#markup' => \Drupal::service('renderer')->render($form),
        ];

        return $build;
    }

    /**
    * Controller function for the add form of the student entity.
    *
    * @return array
    *   Render array for the student add form.
    */
    public function addForm() {
      // Load the Student entity form for the add operation.
      $storage = $this->entityTypeManager->getStorage('student');
      $entity = $storage->create();

      $form = $this->entityFormBuilder()->getForm($entity);

      $build = [
          '#markup' => \Drupal::service('renderer')->render($form),
      ];

      return $build;
    }

}
    //$hasStudentEntity = !empty($storage->loadByProperties(['uid' => $uid]));

    // if ($hasStudentEntity) {
    //     // Load the Student entity for the current user.
    //     $student_entity = $storage->loadByProperties(['uid' => $uid]);
    //     $form = \Drupal::formBuilder()->getForm('Drupal\student\Form\StudentForm', reset($student_entity));
    // } else {
    //     // Display the add form.
    //     $form = \Drupal::formBuilder()->getForm('Drupal\student\Form\StudentForm');
    // }
