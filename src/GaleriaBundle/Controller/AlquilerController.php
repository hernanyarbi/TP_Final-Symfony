<?php

namespace GaleriaBundle\Controller;

use GaleriaBundle\Entity\Alquiler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;



/**
 * Alquiler controller.
 *
 * @Route("alquiler")
 */
class AlquilerController extends Controller
{
    /**
     * Lists all alquiler entities.
     *
     * @Route("/", name="alquiler_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $alquilers = $em->getRepository('GaleriaBundle:Alquiler')->findAll();
        $response = new Response();
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $response->setContent(json_encode(array(
        'alquileres' => $serializer->serialize($alquilers, 'json'),
        )));
        $response->headers->set('Content-Type', 'application/json');
        return $response; 
    }

    /**
     * Creates a new alquiler entity.
     *
     * @Route("/new", name="alquiler_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $request->request->replace($data);
       
        $alquiler = new Alquiler();


        $propietarioArray= $request->request->get('propietario');
        $idPropietario = $propietarioArray['id'];
        $em = $this->getDoctrine()->getManager();
        $prop= $em->getRepository("GaleriaBundle:Propietario")->find($idPropietario);


        $localArray= $request->request->get('local');
        $idLocal = $localArray['id'];
        $em = $this->getDoctrine()->getManager();
        $loc= $em->getRepository("GaleriaBundle:Local")->find($idLocal);

        $alquiler->setLocal($loc);
        $alquiler->setPropietario($prop);
        
        $alquiler->setPlazomes($request->request->get('plazomes'));
        $alquiler->setCostoalquiler($request->request->get('costoalquiler'));
       
        $fecha = new \DateTime($request->request->get('fechaAlquiler'));
        $alquiler->setFechaAlquiler($fecha);       
       
       
        $em->persist($alquiler);
        $em->flush();
       
        $result['status'] = 'ok';
        return new Response(json_encode($result), 200);
    }

    /**
     * Finds and displays a alquiler entity.
     *
     * @Route("/{id}", name="alquiler_show")
     * @Method("GET")
     */
    public function showAction(Alquiler $alquiler)
    {
        $deleteForm = $this->createDeleteForm($alquiler);

        return $this->render('alquiler/show.html.twig', array(
            'alquiler' => $alquiler,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing alquiler entity.
     *
     * @Route("/{id}/edit", name="alquiler_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Alquiler $alquiler)
    {
        $data = json_decode($request->getContent(), true);
        $request->request->replace($data);
        $sn = $this->getDoctrine()->getManager();
        $alq = $sn->getRepository('GaleriaBundle:Alquiler')->find($request->request->get('id'));



        $propietarioArray= $request->request->get('propietario');
        $idPropietario = $propietarioArray['id'];
        $em = $this->getDoctrine()->getManager();
        $prop= $em->getRepository("GaleriaBundle:Propietario")->find($idPropietario);


        $localArray= $request->request->get('local');
        $idLocal = $localArray['id'];
        $em = $this->getDoctrine()->getManager();
        $loc= $em->getRepository("GaleriaBundle:Local")->find($idLocal);

        $alq->setLocal($loc);
        $alq->setPropietario($prop);
        
        $alq->setPlazomes($request->request->get('plazomes'));
        $alq->setCostoalquiler($request->request->get('costoalquiler'));
       
        $fecha = new \DateTime($request->request->get('fechaAlquiler'));
        $alq->setFechaAlquiler($fecha);     

        $sn->flush();

        $result['status'] = 'ok';
        return new Response(json_encode($result), 200);
    }

    /**
     * Deletes a alquiler entity.
     *
     * @Route("/{id}", name="alquiler_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Alquiler $alquiler)
    {
        $data = new Alquiler;
        $sn = $this->getDoctrine()->getManager();
    
        $alq = $this->getDoctrine()->getRepository('GaleriaBundle:Alquiler')->find($alquiler);
        $sn->remove($alq);
        $sn->flush();
        $result['status'] = 'ok';
        return new Response(json_encode($result), 200);
    }

    /**
     * Creates a form to delete a alquiler entity.
     *
     * @param Alquiler $alquiler The alquiler entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Alquiler $alquiler)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('alquiler_delete', array('id' => $alquiler->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
