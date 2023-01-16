<?php

namespace App\Filters;

use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PropertyInfo\Type;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

final class CustomerSearch extends AbstractFilter
{
    private $searchParameterName;

    public function __construct(ManagerRegistry $managerRegistry, LoggerInterface $logger = null, array $properties = null, NameConverterInterface $nameConverter = null, string $searchParameterName = 'search')
    {
        parent::__construct($managerRegistry, $logger, $properties, $nameConverter);
        $this->searchParameterName = $searchParameterName;
    }

    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
        // otherwise filter is applied to order and page as well
        if (null === $value || $property !== $this->searchParameterName) {
            return;
        }
        $parameterName = $queryNameGenerator->generateParameterName($property); // Generate a unique parameter name to avoid collisions with other filters

        $queryBuilder
            ->andWhere($this->getQueryString($queryBuilder, $parameterName))
            ->setParameter($parameterName, "%$value%");

    }

    private function getQueryString($queryBuilder, $parameterName)
    {
        $alias = $queryBuilder->getRootAliases()[0];

        //Build orWhere string
        $expr = $queryBuilder->expr()->orX();
        foreach ($this->getProperties() as $column => $setting) {
            $expr->add($queryBuilder->expr()->like("$alias.$column", ':' . $parameterName));
        }

        return $expr;

    }

    public function getDescription(string $resourceClass): array
    {

        if (!$this->properties) {
            return [];
        }

        $propertyString = implode(', ', array_keys($this->properties));
        return [
            $this->searchParameterName => [
                'property' => $propertyString,
                'type' => Type::BUILTIN_TYPE_STRING,
                'required' => false,
                'description' => "Search by fields: $propertyString",
                'openapi' => [
                    'description' => "Search by fields: $propertyString"
                ]
            ]
        ];
    }

}