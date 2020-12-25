<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank
     * @Assert\DateTime
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $address;

    /**
     * @ORM\Column(type="object")
     * @Assert\NotBlank
     * @Assert\Type(type="array")
     */
    private $coordinates = [];

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     */
    private $join_limit;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="events")
     * ORM\OrderBy({"id" = "DESC"})
     */
    private $members;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="events")
     */
    private $organizer;

    /**
     * @ORM\ManyToOne(targetEntity=Community::class, inversedBy="events")
     */
    private $community;

    public function __construct()
    {
        $this->members = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate($date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCoordinates()
    {
        return $this->coordinates;
    }

    public function setCoordinates($coordinates): self
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getJoinLimit(): ?int
    {
        return $this->join_limit;
    }

    public function setJoinLimit(int $join_limit): self
    {
        $this->join_limit = $join_limit;

        return $this;
    }

    public function getMembers()
    {
        return $this->members;
    }

    public function addMember(User $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
        }

        return $this;
    }

    public function removeMember(User $member): self
    {
        $this->members->removeElement($member);

        return $this;
    }

    public function getOrganizer(): ?User
    {
        return $this->organizer;
    }

    public function setOrganizer(?User $organizer): self
    {
        $this->organizer = $organizer;

        return $this;
    }

    public function getCommunity(): ?Community
    {
        return $this->community;
    }

    public function setCommunity(?Community $community): self
    {
        $this->community = $community;

        return $this;
    }

    public function setData($data)
    {
        $this->setName($data['name'] ?? false);
        $this->setAddress($data['address'] ?? false);
        $this->setCoordinates($data['coordinates'] ?? false);
        $this->setDescription($data['description'] ?? false);
        $this->setJoinLimit($data['join_limit'] ?? false);
        $this->setOrganizer($data['user'] ?? null);
        $this->setDate(\DateTime::createFromFormat('Y-m-d H:i',$data['date']) ?? false);
    }
}
