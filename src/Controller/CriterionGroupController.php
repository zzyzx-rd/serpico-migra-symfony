<?php

namespace App\Controller;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Form\ManageCriteriaForm;
use Form\Type\CriterionNameType;
use App\Entity\CriterionGroup;
use App\Entity\CriterionName;
use App\Entity\Department;
use App\Entity\Icon;
use App\Entity\Organization;
use App\Entity\Target;
use App\Entity\User;
use Repository\CriterionNameRepository;
use Repository\OrganizationRepository;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig_Environment;

class CriterionGroupController extends MasterController
{
    /**
     * @param Request $request
     * @return JsonResponse|Response
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/criteriongroup/create", name="createCriterionGroup")
     */
    public function createCriterionGroup(Request $request)
    {
        /**
         * @var User
         */
        $currentUser = MasterController::getAuthorizedUser();
        if (!$currentUser) {
            return self::unauthorized();
        }

        /**
         * Name of the new criterion group
         * @var string
         */
        $name = $request->get('name');
        /**
         * Owner of the new criterion group
         */
        $ownerOrg = $currentUser->getOrganization();
        /**
         * Linked department (optional)
         * @var string|null
         */
        $linkedDptId = $request->get('linked-dpt-id');



        if (!$name) {
            return new Response(null, 400);
        }

        $criterionGroup = new CriterionGroup($name, $ownerOrg);
        $criterionGroup->setCreatedBy($currentUser->getId());
        $em = $this->getEntityManager();

        if ($linkedDptId) {
            $repoDpt = $em->getRepository(Department::class);
            $department = $repoDpt->find($linkedDptId);
            if ($department === null) {
                return new Response(
                    "Department with id=$linkedDptId does not exist", 404
                );
            }
            $criterionGroup->setDepartment($department);
        }

        $em->persist($criterionGroup);
        $em->flush();

        return new JsonResponse([ 'id' => $criterionGroup->getId() ], 201);
    }

    /**
     * @param Request $request
     * @param int $cgpId
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function setCgpDepartment(Request $request, int $cgpId)
    {
        /**
         * Linked department (optional)
         * @var string|null
         */
        $linkedDptId = $request->get('linked-dpt-id');

        $em = self::getEntityManager();
        $repoCG = $em->getRepository(CriterionGroup::class);
        /** @var CriterionGroup|null */
        $cgp = $repoCG->find($cgpId);

        if (!$cgp) {
            return self::fieldDoesNotExist('Criterion group', $cgpId);
        }

        if (!$linkedDptId) {
            $cgp->setDepartment(null);
        } else {
            $repoD = $em->getRepository(Department::class);
            $dpt = $repoD->find($linkedDptId);
            $cgp->setDepartment($dpt);
        }

        $em->persist($cgp);
        $em->flush();

