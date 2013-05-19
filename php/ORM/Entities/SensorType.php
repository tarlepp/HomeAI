<?php
/**
 * \php\ORM\Entities\SensorType.php
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
 * SensorType entity class.
 *
 * @package     HomeAI
 * @subpackage  Entities
 * @category    ORM
 *
 * @method  integer     getId()
 * @method  string      getName()
 * @method  string      getDescription()
 * @method  string      getUnit()
 * @method  boolean     getDecimal()
 * @method  SensorType  setName($name)
 * @method  SensorType  setDescription($description)
 * @method  SensorType  setUnit($unit)
 * @method  SensorType  setDecimal($decimal)
 *
 * @ORM\Table(name="Sensor_Type")
 * @ORM\Entity
 */
class SensorType extends Base
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
     * @ORM\Column(name="Unit", type="string", length=255, nullable=false)
     */
    protected $unit;

    /**
     * @var boolean
     *
     * @ORM\Column(name="Decimal", type="boolean", nullable=false)
     */
    protected $decimal;
}
