<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReminderRepository")
 */
class Reminder
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Overrides", mappedBy="reminder", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $overrides;

    /**
     * @ORM\Column(type="boolean" , nullable = true)
     */
    private $userdefault;

    public function __construct()
    {
        $this->overrides = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }



    /**
     * @return Collection|Overrides[]
     */
    public function getOverrides(): Collection
    {
        return $this->overrides;
    }

    public function addOverride(Overrides $override): self
    {
        if (!$this->overrides->contains($override)) {
            $this->overrides[] = $override;
            $override->setReminder($this);
        }

        return $this;
    }

    public function removeOverride(Overrides $override): self
    {
        if ($this->overrides->contains($override)) {
            $this->overrides->removeElement($override);
            // set the owning side to null (unless already changed)
            if ($override->getReminder() === $this) {
                $override->setReminder(null);
            }
        }

        return $this;
    }

    public function getUserdefault(): ?bool
    {
        return $this->userdefault;
    }

    public function setUserdefault(bool $userdefault): self
    {
        $this->userdefault = $userdefault;

        return $this;
    }
}
