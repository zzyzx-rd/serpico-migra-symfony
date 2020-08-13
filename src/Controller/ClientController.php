<?php

namespace App\Controller;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Model\Client;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

final class ClientController extends MasterController
{
    private const CLIENT_ICON_DIR = 'lib/img/org/';
    /** @var \Symfony\Component\Asset\Packages */
    private static $assetsPackages;

    public function __construct()
    {
        global $app;
        self::$assetsPackages = $app['assets.packages'];
    }

    /**
     * @param Request $req
     * @param int $clientId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/client/{clientId}/icon", name="setClientIcon")
     */
    public function setClientIcon(Request $req, int $clientId)
    {
        $em = self::getEntityManager();
        $clientRepo = $em->getRepository(Client::class);
        /** @var Client|null */
        $client = $clientRepo->find($clientId);

        if (!$client) {
            throw new NotFoundHttpException;
        }

        /** @var UploadedFile */
        $file = $req->files->get('file');
        // path in server
        $path = $file->getPathname();

        $originalName = $file->getClientOriginalName();
        $ext          = pathinfo($originalName, PATHINFO_EXTENSION);

        // name the file after the client's id
        $filename = "$clientId.$ext";
        $resource = self::CLIENT_ICON_DIR . $filename;
        $success = move_uploaded_file(
            $path,
            PROJECT_ROOT . "web/$resource"
        );

        if (!$success) {
            throw new \Exception('could not save file in server');
        }

        $client->setLogo($filename);
        $em->persist($client);
        $em->flush();

        return new JsonResponse([
            'url' => self::$assetsPackages->getUrl("/$resource"),
        ]);
    }
}
