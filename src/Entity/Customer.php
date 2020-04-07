<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;


class Customer {


    private $lastName;
    
    
    private $firstName;
    
    /**
     *
     * @var [type]
     */
    private $phoneNumber;
    
    
    private $email;
    
    
    private $customerMessage;

    /**
     *
     * @var [type]
     */
    private $rentalDate;
    
    
    private $nbOfDays;

    
    /**
     * Get the value of lastName
     */ 
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set the value of lastName
     *
     * @return  self
     */ 
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get the value of firstName
     */ 
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set the value of firstName
     *
     * @return  self
     */ 
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get the value of phoneNumber
     */ 
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set the value of phoneNumber
     *
     * @return  self
     */ 
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }


    /**
     * Get the value of customerMessage
     */ 
    public function getCustomerMessage()
    {
        return $this->customerMessage;
    }

    /**
     * Set the value of customerMessage
     *
     * @return  self
     */ 
    public function setCustomerMessage($customerMessage)
    {
        $this->customerMessage = $customerMessage;

        return $this;
    }

    /**
     * Get the value of rentalDate
     */ 
    public function getRentalDate()
    {
        return $this->rentalDate;
    }

    /**
     * Set the value of rentalDate
     *
     * @return  self
     */ 
    public function setRentalDate($rentalDate)
    {
        $this->rentalDate = $rentalDate;

        return $this;
    }


    /**
     * Get the value of nbOfDays
     */ 
    public function getNbOfDays()
    {
        return $this->nbOfDays;
    }

    /**
     * Set the value of nbOfDays
     *
     * @return  self
     */ 
    public function setNbOfDays($nbOfDays)
    {
        $this->nbOfDays = $nbOfDays;

        return $this;
    }
}