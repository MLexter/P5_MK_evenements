<?php

namespace App\Data;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class CartFunctions
{

    protected $session;
    protected $articleRepository;



    public function __construct(SessionInterface $session, ArticleRepository $articleRepo)
    {
        $this->session = $session;
        $this->articleRepo = $articleRepo;
    }
    


    public function addToCart($id)
    {
        $cart = $this->session->get('cart', []);

        if (!empty($cart[$id]))
        {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        $this->session->set('cart', $cart);
    }


    public function removeFromCart($id)
    {
        $cart = $this->session->get('cart', []);

        if(!empty($cart[$id]))
        {
            unset($cart[$id]);
        }

        $this->session->set('cart', $cart);
    }


    public function removeOneFromCart($id)
    {
        $cart = $this->session->get('cart', []);

        if (!empty($cart[$id]))
        {
            $cart[$id] = $cart[$id] - 1;
        }

        $this->session->set('cart', $cart);
    }


    public function addOneToCart($id)
    {
        $cart = $this->session->get('cart', []);

        if (!empty($cart[$id]))
        {
            $cart[$id] = $cart[$id] + 1;
        }

        $this->session->set('cart', $cart);
    }


    public function getFullCart()
    {
        $cart = $this->session->get('cart', []);

        $dataCart = [];

        foreach($cart as $id => $quantity)
        {
            $dataCart[] = [
                'article' => $this->articleRepo->find($id),
                'quantity' => $quantity
            ];
        }

        return $dataCart;
    }


    public function getTotal()
    {
        $total = 0;
        $dataCart = $this->getFullCart();
        foreach($dataCart as $item)
        {
            $totalItem = $item['article']->getPrice() * $item['quantity'];
            $total += $totalItem;
        }

        return $total;
    }


    
    public function emptyCartAction()
    {
        $dataCart = $this->getFullCart();

            foreach($dataCart as $item)
            {
                $this->removeFromCart(($item['article']->getId()));
            }
        
        return $dataCart;
    }



    public function retrieveCustomerData()
    {
        $customerData = [];
    
            foreach ($_POST["rental"] as $key => $value)
            {
                $customerData = [
                    'lastName' => htmlspecialchars($_POST["rental"]["lastName"]),
                    'firstName' => htmlspecialchars($_POST["rental"]["firstName"]),
                    'phoneNumber' => htmlspecialchars($_POST["rental"]["phoneNumber"]),
                    'email' => htmlspecialchars($_POST["rental"]["email"]),
                    'customerMessage' => htmlspecialchars($_POST["rental"]["customerMessage"]),
                    'rentalDate' => htmlspecialchars($_POST["rental"]["rentalDate"]),
                    'NbOfDays' => htmlspecialchars($_POST["rental"]["NbOfDays"])
                ];
            }
            return $customerData;
    }



    public function getBadgeNumber()
    {
        $numberInCart = count($this->getFullCart());
        
        if ($numberInCart == 0)
        { 
            $numberInCart = 0;
        }

        return $numberInCart;

    }

}
