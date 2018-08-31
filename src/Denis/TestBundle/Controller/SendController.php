<?php

namespace Denis\TestBundle\Controller;

use Denis\TestBundle\Entity\Category;
use Denis\TestBundle\Entity\Contact;
use Denis\TestBundle\Entity\Groupmail;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SendController extends Controller
{
    /**
     * @Route("/send", name="send_groupmail")
     * @param Request $request
     * @return Response
     */
    public function sendAction(Request $request)
    {
        $mail = new Groupmail();

        $form = $this->createFormBuilder($mail)
            ->add('name', TextType::class)
            ->add('email', EmailType::class)
            ->add('subject', TextType::class)
            ->add('content', TextType::class)
            ->add('category', EntityType::class,
                array('class' => Category::class, 'choice_label' => 'name'))
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $mail = $form->getData();

            $category = $mail->getCategory();

            $contact = new Contact();

            $contact
                ->setName($mail->getName())
                ->setEmail($mail->getEmail())
                ->setSubject($mail->getSubject())
                ->setContent($mail->getContent())
                ->setCategory($mail->getCategory());

            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();

            $transporter = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
                ->setUsername('denis.demouveau@gmail.com')
                ->setPassword('T5OGroV<');

            $mailer = \Swift_Mailer::newInstance($transporter);

            $msg = (new \Swift_Message($mail->getSubject()))
                ->setFrom(array('denis.demouveau@outlook.com' => 'Denis Demouveau'))
                ->setTo($category->getEmail_1())
                ->setBody($this->renderView('DenisTestBundle:Email:contact.html.twig',
                    array('name' => $mail->getName(),
                        'email' => $mail->getEmail(),
                        'content' => $mail->getContent(),
                        'groupe' => $category->getName())));

            if ($category->getEmail_2())
            {
                $msg->setCc($category->getEmail_2());
            }

            $mailer->send($msg);

            return $this->redirectToRoute('home_page');
        }

        return $this->render('DenisTestBundle:Form:send.html.twig', array('form' => $form->createView()));
    }
}