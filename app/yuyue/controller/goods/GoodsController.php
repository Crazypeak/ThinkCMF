<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/2
 * Time: 17:44
 */

namespace app\yuyue\controller\goods;

use app\yuyue\controller\IndexController;
use app\yuyue\model\GoodsModel;

class GoodsController extends IndexController
{

    /**
     * 商品列表，支持搜索模块
     * @return mixed
     */
    public function goodsList()
    {
        //遍历查询条件
        $where = $this->allWhere('goods');
        unset($where['depot_id']);

        $GoodsModel = new GoodsModel();
        $goods_list = $GoodsModel->getGoodsList($where);
        $this->assign('goods_list', $goods_list);

        $class_list = $GoodsModel->getClassList();
//        $this->assign('class_list', list_to_tree($class_list));
        $this->assign('class_list', $class_list);

        return $this->fetch('goods/goods_list');
    }

//    /**
//     * 库存变动
//     * @param null $id 商品id
//     * @param int $amount 变动数量
//     * @param int $model 变动模式
//     */
//    public function goodsStock($id = null, $amount = 0)
//    {
//        $goods  = GoodsModel::getGoodsOne(['id' => $id, 'status' => 1]);
//        $result = $goods && Inventory::addInventory($goods['id'], 1, 1, $amount, '补增商品：待审核');
//        $result ? $this->success('操作成功') : $this->error('操作失败');
//    }

    /**
     * 商品上架
     * @param array $ids
     * @param int $status 0=>待审核(可删除) 1=>上架  10=>下架
     */
    public function goodsOn($ids = [])
    {
        $GoodsModel = new GoodsModel();
        $result     = $ids && $GoodsModel->changeGoods($ids, 1, 10);
        $result ? $this->success('操作成功') : $this->error('商品状态不正确');
    }

    /**
     * 商品下架
     * @param array $ids
     * @param int $status 0=>待审核(可删除) 1=>上架  10=>下架
     */
    public function goodsOff($ids = [])
    {
        $GoodsModel = new GoodsModel();
        $result     = $ids && $GoodsModel->changeGoods($ids, 10, 1);
        $result ? $this->success('操作成功') : $this->error('商品状态不正确');
    }

    /**
     * 新增商品
     * @return mixed
     */
    public function goodsAdd()
    {
        $GoodsModel = new GoodsModel();
        if ($data = input('post.')) {

            $data['product_code'] = config('PRODUCT_CODE_KEY') . date('YmdHis') . rand(1000, 9999);

            //获取商品封面图片
//            if ($file = request()->file('img')) {
//                $file_name       = $file->validate(['ext' => 'jpg,png'])->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . 'image', $data['product_code'])->getBasename();
//                $data['img_url'] = '/uploads/image/' . $file_name;
//            }else{
//                $data['img_url'] = '/favicon.ico';
//            }

            $result = $GoodsModel->addGoods($data);
            empty($result) ? $this->success('操作成功', url('goodsList')) : $this->error($result);
        } else {
            $class_list = $GoodsModel->getClassList();
//            $this->assign('class_list', list_to_tree($class_list));
            $this->assign('class_list', $class_list);

            return $this->fetch('goods/goods_add');
        }
    }

    /**
     * 商品编辑
     * @param int $id 当前商品id
     * @return mixed
     */
    public function goodsEdit($id = null)
    {
        $GoodsModel = new GoodsModel();
        if ($data = input('post.')) {

            $goods = $GoodsModel->getGoodsOne(['id' => $data['id']]);
            !$goods && $this->error('商品不存在');

            if ($file = request()->file('img')) {
                $file_name       = $file->validate(['ext' => 'jpg,png'])->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . 'image', $goods['product_code'])->getBasename();
                $data['img_url'] = '/uploads/image/' . $file_name;
            } else {
                $data['img_url'] = $goods['img_url'];
            }

            $result = $GoodsModel->saveGoods($data);
            empty($result) ? $this->success('操作成功', url('goodsList')) : $this->error($result);
        } else {
            $goods = $GoodsModel->getGoodsOne(['id' => $id]);
            !$goods && $this->error('商品不存在');
            $this->assign('row', $goods);

            $class_list = $GoodsModel->getClassList();
            $this->assign('class_list', list_to_tree($class_list));

//            print_r("<pre>");
//            print_r($goods); print_r("</pre>");

            return $this->fetch('archives/goods/goodsEdit');
        }
    }

    /**
     * 商品删除(待审核)
     * @param int $id
     */
    public function goodsDel($id = null)
    {
        (!$id || !is_numeric($id)) && $this->error('参数错误');

        $GoodsModel = new GoodsModel();
        $result     = $GoodsModel->delGoods($id);
        $result ? $this->success('操作成功', url('goodsList')) : $this->error('商品不存在或已上架');
    }

    /**
     * 商品查看，只能看，不能改
     * @param int $id 当前商品id
     * @return mixed
     */
    public function goodsLook($id = null)
    {
        $GoodsModel = new GoodsModel();
        $goods      = $GoodsModel->getGoodsOne(['id' => $id]);
        !$goods && $this->error('商品不存在');
        if ($goods) {
            $class_name = $GoodsModel->getClassName($goods['class_id']);
            $goods      = array_merge($goods, $class_name);
        }
        $this->assign('row', $goods);

        return $this->fetch('archives/goods/goodsInfo');
    }

    //商品库存记录
    public function goodsInventory($id = null)
    {
        !$id && $this->error('商品不存在');
        $goods = GoodsModel::getGoodsOne(['id' => $id]);
        $this->assign('goods', $goods);

        $ids = Purchase::getGoodsID(['com_id' => $id]);

        $goods_inv = Inventory::getInventoryList(['purchase_id' => ['IN', $ids ? $ids : 0], 'status' => 1], ['update_time' => 'DESC']);
        $this->assign('goods_inv', $goods_inv);

        return $this->fetch('archives/goods/goodsStockMark');
    }
}