        return new Response(null, 204);
    }


    /**
     * @param Application $app
     * @param $id
     * @return JsonResponse|Response
     * @Route("/settings/criteriongroup/{id}")
     */
    public function getCriterionGroupById(
        Application $app,
        $id
    ) {
        if (!self::isAuthenticated()) {
            return self::unauthorized();
        }

        $em = $this->getEntityManager();
        /**
         * Repository of CriterionGroup
         */
        $repoCG = $em->getRepository(CriterionGroup::class);
        /**
         * @var CriterionGroup
         */
        $criterionGroup = $repoCG->find($id);

        if (!$criterionGroup) {
            return self::fieldDoesNotExist('Criterion group', $id);
        }

        return new JsonResponse($criterionGroup->toArray($app));
    }

    /**
     * @param Request $request
     * @param int $cgpId
     * @return JsonResponse|Response
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/criteriongroup/{id}/criterion")
     * @Route("/settings/criteriongroup/criterion/{cgpId}", name="addCriterion")
     */
    public function addCriterion(Request $request, int $cgpId)
    {
        $currentUser = $this->getAuthorizedUser();

        if (!$currentUser) {
            return self::unauthorized();
        }

        $name = (string)$request->get('name');
        $em = self::getEntityManager();
        $repoCG = $em->getRepository(CriterionGroup::class);
        /** @var CriterionGroup|null */
        $cgp = $repoCG->find($cgpId);

        if (!$cgp) {
            return self::fieldDoesNotExist('Criterion group', $cgpId);
        }
        if (!$name) {
            return self::badRequest();
        }

        foreach ($cgp->getCriteria() as $c) {
            if ($c->getName() === $name) {
                return new Response(
                    'Il existe déjà un critère portant ce nom dans cette matrice',
                    405,
                    [ 'Content-Type' => 'text/plain' ]
                );
            }
        }

        $criterion = (new CriterionName)
            ->setName($name)
            ->setCriterionGroup($cgp)
            ->setCreatedBy($currentUser->getId())
            ->setOrganization($currentUser->getOrganization())
            ->setDepartment($cgp->getDepartment());

        $cgp->addCriterion($criterion);

        $em->persist($criterion);
        $em->persist($cgp);
        $em->flush();

        return new JsonResponse([
            'id' => $criterion->getId(),
            'name' => $criterion->getName()
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/criteriongroup/criterion", name="removeCriterion")
     */
    public function removeCriterion(
        Request $request
    ) {
        $currentUser = $this->getAuthorizedUser();
        if (!$currentUser) {
            return self::unauthorized();
        }

        $crtId = $request->get('crtId');
        if (!$crtId) {
            return new Response(null, 400);
        }


        $em = $this->getEntityManager();
        /** @var CriterionNameRepository */
        $cnRepo = $em->getRepository(CriterionName::class);
        /**
         * @var CriterionName
         */
        $criterion = $cnRepo->find($crtId);


        if (!$criterion) {
            return new Response(null, 404);
        }

        if (
            $criterion->getCriterionGroup()->getOrganization()->getId()
            !== $currentUser->getOrganization()->getId()
        ) {
            return new Response(null, 403);
        }

        if ($cnRepo->criterionIsUsed($criterion)) {
            return new Response(null, 409);
        }

        $targetRepo = $em->getRepository(Target::class);
        foreach ($targetRepo->findBy([ 'cName' => $criterion ]) as $target) {
            $em->remove($target);
        }

        $em->remove($criterion);
        $em->flush();

        return new Response(null, 204);
    }


    /**
     * @param Request $request
     * @param ?int$id
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/criteriongroup/{id}/name", name="updateCriterionGroupName")
     */
    public function updateCriterionGroupName(
        Request $request,
      ?int $id
    ) {
        if (!self::isAuthenticated()) {
            return self::unauthorized();
        }

        /**
         * @var string
         */
        $name = $request->get('name');
        if (!$name) {
            return self::badRequest();
        }

        $em = $this->getEntityManager();
        $repoCG = $em->getRepository(CriterionGroup::class);

        /**
         * @var CriterionGroup
         */
        $criterionGroup = $repoCG->find($id);

        if (!$criterionGroup) {
            return self::fieldDoesNotExist('Criterion group', $id);
        }

        $criterionGroup->setName($name);

        $em->persist($criterionGroup);
        $em->flush();

        return new Response(null, 204);
    }

    /**
     * @param Request $request
     * @param ?int$id
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/criterion/{id}/name", name="updateCriterionName")
     */
    public function updateCriterionName(
        Request $request,
      ?int $id
    ) {
        if (!self::isAuthenticated()) {
            return self::unauthorized();
        }

        /**
         * @var string
         */
        $name = $request->get('name');
        if (!$name) {
            return self::badRequest();
        }

        $em = $this->getEntityManager();
        $repoC = $em->getRepository(CriterionName::class);

        /**
         * @var CriterionName
         */
        $criterion = $repoC->find($id);

        if (!$criterion) {
            return self::fieldDoesNotExist('Criterion', $id);
        }

        $criterion->setName($name);

        $em->persist($criterion);
        $em->flush();

        return new Response(null, 204);
    }

    /**
     * @param Request $request
     * @param int $cnId
     * @return JsonResponse|Response
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/criterion/{cnId}", name="setCriterionIcon")
     */
    public function setCriterionIcon(Request $request, int $cnId) {
        $icoId = $request->get('ico-id');
        if (!$icoId) {
            return self::badRequest();
        }

        $currentUser = self::getAuthorizedUser();
        if (!$currentUser) {
            return self::unauthorized();
        }

        $em = self::getEntityManager();
        $repoI = $em->getRepository(Icon::class);
        /** @var Icon|null */
        $icon = $icoId === -1 ? null : $repoI->find($icoId);

        $currentUserRole = $currentUser->getRole();
        $currentUserDpt = $currentUser->getDepartment();
        if (
            ($currentUserRole === 2 and !$currentUserDpt)
            or $currentUserRole === 3
        ) {
            return self::unauthorized();
        }

        $repoCN = $em->getRepository(CriterionName::class);
        $findBy = [ 'id' => $cnId ];

        if ($currentUserRole === 1) {
            $findBy['organization'] = $currentUser->getOrganization();
        } else if ($currentUserRole === 2) {
            $findBy['department'] = [ $currentUserDpt, null ];
        }

        /** @var CriterionName|null */
        $criterion = $repoCN->findOneBy($findBy);

        if (!$criterion) {
            return new Response(null, 404);
        }

        $criterion->setIcon($icon);
        $em->persist($criterion);
        $em->flush();

        return new JsonResponse([
            'icon' => $icon ? $icon->getChar() : null,
            'type' => $icon ? $icon->getType() : null
        ], 200);
    }


    /**
     * @param ?int$id
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/criteriongroup/delete/{id}",name="deleteCriterionGroup")
     */
    public function deleteCriterionGroup(
      ?int $id
    ) {
        if (!self::isAuthenticated()) {
            return self::unauthorized();
        }

        $em = $this->getEntityManager();
        $repoCG = $em->getRepository(CriterionGroup::class);
        /**
         * @var CriterionGroup
         */
        $criterionGroup = $repoCG->find($id);

        if (!$criterionGroup) {
            return self::fieldDoesNotExist('Criterion group', $id);
        }
        if (!$criterionGroup->hasNoCriterion()) {
            return self::badRequest();
        }

        $em->remove($criterionGroup);
        $em->flush();

        return new Response(null, 204);
    }


    /**
     * @param Application $app
     * @return RedirectResponse
     * @Route("/organization/settings/criteriongroups/", name="manageCriterionGroups")
     */
    public function criterionGroupsListAction(Application $app)
    {
        $currentUser = $this->getAuthorizedUser();
        if (!$currentUser) {
            return $this->redirectToRoute('login');
        }

        /**
         * @var Twig_Environment
         */
        $twig = $app['twig'];
        $userOrg = $currentUser->getOrganization();
        $criterionGroups = $userOrg->getCriterionGroups();


        /** @var OrganizationRepository */
        $repoO = $this->getEntityManager()->getRepository(Organization::class);
        $repoI = $this->getEntityManager()->getRepository(Icon::class);
        /** @var Icon[] */
        $icons = $repoI->findAll();
        $usedCriterionNames = $repoO->findUsedCriterionNames($userOrg);

        /**
         * @var FormFactory
         */
        $formFactory = $app['form.factory'];

        $form = $formFactory->create(
            ManageCriteriaForm::class,
            $userOrg
        );

        $criterionNameType = $formFactory->create(
            CriterionNameType::class,
            null,
            [ 'organization' => $userOrg ]
        );


        return $twig->render('criteriongroups_list.html.twig', [
            'criterionGroups' => $criterionGroups,
            'form' => $form->createView(),
            'criterionNameType' => $criterionNameType->createView(),
            'used_criterion_names' => $usedCriterionNames,
            'icons' => $icons
        ]);
    }




    private static function badRequest()
    {
        return new Response(null, 400);
    }

    private static function unauthorized()
    {
        return new Response(null, 401);
    }

    private static function fieldDoesNotExist(string $field, $id)
    {
        return new Response(
            "$field with id=$id does not exist", 404
        );
    }

    private static function isAuthenticated()
    {
        $currentUser = self::getAuthorizedUser();
        return $currentUser instanceof User;
    }
}
