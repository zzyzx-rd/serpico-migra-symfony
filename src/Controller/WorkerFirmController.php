<?php

namespace App\Controller;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use App\Entity\Client;
use App\Entity\WorkerFirm;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

final class WorkerFirmController extends MasterController
{
    private const CLIENT_ICON_DIR = 'lib/img/wf/';

    /**
     * @param Request $req
     * @param int $clientId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/worker-firm/{wfiId}/icon", name="setWorkerFirmIcon")
     */
    public function setWorkerFirmIcon(Request $req, int $wfiId)
    {
        $em = $this->em;
        $workerFirmRepo = $em->getRepository(WorkerFirm::class);
        /** @var Client|null */
        $workerFirm = $workerFirmRepo->find($wfiId);

        if (!$workerFirm) {
            throw new NotFoundHttpException;
        }

        /** @var UploadedFile */
        $file = $req->files->get('file');

        // path in server
        $path = $file->getPathname();

        
        $originalName = $file->getClientOriginalName();
        $ext          = pathinfo($originalName, PATHINFO_EXTENSION);

        // name the file after the client's id
        $filename = "$wfiId.$ext";
        $resource = self::CLIENT_ICON_DIR . $filename;

        //return new JsonResponse([$path, $filename, $resource],200);

        $success = move_uploaded_file(
            $path,
            dirname(dirname(__DIR__)) . "/public/$resource"
        );

        if (!$success) {
            throw new \Exception('could not save file in server');
        }

        $workerFirm->setLogo($filename);
        $em->persist($workerFirm);
        $em->flush();

        $package = new Package(new EmptyVersionStrategy());

        return new JsonResponse([
            'url' => $package->getUrl("/$resource"),
        ]);
    }
}
