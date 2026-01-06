<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Actions\Cart\GetCart;

class EnsureCorrectOrderStep
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next, $requiredStep): Response
  {
    $cart = (new GetCart())->execute();
    $currentStep = $cart['order_step'] ?? 1;

    if ($currentStep < $requiredStep)
    {
      return redirect()->route($this->getRedirectRoute($currentStep))->with('error', 'Please complete the previous steps before proceeding.');
    }

    return $next($request);
  }

  private function getRedirectRoute($step)
  {
    $routes = [
      1 => 'order.overview',
      2 => 'order.invoice-address',
      3 => 'order.shipping-address',
      4 => 'order.payment',
      5 => 'order.summary',
      6 => 'order.finalize',
    ];

    return $routes[$step] ?? 'order.overview';
  }
}
