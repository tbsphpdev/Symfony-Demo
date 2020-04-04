<?php

namespace Chartwell\Modules\ClientAndSiteManagementBundle\Controller;

use Chartwell\Modules\ClientAndSiteManagementBundle\Controller\Core\ModuleController;

use Chartwell\Modules\ClientAndSiteManagementBundle\Entity\Project;
use Chartwell\Modules\ClientAndSiteManagementBundle\Entity\Projects;
use Chartwell\Modules\ClientAndSiteManagementBundle\Entity\Site;
use Chartwell\Modules\ClientAndSiteManagementBundle\Form\Type\SiteProjectType;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\EventDispatcher\Tests\Service;
use Symfony\Component\HttpFoundation\JsonResponse;


class ProjectsController extends ModuleController
{
    use \Chartwell\CoreBundle\Controller\traits\SoftDeleteableTrait;

    public function indexAction($page) {

        $sitesRepository = $this->getDoctrine()
            ->getRepository('ChartwellModulesClientAndSiteManagementBundle:Site');

        $sites = $sitesRepository->getSiteContracts();

        $contractsRepository = $this->getDoctrine()
            ->getRepository('ChartwellModulesClientAndSiteManagementBundle:Project');

        $perPage = $this->container->getParameter('pagination_per_page');

        $filter = $this->get('chartwell_core.filters')->getFilter();

        $contractsPaginator = $contractsRepository->getPage($page, $perPage, $filter);

       /* Breadcrumbs */
        $this->breadcrumbs->addItem('Projects');

        return $this->render('ChartwellModulesClientAndSiteManagementBundle:Projects:index.html.twig', array(
            'currentPage' => $page,
            'perPage' => $perPage,
            'contractsPaginator' => $contractsPaginator,
            'sites' => $sites
        ));
    }

    public function addAction(Request $request) {

        $this->denyAccessUnlessGranted('ROLE_CLIENT_AND_SITE_CAN_ADD', $this->module);

        $contract = new Project();
        $clientId = $request->get('client');

        if($clientId) {
            $client = $this->getDoctrine()
                ->getRepository('ChartwellModulesClientAndSiteManagementBundle:Client')
                ->findOneBy(array(
                    'id' => $clientId,
                    'isDeleted' => 0
                ));
            if(!$client) {
                return $this->createNotFoundException();
            }
            //$client->setClient($client);
        }
        $form = $this->createForm(new SiteProjectType(), $contract, array(
            'action' => $this->generateUrl('chartwell_modules_client_and_site_management_projects_add', array(
                'divisionSlug' => $this->division->getSlug()
            )),
            'method' => 'POST'
        ));

        $form->handleRequest($request);
        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
//            echo "<pre>";
//            print_r($contract);exit;
            $em->persist($contract);
            $em->flush();

            $session = $this->getRequest()->getSession();
            $session->getFlashBag()->add('message', 'Project have been created.');

            return $this->redirect($this->generateUrl('chartwell_modules_client_and_site_management_projects', array(
                'divisionSlug' => $this->division->getSlug()
            )));
        }

        /* Breadcrumbs */
        $this->breadcrumbs->addItem(
            'Projects',
            $this->generateUrl('chartwell_modules_client_and_site_management_projects', array(
                'divisionSlug' => $this->division->getSlug()
            ))
        );
        $this->breadcrumbs->addItem('New project');

        return $this->render('ChartwellModulesClientAndSiteManagementBundle:Projects:form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function viewAction(Request $request, $id) {
        $contractsRepository = $this->getDoctrine()
            ->getRepository('ChartwellModulesClientAndSiteManagementBundle:Project');

        $contract = $contractsRepository->findOneById($id);
        if(!$contract) {
            throw $this->createNotFoundException();
        }
        $this->breadcrumbs->addItem(
            'Projects',
            $this->generateUrl('chartwell_modules_client_and_site_management_projects', array(
                'divisionSlug' => $this->division->getSlug()
            ))
        );
        $this->breadcrumbs->addItem($contract->getContractNumber());

        return $this->render('ChartwellModulesClientAndSiteManagementBundle:Projects:view.html.twig', array(
            'contract' => $contract
        ));
    }

