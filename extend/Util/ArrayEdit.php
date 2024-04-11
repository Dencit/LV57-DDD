<?php

namespace Extend\Util;

/**
 * notes: 数组编辑工具
 * @author 陈鸿扬 | @date 2021/3/12 18:14
 */
class ArrayEdit
{

    //统计用
    protected $itemFiled = null;//统计模板字段集

    //设置模板字段集
    public function initItemField($fieldArr)
    {
        $this->itemFiled = $fieldArr;
        return $this->itemFiled;
    }

    /*
     * notes: 把另一个列数据,通过$relationId,关联到准备提交的列表数据, 相应地添加数据 或 对重复数据进行更新
     * @author 陈鸿扬 | @date 2021/2/24 14:12
     */
    public function combineList(&$beAddList, $getList, $relationId, $updateKeyArr = null)
    {
        //获取准备添加的数据列表 $addIds
        $addIds     = array_column($beAddList, "$relationId");
        $newAddData = [];
        //检查待添加数据是否重复
        if (!empty($getList)) {
            foreach ($getList as $ind => $item) {
                $searchIndex = array_search($item["$relationId"], $addIds);
                //重复则更新
                if ($searchIndex !== false) {
                    //往当前行数据-更新指定内容
                    $addIndex                = $beAddList[$searchIndex];
                    $itemData                = $this->itemData($addIndex, $relationId, $item, $updateKeyArr);
                    $beAddList[$searchIndex] = $itemData;
                } //不重复则添加
                else {
                    //新增行数据-添加指定内容
                    $addIndex     = [];
                    $itemData     = $this->itemData($addIndex, $relationId, $item, $updateKeyArr);
                    $newAddData[] = $itemData;
                }
            }
            //合并补充数据
            $beAddList = array_merge($beAddList, $newAddData);
            return $beAddList;
        }
    }

    /*
     * notes: 指定更新内容,用于单列数据更新
     * @author 陈鸿扬 | @date 2021/2/24 14:12
     */
    protected function itemData($addIndex, $relationId, $item, $updateKeyArr)
    {
        //填充当前数据行没有的字段
        if (!empty($this->itemFiled)) {
            $temple   = $this->itemFiled;
            $addIndex = array_merge($temple, $addIndex);//$addIndex 覆盖 $temple
        }
        //同步当前数据行
        $itemData = $addIndex;
        //按设置字段名更新
        if (!empty($updateKeyArr)) {
            $itemData["$relationId"] = $item["$relationId"];
            foreach ($updateKeyArr as $k) {
                $itemData["$k"] = $item["$k"];
            }
        } //即使没有设置字段名,也要更新关联id
        else {
            $itemData["$relationId"] = $item["$relationId"];
        }
        return $itemData;
    }

    //合计查询结果中的字段
    public function fieldCollectSum($tempData, array $sumFields)
    {
        $tempMeta = [];
        if (count($tempData) > 0) {
            foreach ($sumFields as $key => $option) {
                $valueArr = array_column($tempData, $key);
                switch ($option) {
                    default: //相加
                        $tempMeta[$key] = array_sum($valueArr);
                        break;
                    case 'per' : //相加+均除
                        $countValue     = count($valueArr);
                        $tempMeta[$key] = floatval(bcdiv(array_sum($valueArr), $countValue, 2));
                        break;
                }
            }
        }
        return $tempMeta;
    }

}