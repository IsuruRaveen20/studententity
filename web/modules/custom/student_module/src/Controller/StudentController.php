<?php

namespace Drupal\student_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\user\Entity\User;
use Drupal\Core\Access\AccessResult;

/**
 * Controller for the Student entity.
 */
class StudentController extends ControllerBase
{
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
  public function __construct(EntityTypeManagerInterface $entityTypeManager, FormBuilderInterface $formBuilder)
  {
    $this->entityTypeManager = $entityTypeManager;
    $this->formBuilder = $formBuilder;
  }


  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('form_builder')
    );
  }

  /**
   * Controller function for displaying the student entity form dynamically.
   *
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match.
   *
   * @return array
   *   Render array for the student entity form.
   */
  public function content(RouteMatchInterface $route_match)
  {
    // Get the current user.
    // $current_user = User::load($this->currentUser()->id());
    $current_user = $this->currentUser()->id();
    var_dump($current_user);
    // Check if the user has created a Student entity.
    $storage = $this->entityTypeManager->getStorage('student');
    $student_entities = $storage->getQuery()
      ->condition('uid', $current_user)
      ->range(0, 1)
      ->execute();

    if (!empty($student_entities)) {
      // Load the Student entity for the current user.
      $student_entity_id = reset($student_entities);
      $student_entity = $storage->load($student_entity_id);

      // Build and return the edit form.
      $form = $this->entityFormBuilder()->getForm($student_entity);
    } else {
      // Display the add form.
      $entity = $storage
        ->create([
          'uid' => $current_user,
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

  // Use the content method for the add form as well
  public function addForm(RouteMatchInterface $route_match)
  {
    return $this->content($route_match);
  }

  /**
   * Custom access callback for the 'student_module.add_form' route.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   The access result.
   */
  public static function checkContentAccess()
  {
    // Check if the user has the 'student' role.
    $hasStudentRole = in_array('student', \Drupal::currentUser()->getRoles());
    // Check if the user has the necessary permission.
    $hasPermission = \Drupal::currentUser()->hasPermission('student access');
    var_dump($hasStudentRole);
    var_dump($hasPermission);

    // Combine the role and permission checks.
    return AccessResult::allowedIf($hasStudentRole && $hasPermission)->cachePerPermissions();
  }




}

