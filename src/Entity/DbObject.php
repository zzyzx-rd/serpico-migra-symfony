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
     * @Column(name="created_by", type="integer", nullable=true)
     * @var int
     */
    protected ?int $createdBy;

    /**
     * @Column(name="inserted", type="datetime")
     * @var DateTime
     */
    protected DateTime $inserted;

    public function __construct($id = 0, $createdBy = null, $inserted = null)
    {
        //parent::__construct($requestStack, $security, $currentUser, $em);
        $this->id = $id;
        $this->createdBy = $createdBy;
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
     * @return int
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param int $createdBy
     * @return DbObject
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    public function getInserted()
    {
        return $this->inserted;
    }

}