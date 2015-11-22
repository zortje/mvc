<?php

namespace Zortje\MVC\Model\Table;

use Zortje\MVC\Model\SQLCommand;
use Zortje\MVC\Model\Table\Entity\Entity;
use Zortje\MVC\Model\Table\Entity\EntityFactory;
use Zortje\MVC\Model\Table\Entity\Exception\EntityClassNonexistentException;
use Zortje\MVC\Model\Table\Entity\Exception\EntityClassNotDefinedException;
use Zortje\MVC\Model\Table\Entity\Exception\InvalidEntityPropertyException;
use Zortje\MVC\Model\Table\Exception\TableNameNotDefinedException;

/**
 * Class Table
 *
 * @package Zortje\MVC\Model\Table
 */
abstract class Table
{

    /**
     * @var \PDO Connection
     */
    protected $pdo;

    /**
     * @var string Table name
     */
    protected $tableName;

    /**
     * @var String Entity class
     */
    protected $entityClass;

    /**
     * @var SQLCommand SQL Command
     */
    protected $sqlCommand;

    /**
     * Get table name
     *
     * @return string Table name
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * Find all entities
     *
     * @return Entity[] Entities
     */
    public function findAll()
    {
        $stmt = $this->pdo->prepare($this->sqlCommand->selectFrom());
        $stmt->execute();

        return $this->createEntitiesFromStatement($stmt);
    }

    /**
     * Find all entities where key is equal to the given value
     *
     * @param $key
     * @param $value
     *
     * @throws InvalidEntityPropertyException If entity does not have that property
     *
     * @return Entity[] Entities
     */
    public function findBy($key, $value)
    {
        /**
         * Check if entity have the property
         */
        $reflector = new \ReflectionClass($this->entityClass);

        $entity = $reflector->newInstanceWithoutConstructor();

        if (!isset($entity::getColumns()[$key])) {
            throw new InvalidEntityPropertyException([$this->entityClass, $key]);
        }

        /**
         * Execute with key-value condition
         */
        $stmt = $this->pdo->prepare($this->sqlCommand->selectFromWhere($key));
        $stmt->execute([":$key" => $value]);

        return $this->createEntitiesFromStatement($stmt);
    }

    /**
     * Insert entity into dabase
     *
     * @param Entity $entity Entity
     *
     * @return int Inserted entity ID
     */
    public function insert(Entity $entity)
    {
        $stmt = $this->pdo->prepare($this->sqlCommand->insertInto());

        $now = new \DateTime();
        $now = $now->format('Y-m-d H:i:s');

        $array = array_merge($entity->toArray(false), [
            'modified' => $now,
            'created'  => $now
        ]);

        $stmt->execute($array);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(Entity $entity)
    {
        // @todo Implement
    }

    public function delete(Entity $entity)
    {
        // @todo Implement
    }

    /**
     * Creates an array of Entity objects from statement
     *
     * @param \PDOStatement $statement
     *
     * @return Entity[] Entities from statement
     */
    protected function createEntitiesFromStatement(\PDOStatement $statement)
    {
        $entities = [];

        $entityFactory = new EntityFactory($this->entityClass);

        foreach ($statement as $row) {
            $entities[] = $entityFactory->createFromArray($row);
        }

        return $entities;
    }

    /**
     * Create SQLCommand for this Table with provided Entity
     *
     * @return SQLCommand
     */
    protected function createCommand()
    {
        $reflector = new \ReflectionClass($this->entityClass);

        $entity = $reflector->newInstanceWithoutConstructor();

        $columns = $entity::getColumns();

        return new SQLCommand($this->tableName, $columns);
    }

    /**
     * @param \PDO $pdo
     *
     * @throws TableNameNotDefinedException If table name is not defined in subclass
     * @throws EntityClassNotDefinedException If entity class is not defined in subclass
     * @throws EntityClassNonexistentException If entity class is nonexistent
     */
    public function __construct(\PDO $pdo)
    {
        if ($this->tableName === null) {
            throw new TableNameNotDefinedException([get_class($this)]);
        }

        if ($this->entityClass === null) {
            throw new EntityClassNotDefinedException([get_class($this)]);
        } elseif (!class_exists($this->entityClass)) {
            throw new EntityClassNonexistentException([get_class($this), $this->entityClass]);
        }

        // @todo should check if `$this->entityClass` is subclass of Entity class

        $this->pdo = $pdo;

        $this->sqlCommand = $this->createCommand();
    }
}
