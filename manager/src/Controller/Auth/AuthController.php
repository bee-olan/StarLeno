<?php

namespace App\Controller\Auth;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('app/auth/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout(): Response
    {
        // controller can be blank: it will never be executed!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    /**
     * @Route("/test", name="test")
     * @return Response
     */
    public function test(\Swift_Mailer $mailer): Response
    {
        $messageBody = 'Тема: Обновление графика работы с 1 февраля

Здравствуйте!

Сообщаем об изменении графика работы нашего офиса с 1 февраля 2025 года.

Новый график:
Понедельник-пятница: 9:00 - 19:00
Суббота: 10:00 - 16:00
Воскресенье: выходной

По всем вопросам обращайтесь по телефону: +7 (999) 123-45-67

С уважением,
Команда поддержки';

        $message = (new \Swift_Message('Обновление графика работы'))
            ->setFrom('admin@regtema.ru')
            ->setTo('bee.regtema@mail.ru')
            ->setBody($messageBody);

        try {
            $result = $mailer->send($message);
            dump($message->getHeaders()->toString());
            dump($mailer->getTransport()->ping());
            dd('Письмо отправлено', $result);
        } catch (\Exception $e) {
            dd('Ошибка отправки:', $e->getMessage(), $e->getTrace());
        }
    }
}
