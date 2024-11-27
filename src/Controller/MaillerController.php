<?php

    // src/Controller/MailerController.php
namespace App\Controller;

    use Symfony\Component\Mime\Email;
    use Symfony\Component\Mailer\MailerInterface;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

class MaillerController
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    #[Route('/send-email', name: 'send_email')]
    public function sendEmail(): Response
    {
        // Créez un objet Email
        $email = (new Email())
            ->from('no-reply@example.com')  // Adresse de l'expéditeur
            ->to('mailtrap.club@gmail.com')  // Adresse du destinataire
            ->subject('Hi')  // Sujet de l'email
            ->text('Ceci est un e-mail de test envoyé depuis Symfony avec Mailtrap.')  // Corps du message en texte brut
            ->html('<p>Ceci est un <strong>e-mail de test</strong> envoyé depuis Symfony avec Mailtrap.</p>');  // Corps du message en HTML

        // Envoyer l'email
        $this->mailer->send($email);

        // Retourner une réponse après l'envoi
        return new Response('E-mail envoyé avec succès !');
    }


}