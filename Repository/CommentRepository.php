<?php
/**
 * Created by PhpStorm.
 * User: SpeakWithAGeek
 * Date: 6/21/2017
 * Time: 5:48 PM
 */

namespace Repository;

class CommentRepository extends AbstractRepository
{
    private $tableName = 'comments';

    /**
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $id = (int) $id;
        $item = $this->getById($id);
        $result = false;
        if (!empty($item)) {
            $sql = "
                DELETE FROM {$this->tableName}
                WHERE
                  leftId >= :leftId 
                  AND rightId <= :rightId
            ";
            $queryBuilder = $this->getQueryBuilder($sql);
            $queryBuilder->bindParam(':leftId', $item['leftId'], \PDO::PARAM_INT);
            $queryBuilder->bindParam(':rightId', $item['rightId'], \PDO::PARAM_INT);

            $result = $queryBuilder->execute();

            $delta = -1 * ($item['rightId'] - $item['leftId'] + 1);

            $this->updateParentItemNodes($item['leftId'], $item['rightId'], $delta);
            $this->updateAfterParentItemNodes($item['rightId'], $delta);

        }

        return $result;
    }

    /**
     * @param $content
     * @param null $parentId
     * @return string
     */
    public function add($content, $parentId = null)
    {
        $maxRightId = 0;
        $level = 1;

        if ($parentId !== null) {
            $parent = $this->getById($parentId);
            if (!empty($parent)) {
                $maxRightId = (int)$parent['rightId'];
                $level = $parent['level'] + 1;
                $parentId = $parent['id'];
            }
        }
        $maxRightId = ($maxRightId) ? $maxRightId : $this->getMaxRightId() + 1;

        $this->updateAfterParentItemNodes($maxRightId, 2);
        $this->updateParentItemNodes($maxRightId, $maxRightId, 2);

        $sql = "
            INSERT 
            INTO {$this->tableName} 
              (content, leftId, rightId, `level`, parentId)
            VALUES 
              (:content, :leftId, :rightId, :level, :parentId)
        ";

        $leftId = $maxRightId;
        $rightId = $leftId + 1;
        $builder = $this->getQueryBuilder($sql);
        $builder->bindParam(':content', $content, \PDO::PARAM_STR);
        $builder->bindParam(':leftId', $leftId, \PDO::PARAM_INT);
        $builder->bindParam(':rightId', $rightId, \PDO::PARAM_INT);
        $builder->bindParam(':level', $level, \PDO::PARAM_INT);
        $builder->bindParam(':parentId', $parentId, \PDO::PARAM_INT);

        $builder->execute();

        return $this->pdo->lastInsertId();
    }

    /**
     * @param $parentId
     * @return array
     */
    public function getAll($parentId)
    {
        $result = [];
        $parent = $this->getById($parentId);
        if (!empty($parent)) {
            $sql = "
                SELECT * 
                FROM {$this->tableName}
                WHERE 
                  leftId >= :leftId
                  AND rightId <= :rightId
                  AND id <> :id
                ORDER BY leftId asc;
            ";
            $builder = $this->getQueryBuilder($sql);
            $builder->bindParam(':leftId', $parent['leftId'], \PDO::PARAM_INT);
            $builder->bindParam(':rightId', $parent['rightId'], \PDO::PARAM_INT);
            $builder->bindParam(':id', $parentId, \PDO::PARAM_INT);
            $builder->execute();

            $result = $builder->fetchAll();
        }

        return $result;
    }

    /**
     * @param $level
     * @return array
     */
    public function getLevelItems($level)
    {
        $sql = "
            SELECT * 
            FROM {$this->tableName}
            WHERE `level` = :itemLevel
            ORDER BY leftId asc;
        ";

        $builder = $this->getQueryBuilder($sql);
        $builder->bindParam(':itemLevel', $level, \PDO::PARAM_INT);
        $builder->execute();
        return $builder->fetchAll();
    }

    /**
     * @param $minLeftId
     * @param $delta
     * @return bool
     * @internal param $rightId
     */
    private function updateAfterParentItemNodes($minLeftId, $delta)
    {
        $delta = (int) $delta;
        $deltaAbs = abs($delta);
        $deltaStr = ($delta > 0) ? "+ $delta" : " - $deltaAbs";

        $sql = "
          UPDATE {$this->tableName} 
          SET 
            leftId = leftId $deltaStr, 
            rightId = rightId $deltaStr 
          WHERE 
            leftId > :minLeftId        
        ";

        $builder = $this->getQueryBuilder($sql);
        $builder->bindParam(':minLeftId', $minLeftId, \PDO::PARAM_INT);
        return $builder->execute();
    }

    /**
     * @param $maxLeftId
     * @param $minRightId
     * @param $delta
     * @return bool
     */
    private function updateParentItemNodes($maxLeftId, $minRightId, $delta)
    {
        $delta = (int) $delta;
        $deltaAbs = abs($delta);
        $deltaStr = ($delta > 0) ? "+ $delta" : " - $deltaAbs";

        $sql = "
          UPDATE {$this->tableName} 
          SET 
            rightId = rightId $deltaStr 
          WHERE 
            rightId >= :minRightId 
            AND leftId < :maxLeftId                  
        ";

        $builder = $this->getQueryBuilder($sql);
        $builder->bindParam(':minRightId', $minRightId, \PDO::PARAM_INT);
        $builder->bindParam(':maxLeftId', $maxLeftId, \PDO::PARAM_INT);

        return $builder->execute();
    }

    /**
     * @param $id
     * @return bool
     */
    public function getById($id)
    {
        $id = (int) $id;

        $sql = "
            SELECT * 
            FROM {$this->tableName}
            WHERE id = :id
        ";

        $queryBuilder = $this->getQueryBuilder($sql);
        $queryBuilder->bindParam(':id', $id, \PDO::PARAM_INT);
        $queryBuilder->execute();
        return $queryBuilder->fetch();
    }

    /**
     * @return int
     */
    private function getMaxRightId()
    {
        $sql = "
          SELECT MAX(rightId) 
          FROM {$this->tableName}
        ";

        $builder = $this->getQueryBuilder($sql);
        $builder->execute();
        return $builder->fetchColumn();
    }
}