<?php
Yii::import('application.modules.reviews.components.parsers.*');
Yii::import('application.modules.reviews.models.*');

class UpdateReviewsCommand extends CConsoleCommand
{
    public function actionIndex($limit = 500, $forceRun = false)
    {
        $offset = Yii::app()->getGlobalState(__FILE__ . 'reviewsOffset', 0);
        $runAt = Yii::app()->getGlobalState(__FILE__ . 'runAt', 0);

        if (!$forceRun && $runAt && $runAt > ( time() - 1 * 60 * 60 ) ) {
            return 0;
        }

        Yii::app()->setGlobalState(__FILE__ . 'runAt', time());

        $sql = 'SELECT * FROM {{reviewsRelations}}';
        $db = Yii::app()->getDb();
        $comm = $db->createCommand($sql);
        $rows = $comm->query();

        $data = array();
        foreach ($rows AS $row) {
            $data[$row['cId']][] = $row;
        }

        $comm = $db->createCommand();
        $comm->select = 'COUNT(*) AS count';
        $comm->from = '{{torrentGroups}}';
        $comm->where = 'cId IN(' . implode(', ', array_keys($data)) . ')';
        $count = $comm->queryScalar();

        $comm = $db->createCommand();
        $comm->select = '*';
        $comm->from = '{{torrentGroups}}';
        $comm->where = 'cId IN(' . implode(', ', array_keys($data)) . ')';
        $comm->order = 'mtime DESC';
        $comm->limit($limit, $offset);

        $rows = $comm->query();

        $className = (new modules\torrents\models\TorrentGroup())->resolveClassName();

        foreach ($rows AS $row) {
            $reviewsData = $data[$row['cId']];

            foreach ($reviewsData AS $review) {
                /**
                 * @var ReviewRelation $ReviewRelation
                 */
                $ReviewRelation = EActiveRecord::model('ReviewRelation')->populateRecord($review);

                $params = $ReviewRelation->getParams();

                /**
                 * @var $class ReviewInterface
                 */
                $class = new $ReviewRelation->apiName;

                $attrs = $params;

                $eavComm = $db->createCommand();
                $eavComm->select = 'attribute, value';
                $eavComm->from = '{{torrentGroupsEAV}}';
                $eavComm->where(['in', 'attribute', $params]);
                $eavComm->andWhere('entity = :entity', [':entity' => $row['id']]);

                foreach ($eavComm->query() AS $eavRow) {
                    if (($key = array_search($eavRow['attribute'], $attrs)) !== false) {
                        $attrs[$key] = $eavRow['value'];
                    }
                }

                $class->getReviewData($attrs, $className, $row['id']);
            }
        }

        $newOffset = $offset + $limit;

        if ($newOffset > $count) {
            $newOffset = 0;
        }

        Yii::app()->setGlobalState(__FILE__ . 'reviewsOffset', $newOffset);
        Yii::app()->clearGlobalState(__FILE__ . 'runAt');

        return 0;
    }
}