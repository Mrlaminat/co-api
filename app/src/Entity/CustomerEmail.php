<?php

namespace App\Entity;

use App\Repository\CustomerEmailRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=CustomerEmailRepository::class)
 * @UniqueEntity(
 *     "email",
 *     message="The email '{{ value }}' is already in use."
 * )
 */
class CustomerEmail implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=64, unique=true)
     * @Assert\NotNull(message="Please enter an email")
     * @Assert\Length(
     *      min = 5,
     *      max = 64,
     *      minMessage = "Your email must be at least {{ limit }} characters long",
     *      maxMessage = "Your email cannot be longer than {{ limit }} characters"
     * )
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     */
    private ?string $email;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="customerEmail")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Customer $customer;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Customer|null
     */
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer|null $customer
     * @return $this
     */
    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->getId(),
            'email' => $this->getEmail()
        );
    }
}
