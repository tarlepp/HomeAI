<?php
/**
 * \php\ORM\Entities\Session.php
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
 * Session entity class.
 *
 * @package     HomeAI
 * @subpackage  Entities
 * @category    ORM
 *
 * @method  integer     getId()
 * @method  string      getSession()
 * @method  string      getData()
 * @method  \DateTime   getExpire()
 * @method  \DateTime   getCreate()
 * @method  Session     setSession($session)
 * @method  Session     setData($data)
 * @method  Session     setExpire(\DateTime $expire)
 * @method  Session     setCreate(\DateTime $create)
 *
 * @ORM\Table(name="Session")
 * @ORM\Entity
 */
class Session extends Base
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Session", type="string", length=255, nullable=false)
     */
    protected $session;

    /**
     * @var string
     *
     * @ORM\Column(name="Data", type="text", nullable=false)
     */
    protected $data;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Expire", type="datetime", nullable=false)
     */
    protected $expire;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Create", type="datetime", nullable=false)
     */
    protected $create;
}
