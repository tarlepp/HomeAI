<?php
/**
 * \php\ORM\Entities\SensorData.php
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
 * SensorData entity class.
 *
 * @package     HomeAI
 * @subpackage  Entities
 * @category    ORM
 *
 * @method  integer     getId()
 * @method  string      getValue()
 * @method  \DateTime   getStamp()
 * @method  Sensor      getSensor()
 * @method  SensorData  setValue($value)
 * @method  SensorData  setStamp(\DateTime $stamp)
 * @method  SensorData  setSensor(Sensor $sensor)
 *
 * @ORM\Table(name="Sensor_Data")
 * @ORM\Entity
 */
class SensorData extends Base
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
     * @var float
     *
     * @ORM\Column(name="Value", type="decimal", nullable=false)
     */
    protected $value;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Stamp", type="datetime", nullable=false)
     */
    protected $stamp;

    /**
     * @var Sensor
     *
     * @ORM\ManyToOne(targetEntity="Sensor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Sensor_ID", referencedColumnName="ID")
     * })
     */
    protected $sensor;
}
