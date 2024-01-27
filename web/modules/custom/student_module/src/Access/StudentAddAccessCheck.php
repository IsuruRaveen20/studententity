<?php

namespace Drupal\student_module\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Access\AccessCheckInterface;
use Symfony\Component\Routing\Route;

class StudentAddAccessCheck implements AccessCheckInterface {

  /**
   * The route match service.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * Constructs a new StudentAccess object.
   *
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match.
   */
  public function __construct(RouteMatchInterface $route_match) {
    $this->routeMatch = $route_match;
  }

  /**
   * {@inheritdoc}
   */
  public function applies(Route $route, $context = NULL) {
    // return $route->getRouteName() === 'your.route.name'; // Adjust with your route name.
    return $route->getRequirement('_custom_access');
  }

  /**
   * {@inheritdoc}
   */
  public function access(AccountInterface $account, $route = NULL, $context = NULL) {
    // Check if the user has the 'student' role.
    if ($account->hasRole('student')) {
      return AccessResult::allowed();
    }

    return AccessResult::forbidden();
  }

  /**
   * {@inheritdoc}
   */
  public function checkAccess(AccountInterface $account, $route = NULL, $context = NULL) {
    // Your access check logic here.
  }

  /**
   * {@inheritdoc}
   */
  public function isApplicable($route) {
    // Your logic to determine applicability.
  }

  /**
   * Creates an instance of this class.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The container.
   *
   * @return static
   *   The instance of this class.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_route_match')
    );
  }

}
