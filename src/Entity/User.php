<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;



/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $user_identification;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8", minMessage="Le mot de passe saisi est incorrect.")
     */
    private $password;

    private $plainPassword;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserIdentification(): ?string
    {
        return $this->user_identification;
    }

    public function setUserIdentification(string $user_identification): self
    {
        $this->user_identification = $user_identification;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function eraseCredentials() {}

    public function getSalt() {}

    public function getUsername() {}

    public function getRoles()
    {
        return ['ROLE_ADMIN'];
    }

}