    public function editAction(Request $request, $id) {

        $this->denyAccessUnlessGranted('ROLE_CLIENT_AND_SITE_CAN_EDIT', $this->module);

        $contractsRepository = $this->getDoctrine()
            ->getRepository('ChartwellModulesClientAndSiteManagementBundle:Project');

        $contract = $contractsRepository->findOneById($id);
        if(!$contract) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(new SiteProjectType(), $contract, array(
            'action' => $this->generateUrl('chartwell_modules_client_and_site_management_projects_edit', array(
                'divisionSlug' => $this->division->getSlug(),
                'id' => $contract->getId()
            )),
            'method' => 'POST'
        ));

        $form->handleRequest($request);
        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($contract);
            $em->flush();

            $session = $this->getRequest()->getSession();
            $session->getFlashBag()->add('message', 'Project have been saved.');

            return $this->redirect($this->generateUrl('chartwell_modules_client_and_site_management_projects_view', array(
                'divisionSlug' => $this->division->getSlug(),
                'id' => $contract->getId()
            )));
        }

        /* Breadcrumbs */
        $this->breadcrumbs->addItem(
            'Projects',
            $this->generateUrl('chartwell_modules_client_and_site_management_projects', array(
                'divisionSlug' => $this->division->getSlug()
            ))
        );
        $this->breadcrumbs->addItem('Edit: '.$contract->getContractNumber());

        return $this->render('ChartwellModulesClientAndSiteManagementBundle:Projects:form.html.twig', array(
            'contract' => $contract,
            'form' => $form->createView()
        ));
    }

    public function deleteAction($id) {

        $this->denyAccessUnlessGranted('ROLE_CLIENT_AND_SITE_CAN_DELETE', $this->module);

        $contractsRepository = $this->getDoctrine()
            ->getRepository('ChartwellModulesClientAndSiteManagementBundle:Project');

        $contract = $contractsRepository->findOneById($id);
        if(!$contract) {
            throw $this->createNotFoundException();
        }

        $this->softDelete($contract);

        $session = $this->getRequest()->getSession();
        $session->getFlashBag()->add('message', 'Project have been deleted.');

        return $this->redirectToRoute('chartwell_modules_client_and_site_management_projects', array(
            'divisionSlug' => $this->division->getSlug()
        ));
    }

    public function getClientAction(){
        $siteId = $_POST['contract']['siteId'];
        $sitesRepository = $this->getDoctrine()
            ->getRepository('ChartwellModulesClientAndSiteManagementBundle:Site');

        $client = $sitesRepository->getClientSite($siteId);

        echo $client;
        exit;
    }

    public function sitesAction($id, $page) {

        $projectsRepository = $this->getDoctrine()
            ->getRepository('ChartwellModulesClientAndSiteManagementBundle:Project');

        $project = $projectsRepository->findOneById($id);
        if(!$project) {
            throw $this->createNotFoundException();
        }

        $sitesRepository = $this->getDoctrine()
            ->getRepository('ChartwellModulesClientAndSiteManagementBundle:Site');

        $perPage = $this->container->getParameter('pagination_per_page');

        $filter = $this->get('chartwell_core.filters')->getFilter();
        $filter['strict']['project'] = $project->getId();
        $sitesPaginator = $sitesRepository->getPage($page, $perPage, $filter);

        /* Breadcrumbs */
        $this->breadcrumbs->addItem(
            'Sites',
            $this->generateUrl('chartwell_modules_client_and_site_management_sites', array(
                'divisionSlug' => $this->division->getSlug()
            ))
        );
        $this->breadcrumbs->addItem($project->getContractNumber(),
            $this->generateUrl('chartwell_modules_client_and_site_management_sites_view', array(
                'divisionSlug' => $this->division->getSlug(),
                'id' => $project->getId()
            )));
        $this->breadcrumbs->addItem('Sites');

        return $this->render('ChartwellModulesClientAndSiteManagementBundle:Projects:sites.html.twig', array(
            'contact' => $project,
            'perPage' => $perPage,
            'currentPage' => $page,
            'sitesPaginator' => $sitesPaginator
        ));
    }



}
