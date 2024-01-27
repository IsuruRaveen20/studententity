<?php
namespace Drupal\student_module\Access;

use Drupal\Core\Access\AccessCheckInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\Routing\Route;
use Psr\Log\LoggerInterface;

/**
 * Checks access for displaying the add student form.
 */
class StudentAccessCheck implements AccessCheckInterface
{
  /**
   * The logger service.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * Constructs a new StudentAccessCheck object.
   *
   * @param \Psr\Log\LoggerInterface $logger
   *   The logger service.
   */
  public function __construct(LoggerInterface $logger) {
    $this->logger = $logger;
  }

  /**
   * {@inheritdoc}
   */
  public function applies(Route $route)
  {
    return $route->getRequirement('_custom_access_check');
  }

  /**
   * {@inheritdoc}
   */
  public function accessCheck(AccountInterface $account, Route $route)
  {
    $access_result = $this->checkPermissions($account);
    var_dump($access_result);
    // Log role and permission information.
    if ($access_result->isAllowed()) {
      $this->logger->notice('User with uid @uid has access to the route @route.', [
        '@uid' => $account->id(),
        '@route' => $route->getPath(),
      ]);
    }

    return $access_result;
  }

  /**
   * Check if the user has the necessary permissions.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The account to check.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   The access result.
   */
  protected function checkPermissions(AccountInterface $account)
  {
    // Check if the user has the 'student' role.
    if ($account->hasRole('student')) {
      // Additional permission check logic.
      // Replace 'additional_permission' with the actual permission you want to check.
      if ($account->hasPermission('administer student')) {
        return AccessResult::allowed();
      }
    }

    return AccessResult::forbidden();
  }
}

// use Drupal\Core\Access\AccessCheckInterface;
// use Drupal\Core\Access\AccessResult;
// use Drupal\Core\Session\AccountInterface;
// use Symfony\Component\Routing\Route;
// use Psr\Log\LoggerInterface;

// /**
//  * Checks access for displaying the add student form.
//  */
// class StudentAccessCheck implements AccessCheckInterface
// {
//   /**
//    * The logger service.
//    *
//    * @var \Psr\Log\LoggerInterface
//    */
//   protected $logger;

//   /**
//    * Constructs a new StudentAccessCheck object.
//    *
//    * @param \Psr\Log\LoggerInterface $logger
//    *   The logger service.
//    */
//   public function __construct(LoggerInterface $logger) {
//     $this->logger = $logger;
//   }

//   /**
//    * {@inheritdoc}
//    */
//   public function applies(Route $route)
//   {
//     return $route->getRequirement('_custom_access_check');
//   }

//   /**
//    * {@inheritdoc}
//    */
//   public function access(AccountInterface $account, Route $route)
//   {
//     $access_result = $this->checkPermissions($account);

//     // Log role and permission information.
//     if ($access_result->isAllowed()) {
//       $this->logger->notice('User with uid @uid has access to the route @route.', [
//         '@uid' => $account->id(),
//         '@route' => $route->getPath(),
//       ]);
//     }

//     return $access_result;
//   }

//   /**
//    * Check if the user has the necessary permissions.
//    *
//    * @param \Drupal\Core\Session\AccountInterface $account
//    *   The account to check.
//    *
//    * @return \Drupal\Core\Access\AccessResultInterface
//    *   The access result.
//    */
//   protected function checkPermissions(AccountInterface $account)
//   {
//     // Check if the user has the 'student' role.
//     if ($account->hasRole('student')) {
//       // Additional permission check logic.
//       // Replace 'additional_permission' with the actual permission you want to check.
//       if ($account->hasPermission('administer student')) {
//         return AccessResult::allowed();
//       }
//     }

//     return AccessResult::forbidden();
//   }
// }

// /**
//  * Check if the user has the necessary permissions.
//  *
//  * @param \Drupal\Core\Session\AccountInterface $account
//  *   The account to check.
//  *
//  * @return \Drupal\Core\Access\AccessResultInterface
//  *   The access result.
//  */
// protected function checkPermissions(AccountInterface $account) {
//   // Check if the user has the 'student' role.
//   if ($account->hasRole('student')) {
//     return AccessResult::allowed();
//   }

//   return AccessResult::forbidden();
// }

// namespace Drupal\student_module\Access;

// use Drupal\Core\Access\AccessResult;
// use Drupal\Core\Access\AccessCheckInterface;
// use Drupal\Core\Routing\RouteProviderInterface;
// use Symfony\Component\DependencyInjection\ContainerInterface;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\Routing\Route;
// use Drupal\Core\Routing\RouteMatchInterface;
// use Drupal\Core\Session\AccountInterface;

// /**
//  * Access check for the Student add/edit route.
//  */
// class StudentAccessCheck implements AccessCheckInterface {

//   /**
//    * The route provider.
//    *
//    * @var \Drupal\Core\Routing\RouteProviderInterface
//    */
//   protected $routeProvider;

//   /**
//    * Constructs a new StudentAccessCheck object.
//    *
//    * @param \Drupal\Core\Routing\RouteProviderInterface $route_provider
//    *   The route provider.
//    */
//   public function __construct(RouteProviderInterface $route_provider) {
//     $this->routeProvider = $route_provider;
//   }

//   /**
//    * {@inheritdoc}
//    */
//   public function applies(Route $route) {
//     // Check if the route is the one you want to control access to.
//     // $route_name = $route->getName();
//     return $route->getRequirement('_custom_access') === 'student_access_check';
//     // return $route_name === 'student_module.add_form';
//   }

//   /**
//    * {@inheritdoc}
//    */

//   public function access(AccountInterface $account, Request $request = NULL, RouteMatchInterface $route_match = NULL) {
//     // Check for both role and permissions:
//     return AccessResult::allowedIf($account->hasRole('student') &&
//       AccessResult::allowedIfHasPermissions($account, ['administer student'])->isAllowed());
//   }

//   /**
//    * {@inheritdoc}
//    */
//   public static function create(ContainerInterface $container) {
//     return new static(
//       $container->get('router.route_provider')
//     );
//   }
// }
