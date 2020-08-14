<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 07/02/2018
 * Time: 17:28
 */

namespace App\Form\DataTransformer;

use App\Entity\Position;
use App\Entity\User;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class UserToPositionTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Transforms a User to a Position.
     *
     * @param  User|null $user
     * @return Position;
     */

    public function transform($user)
    {
        if (null === $user) {
            return null;
        }

        return $user->getPosition();

    }

    /**
     * Transforms a Position (number) to an User (issue).
     *
     * @param Position $position
     * @return void
     */
    public function reverseTransform($position)
    {
        // no issue number? It's optional, so that's ok
        if (!$position) {
            return;
        }
        // query for the user with this position
        $result = $this->em->getRepository(User::class)->find($position);

            





        if (null === $issue) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'An issue with number "%s" does not exist!',
                $issueNumber
            ));
        }

        return $issue;
    }

}