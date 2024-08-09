<?php

namespace App\Controller;

use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart_index')]
    public function index(SessionInterface $session): Response
    {
        $cartItems = $session->get('cart', []);

        return $this->render('cart/index.html.twig', [
            'cartItems' => $cartItems,
        ]);
    }

    #[Route('/cart/checkout', name: 'cart_checkout')]
public function checkout(SessionInterface $session): Response
{
    $cartItems = $session->get('cart', []);
    $lineItems = [];

    foreach ($cartItems as $sizes) {
        foreach ($sizes as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $item['product']->getName(),
                        'images' => [$this->generateUrl('product_show', ['id' => $item['product']->getId()], UrlGeneratorInterface::ABSOLUTE_URL)],
                        ],
                    'unit_amount' => $item['product']->getPrice() * 100, // Le prix en centimes
                    ],
                'quantity' => $item['quantity'],
            ];
        }
    }

    Stripe::setApiKey($this->getParameter('stripe_secret_key'));

    $checkoutSession = StripeSession::create([
        'payment_method_types' => ['card'],
        'line_items' => [$lineItems],
        'mode' => 'payment',
        'success_url' => $this->generateUrl('checkout_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
        'cancel_url' => $this->generateUrl('checkout_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
    ]);

    return $this->redirect($checkoutSession->url);
}

    #[Route('/checkout/success', name: 'checkout_success')]
    public function success(SessionInterface $session): Response
    {
        $session->remove('cart');

        return $this->render('cart/success.html.twig');
    }

    #[Route('/checkout/cancel', name: 'checkout_cancel')]
    public function cancel(): Response
    {
        return $this->render('cart/cancel.html.twig');
    }

    #[Route('/cart/remove/{id}/{size}', name: 'cart_remove')]
    public function removeFromCart($id, $size, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);

        if (isset($cart[$id][$size])) {
            unset($cart[$id][$size]);

            if (empty($cart[$id])) {
                unset($cart[$id]);
            }

            $session->set('cart', $cart);
        }

        return $this->redirectToRoute('cart_index');
    }

    
}
