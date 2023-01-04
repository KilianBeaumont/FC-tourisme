<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;


#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pseudo = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedUp = null;

    #[ORM\Column]
    private ?bool $estActif = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(?string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedUp(): ?\DateTimeInterface
    {
        return $this->updatedUp;
    }

    public function setUpdatedUp(\DateTimeInterface $updatedUp): self
    {
        $this->updatedUp = $updatedUp;

        return $this;
    }

    public function isEstActif(): ?bool
    {
        return $this->estActif;
    }

    public function setEstActif(bool $estActif): self
    {
        $this->estActif = $estActif;

        return $this;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('prenom', new Assert\Length([
            'min' => 2,
            'max' => 60,
            'minMessage' => 'Votre prénom doit contenir au minimum 2 caractères',
            'maxMessage' => 'Votre prénom doit contenir au maximum 60 caractères',
        ]));

        $metadata->addPropertyConstraint('prenom', new Assert\NotBlank());

        $metadata->addPropertyConstraint('nom', new Assert\Length([
            'min' => 2,
            'max' => 60,
            'minMessage' => 'Votre nom doit contenir au minimum 2 caractères',
            'maxMessage' => 'Votre nom doit contenir au maximum 60 caractères',
        ]));

        $metadata->addPropertyConstraint('nom', new Assert\NotBlank());

        $metadata->addPropertyConstraint('pseudo', new Assert\Length([
            'min' => 2,
            'max' => 60,
            'minMessage' => 'Votre pseudo doit contenir au minimum 2 caractères',
            'maxMessage' => 'Votre pseudo doit contenir au maximum 60 caractères',
        ]));

        $metadata->addPropertyConstraint('password', new Assert\Length([
            'min' => 8,
            'minMessage' => 'Votre mot de passe doit contenir au minimum 8 caractères',
        ]));

        $metadata->addPropertyConstraint('password', new Assert\NotBlank());

        $metadata->addPropertyConstraint('email', new Assert\Email([
            'message' => 'Votre email "{{ value }}" est invalide.',
        ]));
        $metadata->addPropertyConstraint('email', new Assert\NotBlank());
        $metadata->addPropertyConstraint('email', new Assert\Unique());

        $metadata->addGetterConstraint('estActif', new IsTrue([
            'message' => 'Votre compte est inactif.',
        ]));
    }
}
