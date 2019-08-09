<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use App\Controller\FreeTimeController;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(collectionOperations={
 *     "get",
 *     "get_free"={
 *         "method"="get",
 *         "path"="/event/free",
 *         "controller"=FreeTimeController::class,
 *     }
 *     })
 * @ApiFilter(DateFilter::class, properties={"start.dateTime"})
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $googleId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $summary;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $location;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Start", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $start;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\End", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $end;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Recurrence", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $recurence;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Attendees", mappedBy="event", orphanRemoval=true , cascade={"persist", "remove"})
     */
    private $attendees;

    /**
     * @var self
     * @MaxDepth(2)
     * @ORM\OneToOne(targetEntity="App\Entity\Reminder", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $reminder;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="Events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->attendees = new ArrayCollection();
        $this->reminder = new Reminder();
        $this->recurence = new Recurrence();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

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

    public function getStart(): ?Start
    {
        return $this->start;
    }

    public function setStart(Start $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?End
    {
        return $this->end;
    }

    public function setEnd(End $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getRecurence(): ?Recurrence
    {
        return $this->recurence;
    }

    public function setRecurence(Recurrence $recurence): self
    {
        $this->recurence = $recurence;

        return $this;
    }

    /**
     * @return Collection|Attendees[]
     */
    public function getAttendees(): Collection
    {
        return $this->attendees;
    }

    public function addAttendee(Attendees $attendee): self
    {
        if (!$this->attendees->contains($attendee)) {
            $this->attendees[] = $attendee;
            $attendee->setEvent($this);
        }

        return $this;
    }

    public function removeAttendee(Attendees $attendee): self
    {
        if ($this->attendees->contains($attendee)) {
            $this->attendees->removeElement($attendee);
            // set the owning side to null (unless already changed)
            if ($attendee->getEvent() === $this) {
                $attendee->setEvent(null);
            }
        }

        return $this;
    }

    public function getReminder(): ?Reminder
    {
        return $this->reminder;
    }

    public function setReminder(Reminder $reminder): self
    {
        $this->reminder = $reminder;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGoogleId()
    {
        return $this->googleId;
    }

    /**
     * @param mixed $googleId
     */
    public function setGoogleId($googleId): void
    {
        $this->googleId = $googleId;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }


}
