<?php

namespace App\Controller;

use App\Entity\Customer\Customer;
use App\Entity\Newsletter;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class NewsletterController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @param int $newsletterId
     * @param int $customerId
     * @return Response
     */
    public function unsubscribe(int $newsletterId, int $customerId): Response
    {
        $newsletter = $this->managerRegistry->getRepository(Newsletter::class)->find($newsletterId);
        $customer = $this->managerRegistry->getRepository(Customer::class)->find($customerId);

        /**
         * @var Newsletter $newsletter
         */
        $newsletter->removeCustomer($customer);

        return $this->json(['message' => 'Customer ' . $customerId . ' unsubscribed']);
    }
}
