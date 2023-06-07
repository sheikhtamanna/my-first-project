<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Loader\Configurator\mailer;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $contact = $form->getData();
            $subject = $contact['subject'];
            $address = $contact['email'];
            $message = $contact['message'];
            $email = (new TemplatedEmail())
               ->from($address)
               ->to('admin@gmail.com')
               ->subject($subject)
               ->text($message)
               ->htmlTemplate('emails/contact.html.twig')
               ->context([
                'contact' => $contact
               ]);
        $mailer->send($email);
        $this->addFlash('success', 'your message is successfully sent');
        
        }
        return $this->renderForm('contact/index.html.twig', [
            'form' => $form
        ]);
       
    }
}
