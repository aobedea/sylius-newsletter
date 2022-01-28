<?php

declare(strict_types=1);

namespace App\Entity\Customer;

use Sylius\Component\Core\Model\Customer as BaseCustomer;
use App\Entity\Newsletter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_customer")
 */
class Customer extends BaseCustomer
{
    /**
     * Many Users have Many Groups.
     * @ManyToMany(targetEntity="App\Entity\Newsletter")
     * @JoinTable(name="sylius_customer_newsletter",
     *      joinColumns={@JoinColumn(name="customer_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="newsletter_id", referencedColumnName="id")}
     *      )
     */
    protected $newsletters;

    public function __construct()
    {
        parent::__construct();

        $this->newsletters = new ArrayCollection();
    }

    /**
     * @return Collection|Newsletter[]
     */
    public function getNewsletters(): Collection
    {
        return $this->newsletters;
    }

    public function addNewsletter(Newsletter $newsletter): self
    {
        if (!$this->newsletters->contains($newsletter)) {
            $this->newsletters[] = $newsletter;
        }
        return $this;
    }

    public function removeNewsletter(Newsletter $newsletter): self
    {
        $this->newsletters->removeElement($newsletter);
        return $this;
    }
}
