<?php

namespace Drupal\student_module\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Access\AccessCheckInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Access check for the Student add/edit route.
 */
class StudentAccessCheck implements AccessCheckInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new StudentAccessCheck object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $route_match) {
    // Check if the route is the one you want to control access to.
    $route_name = $route_match->getRouteName();
    return $route_name == 'student_module.add_form'; // Corrected route name.
  }


  /**
   * {@inheritdoc}
   */
  public function access(AccountInterface $account, RouteMatchInterface $route_match, $entity_type_id = NULL) {
    // Check if the user has the 'student' role.
    \Drupal::logger('student_module')->notice('Roles: @roles', ['@roles' => implode(', ', $account->getRoles())]);
    var_dump($account->getRoles());
    kint($account->getRoles());
    if ($account->hasRole('student')) {
      // Add any additional checks here if needed.
      return AccessResult::allowed();
    }

    // Deny access by default.
    return AccessResult::forbidden();
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }
}



