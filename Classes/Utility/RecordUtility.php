<?php
declare(strict_types=1);
namespace In2code\Migration\Utility;

use Doctrine\DBAL\DBALException;

/**
 * Class ObjectUtility
 */
class RecordUtility
{
    protected static $importTable = 'tx_migration_import_records';

    /**
     * @param int $origUid
     * @param string $tableName
     * @return int|null
     */
    public static function getLocalUid(int $origUid, string $tableName):? int
    {
        $queryBuilder = DatabaseUtility::getQueryBuilderForTable(self::$importTable);

        $row = $queryBuilder->select('*')
            ->from(self::$importTable)
            ->where(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->eq('tablename', $queryBuilder->createNamedParameter($tableName)),
                    $queryBuilder->expr()->eq('orig_uid', $origUid)
                )
            )->execute()->fetch();

        return $row['local_uid'] ?: null;
    }

    /**
     * @param int $localUid
     * @param int $origUid
     * @param string $tableName
     * @return bool
     */
    public static function insert(int $localUid, int $origUid, string $tableName): bool
    {
        $queryBuilder = DatabaseUtility::getQueryBuilderForTable(self::$importTable);
        if (!self::getLocalUid($origUid, $tableName)) {
            $queryBuilder->insert(self::$importTable)
                ->values([
                    'tstamp' => time(),
                    'crdate' => time(),
                    'orig_uid' => $origUid,
                    'local_uid' => $localUid,
                    'tablename' => $tableName
                ])->execute();
        }
        return true;
    }

    /**
     * @param int $origUid
     * @param string $tableName
     * @return bool
     */
    public static function update(int $origUid, string $tableName): bool
    {
        $queryBuilder = DatabaseUtility::getQueryBuilderForTable(self::$importTable);
        if (self::getLocalUid($origUid, $tableName)) {
            $queryBuilder->update(self::$importTable)
                ->set('tstamp',time())
                ->where(
                    $queryBuilder->expr()->andX(
                        $queryBuilder->expr()->eq('tablename', $queryBuilder->createNamedParameter($tableName)),
                        $queryBuilder->expr()->eq('orig_uid', $origUid)
                    )
                )->execute();
        }
        return true;
    }

    /**
     * @param int $origUid
     * @param string $tableName
     * @return bool
     */
    public static function recordExists(int $origUid, string $tableName): bool
    {
        $queryBuilder = DatabaseUtility::getQueryBuilderForTable($tableName);
        $queryBuilder->getRestrictions()->removeAll();
        $row = $queryBuilder->select('uid')
            ->from($tableName)
            ->where($queryBuilder->expr()->eq('uid', $origUid))
            ->execute()->fetch();

        return $row['uid'] ? true: false;
    }
}
