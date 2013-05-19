<?php
/**
 * \php\ORM\Entities\Sensor.php
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
 * Sensor entity class.
 *
 * @package     HomeAI
 * @subpackage  Entities
 * @category    ORM
 *
 * @method  integer     getId()
 * @method  string      getName()
 * @method  string      getDescription()
 * @method  string      getIp()
 * @method  SensorType  getSensorType()
 * @method  Sensor      setName($name)
 * @method  Sensor      setDescription($description)
 * @method  Sensor      setIp($ip)
 * @method  Sensor      setSensorType(SensorType $sensorType)
 *
 * @ORM\Table(name="Sensor")
 * @ORM\Entity
 */
class Sensor extends Base
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
     * @ORM\Column(name="Name", type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="Description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="IP", type="string", length=255, nullable=true)
     */
    protected $ip;

    /**
     * @var SensorType
     *
     * @ORM\ManyToOne(targetEntity="SensorType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Sensor_Type_ID", referencedColumnName="ID")
     * })
     */
    protected $sensorType;
}
