<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/6
 * Time: 15:17
 */

namespace app\yuyue\model;

use think\Db;
use think\Loader;

class DepositModel extends LogModel
{
    public static function getDepositOne($id)
    {
        return Db::name('deposit')->where(['id' => $id])->find();
    }

    public static function getDepositList($where = [])
    {
        return Db::name('deposit')->where($where)->paginate(10);
    }

    public static function saveDeposit($data = [])
    {
        $validate = Loader::validate('Deposit', 'validate', false, 'yuyue');
        if ($validate->scene('deposit_save')->check($data)) {
//            $result = Db::name('goods')->where(['bar_code' => $data['bar_code']])->find();
//            if ($result)
//                return '该条形码已存在于其它商品';

            $row['name'] = trim($data['name']);             //名称

            $row['price']      = trim($data['price']);    //预约价格
            $row['unit_price'] = trim($data['unit_price']);     //额外单价

            $row['status']   = $data['status'];                  //状态
            $row['sequence'] = $data['sequence'];                  //状态
            $row['img_url']  = $data['img_url'];                 //封面

            $row['remark']  = $data['remark'];      //简介
            $row['content'] = self::getPostContentAttr($data);     //详情


            if (isset($data['id']) && is_numeric($data['id'])) {
                Db::name('deposit')->where(['id' => $data['id']])->update($row);
                LogModel::addLog(4, $data['id'], '编辑预约对象');
            } else {
                $row['create_time'] = time();
                $row['code']        = $data['code'];           //货号
                $id                 = Db::name('deposit')->insertGetId($row);
                //记录操作日志
                LogModel::addLog(4, $id, '新增预约对象');
            }

            return '';
        } else return $validate->getError();
    }

    public static function delDeposit($id){
//        $result = Db::name('goods')->where(['category_id' => $id])->find();
//        if ($result)
//            return '商品分类存在商品';

        Db::name('deposit')->where(['id' => $id])->delete();
        LogModel::addLog(4, $id, '删除预约对象');

        return '';
    }
}