<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/21
 * Time: 8:46
 */

namespace app\yuyue\model;

use think\Db;
use app\yuyue\validate\Goods as GoodsValidate;
use think\Loader;

class GoodsModel extends LogModel
{
    /**
     * 获取平台货号组
     * @param $code string 平台货号
     * @return array
     */
    public static function searchGoodsDetail($key = '')
    {
        $where['name']         = ['LIKE', '%' . $key . '%'];
        $where['bar_code']     = ['LIKE', '%' . $key . '%'];
        $where['product_code'] = ['LIKE', '%' . $key . '%'];

//        $where['status']       = 1;

        return Db::table('commodity_view')->whereOr($where)->limit(10)->column('id,name,product_code,bar_code,model,unit,supply_price,selling_price,currency');
    }

    /**
     * 获取单个商品信息
     * @param array $where 条件
     * @param bool $field 字段限制
     * @return array|false|\PDOStatement|string|Model
     */
    public static function getGoodsOne($where = [], $field = true)
    {
        return Db::name('goods')->where($where)->field($field)->find();
    }

    /**
     * 获取商品列表，支持分页函数
     * @param array $where
     * @return object
     */
    public static function getGoodsList($where = [])
    {
        $where = ['status' => ['NEQ', '99']];

        return Db::name('goods')
            ->where($where)
            ->order(['create_time' => 'DESC',])
            ->paginate(10);
    }

    /**
     * 新增商品接口
     * @param $data array 商品数据包
     * @return array|string
     */
    public static function addGoods($data = [])
    {
        $validate = Loader::validate('Goods', 'validate', false, 'yuyue');
        if ($validate->scene('goods_save')->check($data)) {
//            $result = Db::name('goods')->where(['bar_code' => $data['bar_code']])->find();
//            if ($result)
//                return '该条形码已存在于其它商品';

            $row['name']        = trim($data['name']);             //名称
            $row['category_id'] = $data['category_id'];               //分类id
//            $row['bar_code']         = trim($data['bar_code']);         //条码
            $row['product_code']  = $data['product_code'];           //货号
            $row['selling_price'] = trim($data['selling_price']);    //售卖价格
            $row['market_price']  = trim($data['market_price']);     //市场价格
            $row['supply_price']  = trim($data['supply_price']);     //成本价格
            $row['stock']         = trim($data['stock']);            //库存
//            $row['stock_alert']      = trim($data['stock_alert']);      //库存警告

            $row['status']  = $data['status'];                  //状态
            $row['img_url'] = $data['img_url'];                 //封面
//            $row['model']            = $data['model'];                  //规格
//            $row['unit']             = $data['unit'];                   //单位
//            $row['currency']         = '';                              //币种
//            $row['brokerage_type']   = $data['brokerage_type'];         //反现种类
//            $row['brokerage_number'] = $data['brokerage_number'];       //反现佣金

            $row['remark']  = $data['remark'];      //简介
            $row['content'] = self::getPostContentAttr($data);     //详情

            $row['create_time'] = time();

            $id = Db::name('goods')->insertGetId($row);

            //记录操作日志
            LogModel::addLog(2, $id, '新增商品信息');

            return '';
        } else return $validate->getError();
    }

    /**
     * 商品编辑接口
     * @param $data array 商品数据包
     * @return array|string
     */
    public static function saveGoods($data = [])
    {
        $validate = Loader::validate('Goods', 'validate', false, 'yuyue');
        if ($validate->scene('goods_save')->check($data)) {
//            $result = Db::name('goods')->where(['bar_code' => $data['bar_code']])->find();
//            if ($result)
//                return '该条形码已存在于其它商品';

            $row['name']        = trim($data['name']);             //名称
            $row['category_id'] = $data['category_id'];               //分类id
//            $row['bar_code']         = trim($data['bar_code']);         //条码
//            $row['product_code']  = $data['product_code'];           //货号
            $row['selling_price'] = trim($data['selling_price']);    //售卖价格
            $row['market_price']  = trim($data['market_price']);     //市场价格
            $row['supply_price']  = trim($data['supply_price']);     //成本价格
            $row['stock']         = trim($data['stock']);            //库存
//            $row['stock_alert']      = trim($data['stock_alert']);      //库存警告

            $row['status']  = $data['status'];                  //状态
            $row['img_url'] = $data['img_url'];                 //封面
//            $row['model']            = $data['model'];                  //规格
//            $row['unit']             = $data['unit'];                   //单位
//            $row['currency']         = '';                              //币种
//            $row['brokerage_type']   = $data['brokerage_type'];         //反现种类
//            $row['brokerage_number'] = $data['brokerage_number'];       //反现佣金

            $row['remark']  = $data['remark'];      //简介
            $row['content'] = self::getPostContentAttr($data);     //详情

            $result = Db::name('goods')->where(['id' => $data['id']])->update($row);

            //记录操作日志
            $result && LogModel::addLog(2, $data['id'], '编辑商品信息');

            return '';
        } else return $validate->getError();
    }

