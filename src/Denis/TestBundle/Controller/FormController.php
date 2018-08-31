<?php

namespace Denis\TestBundle\Controller;

use Denis\TestBundle\Entity\Members;
use Denis\TestBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FormController extends Controller
{
    public function indexAction()
    {
        return $this->render('DenisTestBundle:Form:index.html.twig');
    }

    /**
     * @Route("/register", name="register_user")
     * @param Request $request
     * @return Response
     */
    public function registerAction(Request $request)
    {
        $user = new Members();

        $form = $this->createFormBuilder($user)
            ->add('name', TextType::class)
            ->add('email', EmailType::class)
            ->add('category', EntityType::class,
                array('class' => Category::class, 'choice_label' => 'name'))
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $transporter = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
                ->setUsername('denis.demouveau@gmail.com')
                ->setPassword('T5OGroV<');

            $mailer = \Swift_Mailer::newInstance($transporter);

            $msg = (new \Swift_Message('Welcome'))
                ->setFrom(array('denis.demouveau@outlook.com' => 'Denis Demouveau'))
                ->setTo($user->getEmail())
                ->setBody($this->renderView('DenisTestBundle:Email:registered.html.twig',
                    array('name' => $user->getName())));
            $mailer->send($msg);

            return $this->redirectToRoute('home_page');
        }

        return $this->render('DenisTestBundle:Form:register.html.twig', array('form' => $form->createView()));
    }
}