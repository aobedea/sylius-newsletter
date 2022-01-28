<?php

namespace App\Command;

use App\Entity\Customer\Customer;
use App\Entity\Newsletter;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment as Twig;
use Twig\Error\LoaderError;
use Twig\Error\SyntaxError;

class NewsletterSendCommand extends Command
{
    protected static $defaultName = 'app:newsletter-send';
    protected static $defaultDescription = 'Send newsletter to customers';

    public function __construct(
        string $name = null,
        ManagerRegistry $doctrine,
        MailerInterface $mailer,
        RouterInterface $router,
        Twig $twig
    ) {
        parent::__construct($name);
        $this->doctrine = $doctrine;
        $this->mailer = $mailer;
        $this->router = $router;
        $this->twig = $twig;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('newsletterId', InputArgument::REQUIRED, 'Newsletter id')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $newsletterId = $input->getArgument('newsletterId');

        if ($newsletterId) {
            $io->note(sprintf('You passed the newsletter id: %s', $newsletterId));
        }

        //get needed data
        $newsletter = $this->doctrine->getRepository(Newsletter::class)->find($newsletterId);

        if (!$newsletter instanceof Newsletter) {
            $io->error('Wrong newsletter id sent');

            exit;
        }

        $customers = $newsletter->getCustomers();

        //build the template & send email for each customer
        foreach ($customers as $customer) {
            try {
                $builtContent = $this->buildNewsletterContent($newsletter, $customer);
                $this->sendEmail($customer->getEmail(), $newsletter->getSubject(), $builtContent);
            } catch (SyntaxError|LoaderError $e) {
                $io->error([
                    'Fail to build the newsletter for ' . $customer->getId(),
                    'Reason: ' . $e->getMessage(),
                ]);
            } catch (TransportExceptionInterface $e) {
                $io->error([
                    'Fail to send the newsletter to ' . $customer->getId(),
                    'Reason: ' . $e->getMessage(),
                ]);
            }

            $io->success('Successfully sent the newsletter to '. $customer->getId());
        }

        $io->success('Successfully sent the newsletter to customers');

        return Command::SUCCESS;
    }

    /**
     * @param Newsletter $newsletter
     * @param Customer $customer
     * @return string
     * @throws LoaderError|SyntaxError
     */
    private function buildNewsletterContent(Newsletter $newsletter, Customer $customer): string
    {
        $newsletterTemplate = $this->twig->createTemplate($newsletter->getContent());

        return $newsletterTemplate->render(
            [
                'firstname' => $customer->getFirstName(),
                'newsletter_name' => $newsletter->getSubject(),
                'unsubscribe_link' => $this->getUnsubscribeLink($newsletter->getId(), $customer->getId())
            ]
        );
    }

    /**
     * @param string $recipient
     * @param string $subject
     * @param string $content
     * @return void
     * @throws TransportExceptionInterface
     */
    private function sendEmail(string $recipient, string $subject, string $content)
    {
        $email = (new Email())
            ->from('no-reply@sylius-first-website.com')
            ->to($recipient)
            ->subject($subject)
            ->html($content);

        $this->mailer->send($email);
    }

    /**
     * @param int $newsletterId
     * @param int $customerId
     * @return string
     */
    private function getUnsubscribeLink(int $newsletterId, int $customerId): string
    {
        return $this->router->generate(
            'app_newsletter_unsubscribe_action',
            [
                'customerId' => $customerId,
                'newsletterId' => $newsletterId,
            ],
            UrlGenerator::ABSOLUTE_URL
        );
    }
}
