<?php

namespace App\Entity;

use App\Repository\CustomerAddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CustomerAddressRepository::class)
 */
class CustomerAddress
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * * @Assert\Length(
     *      min = 2,
     *      max = 64,
     *      minMessage = "Your street name must be at least {{ limit }} characters long",
     *      maxMessage = "Your street name cannot be longer than {{ limit }} characters"
     * )
     * @Assert\Regex(
     *     pattern     = "/^[a-z0-9'\.\-\s\,]*$/i",
     *     message     = "The street name has forbidden symbols"
     * )
     */
    private ?string $street;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\Length(
     *      min = 2,
     *      max = 64,
     *      minMessage = "Your apartment number must be at least {{ limit }} characters long",
     *      maxMessage = "Your apartment number cannot be longer than {{ limit }} characters"
     * )
     * @Assert\Regex(
     *     pattern     = "/^[a-z0-9_-]*$/i",
     *     message     = "The apartment name has forbidden symbols"
     * )
     */
    private ?string $apartment;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\Length(
     *      min = 2,
     *      max = 64,
     *      minMessage = "Your city name must be at least {{ limit }} characters long",
     *      maxMessage = "Your city name cannot be longer than {{ limit }} characters"
     * )
     * @Assert\Regex(
     *     pattern     = "/^[a-z0-9-]*$/i",
     *     message     = "The city name has forbidden symbols"
     * )
     */
    private ?string $city;

    /**
     * @ORM\Column(type="string", length=2)
     * @Assert\Length(
     *      min = 2,
     *      max = 2,
     *      minMessage = "Your country name must be at least {{ limit }} characters long",
     *      maxMessage = "Your country name cannot be longer than {{ limit }} characters"
     * )
     * @Assert\Regex(
     *     pattern     = "/^[A-Z]*$/",
     *     message     = "The country name has forbidden symbols"
     * )
     */
    private ?string $country;

    /**
     * @ORM\Column(type="string", length=16)
     * @Assert\Length(
     *      min = 2,
     *      max = 16,
     *      minMessage = "Your zip code must be at least {{ limit }} characters long",
     *      maxMessage = "Your zip code cannot be longer than {{ limit }} characters"
     * )
     * @Assert\Regex(
     *     pattern     = "/^[0-9]*$/",
     *     message     = "The zip code has forbidden symbols"
     * )
     */
    private ?string $zipCode;

    /**
     * @ORM\OneToOne(targetEntity=Customer::class, inversedBy="customerAddress", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

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
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * @param string $street
     * @return $this
     */
    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getApartment(): ?string
    {
        return $this->apartment;
    }

    /**
     * @param string $apartment
     * @return $this
     */
    public function setApartment(string $apartment): self
    {
        $this->apartment = $apartment;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return $this
     */
    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return $this
     */
    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    /**
     * @param string $zipCode
     * @return $this
     */
    public function setZipCode(string $zipCode): self
    {
        $this->zipCode = $zipCode;

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
     * @param Customer $customer
     * @return $this
     */
    public function setCustomer(Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'street' => $this->getStreet(),
            'apartment' => $this->getApartment(),
            'city' => $this->getCity(),
            'country' => $this->getCountry(),
            'zipCode' => $this->getZipCode(),
        ];
    }
}