    /**
     * 商品删除
     * 限制只能删除待审核商品
     * @param int $id
     * @param int $status
     */
    public static function delGoods($id = 0, $status = 0)
    {
        $where = ['id' => $id, 'status' => $status];

        $goods  = self::getGoodsOne($where);
        $result = self::changeGoods([$id], 99, $goods['status']);

        //记录操作日志
        $result && LogModel::addLog(2, $id, '删除商品');

        return $result;
    }

    /**
     * 商品状态修改
     * @param array $id 可批量
     * @param int $status 商品状态 0=>待审核 1=>上架 10=>下架
     * @return int|string
     */
    public static function changeGoods($ids = [], $status = 10, $old_status = 10)
    {
        $ids    = is_array($ids) ? $ids : [$ids];
        $result = Db::name('goods')->where(['id' => ['IN', $ids], 'status' => $old_status])->update(['status' => $status]);

        //记录操作日志
        $status_text = [0 => '下架', 1 => '上架', 99 => '软删除'];
        if ($result)
            foreach ($ids as $id) {
                LogModel::addLog(2, $id, $status_text[$old_status] . '商品');
            }

        return $result;
    }

    /**
     * ----------------------------
     * 获取商品分类组
     * @param int $pid 上级分类id
     * @return false|\PDOStatement|string|\think\Collection
     * ----------------------------
     */
    public static function getCategoryList($where = [])
    {
        return Db::name('goods_category')->where($where)->column('*', 'id');
    }

    public static function getCategoryOne($id = 0)
    {
        return Db::name('goods_category')->where(['id' => $id])->find();
    }

    public static function saveCategory($data)
    {
        $validate = Loader::validate('Goods', 'validate', false, 'yuyue');
        if ($validate->scene('goods_category')->check($data)) {
            $data['name']   = trim($data['name']);
            $data['remark'] = trim($data['remark']);
//            $row['pid']  = $data['pid'];
//
            if (isset($data['id']) && is_numeric($data['id'])) {
                Db::name('goods_category')->where(['id' => $data['id']])->update($data);
                LogModel::addLog(3, $data['id'], '编辑商品分类');
            } else {
                $data['create_time'] = time();

                $id = Db::name('goods_category')->insertGetId($data);
                LogModel::addLog(3, $id, '新增商品分类');
            }
//
//            //层次，结构概念方便搜索用
//            $parent = self::getCategoryOne($data['pid']);
//            $layer  = ($parent ? $parent['layer'] : '') . $id . ',';
//            Db::name('goods_category')->where(['id' => $id])->update(['layer' => $layer]);

            return '';
        } else return $validate->getError();
    }

    /**
     * 商品分类删除，单条删除
     * 循环删除当前分类下级分类，并循环到删除再下级
     * @param $id
     */
    public static function delCategory($id = 0)
    {
//        $result = Db::name('goods_category')->where(['pid' => $id])->find();
//        if ($result)
//            return '商品分类存在子级分类';

        $result = Db::name('goods')->where(['category_id' => $id])->find();
        if ($result)
            return '商品分类存在商品';

        Db::name('goods_category')->where(['id' => $id])->delete();
        LogModel::addLog(3, $id, '删除商品分类');

        return '';
    }
}