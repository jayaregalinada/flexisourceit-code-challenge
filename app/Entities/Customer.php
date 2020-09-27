<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Services\Customer\Enums\GenderEnum;

/**
 * @ORM\Entity
 * @ORM\Table(name="customers")
 */
class Customer extends AbstractCustomer
{
    /**
     * @ORM\Column(type="string")
     */
    protected $first_name;

    /**
     * @ORM\Column(type="string")
     */
    protected $last_name;

    /**
     * @ORM\Column(type="string")
     */
    protected $username;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $gender;

    /**
     * @ORM\Column(type="string")
     */
    protected $country;

    /**
     * @ORM\Column(type="string")
     */
    protected $city;

    /**
     * @ORM\Column(type="string")
     */
    protected $phone;

    /**
     * @ORM\Column(type="string")
     */
    protected $password;

    public function getUsername() : ?string
    {
        return $this->username;
    }

    public function setUsername(string $username) : Customer
    {
        $this->username = $username;

        return $this;
    }

    public function getGender() : ?int
    {
        return $this->gender;
    }

    /**
     * @param int|GenderEnum $gender
     *
     * @return $this
     */
    public function setGender($gender) : Customer
    {
        $this->gender = $gender instanceof GenderEnum ? $gender->getValue() : $gender;

        return $this;
    }

    public function getCountry() : ?string
    {
        return $this->country;
    }

    public function setCountry(string $country) : Customer
    {
        $this->country = $country;

        return $this;
    }

    public function getCity() : ?string
    {
        return $this->city;
    }

    public function setCity(string $city) : Customer
    {
        $this->city = $city;

        return $this;
    }

    public function getPhone() : ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone) : Customer
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPassword() : ?string
    {
        return $this->password;
    }

    /**
     * @param mixed|string $password
     *
     * @return \App\Entities\Customer
     */
    public function setPassword($password) : Customer
    {
        $this->password = $password;

        return $this;
    }

    public function getFullName() : string
    {
        return implode(' ', [$this->getFirstName(), $this->getLastName()]);
    }

    public function getFirstName() : ?string
    {
        return $this->first_name;
    }

    public function getLastName() : ?string
    {
        return $this->last_name;
    }

    public function setFirstName(string $firstName) : Customer
    {
        $this->first_name = $firstName;

        return $this;
    }

    public function setLastName(string $lastName) : Customer
    {
        $this->last_name = $lastName;

        return $this;
    }
}
