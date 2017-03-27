<?php
namespace app\modules\v1\tools;

use yii\data\ArrayDataProvider;
/**
 * Description of ArrayDataCustomerPaginationProvider
 * Serializer时，不重置pagination定义（不重新统计数量，不做任何数据偏移）,
 *
 * @author LXY
 */
class ArrayDataCustomerPaginationProvider extends ArrayDataProvider{
    //put your code here
    /**
     * @inheritdoc
     */
    protected function prepareModels()
    {
        if (($models = $this->allModels) === null) {
            return [];
        }

        if (($sort = $this->getSort()) !== false) {
            $models = $this->sortModels($models, $sort);
        }

        /*if (($pagination = $this->getPagination()) !== false) {
            //$pagination->totalCount = $this->getTotalCount(); //不重新统计

            if ($pagination->getPageSize() > 0) {
                $models = array_slice($models, $pagination->getOffset(), $pagination->getLimit(), true);
            }
        }*/

        return $models;
    }
}
