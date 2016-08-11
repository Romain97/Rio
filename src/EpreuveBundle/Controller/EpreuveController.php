<?php

namespace EpreuveBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use EpreuveBundle\Entity\Athlete;
use EpreuveBundle\Form\AthleteType;
use EpreuveBundle\Entity\Epreuve;
use EpreuveBundle\Form\EpreuveType;

/**
 * Epreuve controller.
 *
 */
class EpreuveController extends Controller
{
    /**
     * Lists all Epreuve entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $epreuves = $em->getRepository('EpreuveBundle:Epreuve')->findBy(
      array(), // Critere
      array(), // Tri
      3,       // Limite
      0        // Offset
);
      
  

        return $this->render('default/index.html.twig', array(
            'epreuves' => $epreuves,
        ));
    }

    /**
     * Creates a new Epreuve entity.
     *
     */
    public function newAction(Request $request)
    {
        $epreuve = new Epreuve();
        $form = $this->createForm('EpreuveBundle\Form\EpreuveType', $epreuve);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($epreuve);
            $em->flush();

            return $this->redirectToRoute('epreuve_show', array('id' => $epreuve->getId()));
        }

        return $this->render('epreuve/new.html.twig', array(
            'epreuve' => $epreuve,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Epreuve entity.
     *
     */
    public function showAction(Request $request, $id)
    {
        // $deleteForm = $this->createDeleteForm($epreuve);

        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        

        $epreuve = $em->getRepository('EpreuveBundle:Epreuve')->findOneById($id);
        $athletes = $em->getRepository('EpreuveBundle:Athlete')->findByIdepreuve($id);

        $content = $request->request->get('name');

        if($content != NULL) {
            $com = new Commentaire();
            $com->setName($content);
            $com->setIdarticle($id);

            $em->persist($com);
            $em->flush();
        }
        return $this->render('epreuve/show.html.twig', array(
            'epreuve' => $epreuve,
            'athletes' => $athletes,
            // 'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Epreuve entity.
     *
     */
    public function editAction(Request $request, Epreuve $epreuve)
    {
        $deleteForm = $this->createDeleteForm($epreuve);
        $editForm = $this->createForm('EpreuveBundle\Form\EpreuveType', $epreuve);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($epreuve);
            $em->flush();

            return $this->redirectToRoute('epreuve_edit', array('id' => $epreuve->getId()));
        }

        return $this->render('epreuve/edit.html.twig', array(
            'epreuve' => $epreuve,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Epreuve entity.
     *
     */
    public function deleteAction(Request $request, Epreuve $epreuve)
    {
        $form = $this->createDeleteForm($epreuve);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($epreuve);
            $em->flush();
        }

        return $this->redirectToRoute('epreuve_index');
    }

    /**
     * Creates a form to delete a Epreuve entity.
     *
     * @param Epreuve $epreuve The Epreuve entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Epreuve $epreuve)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('epreuve_delete', array('id' => $epreuve->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
