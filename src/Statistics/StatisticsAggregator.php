<?php

namespace App\Statistics;

use Doctrine\DBAL\Driver\Connection;

class StatisticsAggregator
{
    private Connection $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function countTotalHelpers(): int
    {
        return $this->db->query('SELECT COUNT(*) FROM helpers')->fetchColumn();
    }

    public function countTotalHelpersByDay(): array
    {
        return $this->db->query('
            SELECT TO_CHAR(created_at, \'YYYY-mm-dd HH24\') AS day, COUNT(*) as nb
            FROM helpers
            WHERE created_at > current_date - interval \'7\' day
            GROUP BY day
            ORDER BY day ASC
        ')->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function countTotalOwners(): int
    {
        return $this->db->query('SELECT COUNT(DISTINCT owner_uuid) FROM help_requests')->fetchColumn();
    }

    public function countTotalOwnersByDay(): array
    {
        return $this->db->query('
            SELECT TO_CHAR(created_at, \'YYYY-mm-dd HH24\') AS day, COUNT(DISTINCT owner_uuid) as nb
            FROM help_requests
            WHERE created_at > current_date - interval \'7\' day
            GROUP BY day
            ORDER BY day ASC
        ')->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function countHelpersByDepartment(): array
    {
        return $this->db->query('
            SELECT locality, COUNT(*) as nb 
            FROM helpers 
            GROUP BY locality 
            ORDER BY nb DESC
        ')->fetchAll();
    }

    public function countOwnersByDepartment(): array
    {
        return $this->db->query('
            SELECT locality, COUNT(DISTINCT owner_uuid) as nb 
            FROM help_requests r
            GROUP BY locality 
            ORDER BY nb DESC
        ')->fetchAll();
    }

    public function countTotalVolunteers(): int
    {
        return $this->db->query('SELECT COUNT(*) FROM volunteers')->fetchColumn();
    }
}
