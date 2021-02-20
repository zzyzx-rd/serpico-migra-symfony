<?php
/**
 * User: Faventyne
 * Date: 04/12/2017
 * Time: 23:29
 */

namespace App\Entity;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

//use Classes\Exceptions\InvalidSqlQueryException;
/**
 * @ORM\MappedSuperclass
 */
abstract class DbObject
{

    /**
     * 
     * @Id()
     * @GeneratedValue()
     * @Column(name="id", type="integer", nullable=true) @GeneratedValue
     */
    protected ?int $id;

    /**
     * @Column(name="initiator", type="integer", nullable=true)
     * @var User
     */
    protected ?User $initiator;

    /**
     * @Column(name="inserted", type="datetime" , options={"default": "CURRENT_TIMESTAMP"})
     * @var DateTime
     */
    protected DateTime $inserted;

    public function __construct($id = 0, $initiator = null, $inserted = null)
    {
        //parent::__construct($requestStack, $security, $currentUser, $em);
        $this->id = $id;
        $this->initiator = $initiator;
        $this->inserted = $inserted ?: new DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getInitiator()
    {
        return $this->initiator;
    }

    /**
     * @param User $initiator
     * @return DbObject
     */
    public function setInitiator(?User $initiator)
    {
        $this->initiator = $initiator;
        return $this;
    }

    public function getInserted()
    {
        return $this->inserted;
    }

}
