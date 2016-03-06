<?php
declare(strict_types = 1);

namespace Zortje\MVC\Tests\Model\Fixture;

use Zortje\MVC\Model\Table\Entity\Entity;

/**
 * Class CarEntity
 *
 * @package Zortje\MVC\Tests\Model\Fixture
 */
class CarEntity extends Entity
{

    protected static $columns = [
        'make'       => 'string',
        'model'      => 'string',
        'horsepower' => 'integer',
        'released'   => 'date'
    ];

    /**
     * CarEntity constructor.
     *
     * @param string    $make
     * @param string    $model
     * @param int       $horsePower
     * @param \DateTime $released
     */
    public function __construct(string $make, string $model, int $horsePower, \DateTime $released)
    {
        parent::__construct(null, new \DateTime(), new \DateTime());

        $this->set('make', $make);
        $this->set('model', $model);
        $this->set('horsepower', $horsePower);
        $this->set('released', $released);
    }
}
