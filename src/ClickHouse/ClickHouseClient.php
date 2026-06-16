<?php

declare(strict_types=1);

namespace M10c\UnlockedAnalyticsBundle\ClickHouse;

use ClickHouseDB\Client;

/** Minimal, self-contained ClickHouse client for the analytics ClickHouse storage backend. */
final class ClickHouseClient
{
    private readonly Client $client;
    private readonly string $database;

    public function __construct(string $dsn)
    {
        $parts = parse_url($dsn);
        if (false === $parts || !isset($parts['host'])) {
            throw new \InvalidArgumentException(\sprintf('Invalid ClickHouse DSN: %s', $dsn));
        }

        $database = ltrim($parts['path'] ?? '', '/');
        if ('' === $database) {
            throw new \InvalidArgumentException(\sprintf('ClickHouse DSN must include a /database: %s', $dsn));
        }

        $this->client = new Client([
            'host' => $parts['host'],
            'port' => $parts['port'] ?? (('https' === ($parts['scheme'] ?? 'http')) ? 8443 : 8123),
            'username' => isset($parts['user']) ? rawurldecode($parts['user']) : 'default',
            'password' => isset($parts['pass']) ? rawurldecode($parts['pass']) : '',
        ]);
        $this->client->database($database);
        $this->database = $database;
    }

    public function database(): string
    {
        return $this->database;
    }

    /** Run a non-query statement against the configured database. */
    public function command(string $sql): void
    {
        $this->client->write($sql);
    }

    /**
     * Bulk-insert associative rows into a table.
     *
     * @param list<array<string, mixed>> $rows
     */
    public function insert(string $table, array $rows): void
    {
        if ([] === $rows) {
            return;
        }

        $this->client->insertAssocBulk($table, $rows);
    }
}
