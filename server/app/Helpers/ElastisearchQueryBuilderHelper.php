<?php

namespace App\Helpers;


class ElastisearchQueryBuilderHelper
{
    public static function getSortBy(string $sortBy, string $sortOrder): array
    {

        return [
            $sortBy => [
                'order' => $sortOrder,
                'unmapped_type' => 'keyword',
            ]
        ];
    }

    private static function getDateFormatted(\DateTime $date): string
    {
        return $date->format("Y-m-d");
    }

    public static function getRangeQuery(string $field, \DateTime $gte, \DateTime $lte): array
    {
        return [
            'range' => [
                $field => [
                    'gte' => self::getDateFormatted($gte),
                    'lte' => self::getDateFormatted($lte),
                ]
            ]
        ];
    }

    public static function getNestedPathQuery(string $path, array $query): array
    {
        return [
            'nested' => [
                'path' => $path,
                'query' => $query,
            ]
        ];
    }

    public static function getMustQuery(array $queries): array
    {
        return [
                'must' => $queries,
        ];
    }

    public static function getBooleanQuery(array $queries): array
    {
        return [
            'bool' => $queries
        ];
    }

    public static function getShouldQuery(array $queries): array
    {
        return [
            'bool' => [
                'should' => $queries,
            ]
        ];
    }

    public static function getMultiMatchQuery(string $query, array $fields): array
    {
        return [
            'multi_match' => [
                'query' => $query,
                'fields' => $fields,
            ]
        ];
    }

    public static function getTermsQuery(string $string, array $contents)
    {
        return [
            'terms' => [
                $string => $contents,
            ]
        ];
    }

    public static function getBoolQuery(string $boleanBool, array $toArray, int $minShouldMatch = 1): array
    {
        return [
            'bool' => [
                $boleanBool => $toArray,
                'minimum_should_match' => $minShouldMatch,
            ]
        ];
    }

}