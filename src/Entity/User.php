<?php

namespace App\Entity;


use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface, \Serializable
{
    use TimestampableTrait;

    const ROLE_DEFAULT = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const RESET_TOKEN_TTL = 60 * 60 * 12;

    public static array $labels = [
        self::ROLE_DEFAULT => 'Usuario',
        self::ROLE_ADMIN => 'Administrador',
    ];

    private ?int $id = null;
    private string $email;
    private string $name;
    private string $address;
    private string $phone;
    private array $roles = [];
    private ?string $password;
    private string $country;
    private string $city;
    private string $municipality;
    private string $postalCode;
    private string $companyName;
    private ?string $commentary;
    private bool $enabled;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): User
    {
        $this->id = $id;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): User
    {
        $this->email = $email;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): User
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): User
    {
        $this->address = $address;

        return $this;
    }

    public function getMunicipality(): string
    {
        return $this->municipality;
    }

    public function setMunicipality(string $municipality): User
    {
        $this->municipality = $municipality;

        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): User
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): User
    {
        $this->country = $country;

        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): User
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): User
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getContactPerson(): string
    {
        return $this->contactPerson;
    }

    public function setContactPerson(string $contactPerson): User
    {
        $this->contactPerson = $contactPerson;

        return $this;
    }

    public function getCompanyName(): string
    {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): User
    {
        $this->companyName = $companyName;

        return $this;
    }


    public function getCommentary(): ?string
    {
        return $this->commentary;
    }

    public function setCommentary(?string $commentary): User
    {
        $this->commentary = $commentary;

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): User
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): User
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): User
    {
        $this->roles = $roles;

        return $this;
    }

    public function getUserIdentifier(): String
    {
        return (string) $this->email;
    }

    public function getUsername(): string
    {
        return (string) $this->email;
    }


    public function serialize(): ?string
    {
        return serialize([
            $this->id,
            $this->email,
            $this->password,
        ]);
    }

    public function unserialize($data)
    {
        list(
            $this->id,
            $this->email,
            $this->password) = unserialize($data);
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function setInitialRole(): self
    {
        $this->roles = [static::ROLE_DEFAULT];

        return $this;
    }

    public function isAdmin(): bool
    {
        if (in_array(self::ROLE_ADMIN, $this->getRoles())) {
            return true;
        }

        return false;
    }
}
