<?php
/**
 * \php\ORM\Entities\User.php
 *
 * @package     HomeAI
 * @subpackage  Entities
 * @category    ORM
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
namespace HomeAI\ORM\Entities;

use Doctrine\ORM\Mapping as ORM;
use HomeAI\ORM\Base;

/**
 * User entity class.
 *
 * @package     HomeAI
 * @subpackage  Entities
 * @category    ORM
 *
 * @method  integer     getId()
 * @method  string      getUsername()
 * @method  string      getFirstname()
 * @method  string      getSurname()
 * @method  string      getEmail()
 * @method  string      getPassword()
 * @method  \DateTime   getCreated()
 * @method  \DateTime   getModified()
 * @method  boolean     getStatus()
 * @method  User        setUsername($username)
 * @method  User        setFirstname($firstname)
 * @method  User        setSurname($surname)
 * @method  User        setEmail($email)
 * @method  User        setPassword($password)
 * @method  User        setCreated(\DateTime $created)
 * @method  User        setModified(\DateTime $modified)
 * @method  User        setStatus($status)
 *
 * @ORM\Table(name="User")
 * @ORM\Entity
 */
class User extends Base
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Username", type="string", length=255, precision=0, scale=0, nullable=false, unique=true)
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(name="Firstname", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    protected $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="Surname", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    protected $surname;

    /**
     * @var string
     *
     * @ORM\Column(name="Email", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="Password", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    protected $password;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Created", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Modified", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    protected $modified;

    /**
     * @var boolean
     *
     * @ORM\Column(name="Status", type="boolean", precision=0, scale=0, nullable=false, unique=false)
     */
    protected $status;
}
