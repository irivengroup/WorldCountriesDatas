<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets\Infrastructure\Persistence;
use Iriven\WorldDatasets\Application\WorldDatasets;


use Iriven\WorldDatasets\Exception\RepositoryException;
use PDO;
use PDOException;

final class SqliteConnectionFactory
{
    public function create(string $sqliteFilePath): PDO
    {
        if ($sqliteFilePath === '' || !is_file($sqliteFilePath)) {
            throw new RepositoryException(sprintf('SQLite file not found: %s', $sqliteFilePath));
        }

        try {
            $pdo = new PDO('sqlite:' . $sqliteFilePath);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            return $pdo;
        } catch (PDOException $e) {
            throw new RepositoryException('Unable to open SQLite database.', 0, $e);
        }
    }
}
