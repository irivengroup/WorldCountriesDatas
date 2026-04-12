<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets\Infrastructure\Persistence;

use Iriven\WorldDatasets\Exception\RepositoryException;
use PDO;
use PDOException;
use Psr\Log\LoggerInterface;

final class SqliteStatementExecutor
{
    public function __construct(
        private readonly PDO $pdo,
        private readonly ?LoggerInterface $logger = null,
    ) {
    }

    public function count(string $sql): int
    {
        try {
            $stmt = $this->pdo->query($sql);

            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            $this->logger?->error('Failed to count countries.', ['exception' => $e]);
            throw new RepositoryException('Failed to count countries.', 0, $e);
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchAllRows(string $sql): array
    {
        try {
            $stmt = $this->pdo->query($sql);

            /** @var array<int, array<string, mixed>> $rows */
            $rows = $stmt->fetchAll();

            return $rows;
        } catch (PDOException $e) {
            $this->logger?->error('Failed to fetch rows.', ['exception' => $e, 'sql' => $sql]);
            throw new RepositoryException('Failed to fetch rows.', 0, $e);
        }
    }

    /**
     * @param array<string, mixed> $params
     * @param array<string, int> $paramTypes
     * @return array<int, array<string, mixed>>
     */
    public function fetchAllRowsPrepared(string $sql, array $params, array $paramTypes = []): array
    {
        try {
            $stmt = $this->pdo->prepare($sql);

            foreach ($params as $key => $value) {
                $type = $paramTypes[$key] ?? PDO::PARAM_STR;
                $stmt->bindValue($key, $value, $type);
            }

            $stmt->execute();

            /** @var array<int, array<string, mixed>> $rows */
            $rows = $stmt->fetchAll();

            return $rows;
        } catch (PDOException $e) {
            $this->logger?->error('Failed to fetch rows with prepared query.', [
                'exception' => $e,
                'sql' => $sql,
                'params' => $params,
            ]);
            throw new RepositoryException('Failed to fetch rows.', 0, $e);
        }
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>|null
     */
    public function fetchOneRowPrepared(string $sql, array $params): ?array
    {
        try {
            $stmt = $this->pdo->prepare($sql);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();
            $row = $stmt->fetch();

            return is_array($row) ? $row : null;
        } catch (PDOException $e) {
            $this->logger?->error('Failed to fetch row with prepared query.', [
                'exception' => $e,
                'sql' => $sql,
                'params' => $params,
            ]);
            throw new RepositoryException('Failed to fetch row.', 0, $e);
        }
    }

    /**
     * @return array<string, string>
     */
    public function queryMap(string $sql, string $key, string $value): array
    {
        $rows = $this->fetchAllRows($sql);
        $result = [];

        foreach ($rows as $row) {
            $result[(string) $row[$key]] = (string) $row[$value];
        }

        return $result;
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function queryGrouped(string $sql, string $groupKey, string $itemKey, string $itemValue): array
    {
        $rows = $this->fetchAllRows($sql);
        $result = [];

        foreach ($rows as $row) {
            $result[(string) $row[$groupKey]][(string) $row[$itemKey]] = (string) $row[$itemValue];
        }

        ksort($result);

        return $result;
    }
}
