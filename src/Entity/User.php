<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    #[Assert\NotNull]
    #[Assert\Unique]
    #[Assert\Email(
        message: "L'adresse email {{ value }} n'est pas valide.",
    )]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Length(
        min: 8,
        minMessage: 'Votre mot de passe doit comporter minimum {{ limit }} caractères.',
    )]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Assert\Length(
        min: 2,
        max: 60,
        minMessage: 'Votre nom doit comporter minimum {{ limit }} caractères.',
        maxMessage: 'Votre nom doit comporter maximum {{ limit }} caractères.',
    )]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Assert\Length(
        min: 2,
        max: 60,
        minMessage: 'Votre prénom doit comporter minimum {{ limit }} caractères.',
        maxMessage: 'Votre prénom doit comporter maximum {{ limit }} caractères.',
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotNull]
    #[Assert\Length(
        min: 2,
        max: 60,
        minMessage: 'Votre pseudo doit comporter minimum {{ limit }} caractères.',
        maxMessage: 'Votre pseudo doit comporter maximum {{ limit }} caractères.',
    )]
    private ?string $pseudo = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedUp = null;

    #[ORM\Column]
    private ?bool $estActif = null;

    #[ORM\ManyToMany(targetEntity: Etablissement::class, inversedBy: 'users')]
    private Collection $etablissements_favoris;

    public function __construct()
    {
        $this->etablissements_favoris = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Etablissement>
     */
    public function getEtablissementsFavoris(): Collection
    {
        return $this->etablissements_favoris;
    }

    public function addEtablissementsFavori(Etablissement $etablissementsFavori): self
    {
        if (!$this->etablissements_favoris->contains($etablissementsFavori)) {
            $this->etablissements_favoris->add($etablissementsFavori);
        }

        return $this;
    }

    public function removeEtablissementsFavori(Etablissement $etablissementsFavori): self
    {
        $this->etablissements_favoris->removeElement($etablissementsFavori);

        return $this;
    }
}
