<?php
declare(strict_types = 1);

namespace Zortje\MVC\Tests\Model\Fixture;

use Zortje\MVC\Model\Table\Entity\Entity;
use Zortje\MVC\Model\Table\Entity\EntityProperty;

/**
 * Class CarEntity
 *
 * @package Zortje\MVC\Tests\Model\Fixture
 */
class CarEntity extends Entity
{

    // @todo test setting a value for make/model that is larger than 64 (should throw error if not handled)
    // @todo test setting a negative integer for horsepower (should throw error if not handled)
    // @todo test setting doors to an invalid door configuration (should throw error if not handled) only 'TWO' and 'FOUR' should be allowed

    protected static $columns = [
        'make'       => ['type' => EntityProperty::STRING, 'length' => 64],
        'model'      => ['type' => EntityProperty::STRING, 'length' => 64],
        'horsepower' => ['type' => EntityProperty::INTEGER, 'signed' => false],
        'doors'      => ['type' => EntityProperty::ENUM, 'values' => ['TWO', 'FOUR']],
        'released'   => EntityProperty::DATE
    ];

    /**
     * CarEntity constructor.
     *
     * @param string    $make
     * @param string    $model
     * @param int       $horsePower
     * @param string    $doors
     * @param \DateTime $released
     */
    public function __construct(string $make, string $model, int $horsePower, string $doors, \DateTime $released)
    {
        parent::__construct(null, new \DateTime(), new \DateTime());

        $this->set('make', $make);
        $this->set('model', $model);
        $this->set('horsepower', $horsePower);
        $this->set('doors', $doors);
        $this->set('released', $released);
    }
}
