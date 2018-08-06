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

class CategoryController extends IndexController {

    /**
     * 前端输出，默认输出所有分类
     * @return mixed
     */
    public function categoryList()
    {
        $category_list = GoodsModel::getCategoryList();
        $this->assign('category_list', list_to_tree($category_list));
        return $this->fetch('category/category_list');
    }


    public function categoryAdd()
    {
        if ($data = input('post.')) {
            $result = GoodsModel::saveCategory($data);
            empty($result) ? $this->success('操作成功','categoryList') : $this->error($result);
        } else {
            return $this->fetch('category/category_add');
        }
    }

    /**
     * 编辑商品分类
     * @param null $id
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function categoryEdit($id = null)
    {
        if ($data = input('post.')) {
            $result = GoodsModel::saveCategory($data);
            empty($result) ? $this->success('操作成功','categoryList') : $this->error($result);
        } else {
            $row = $id ? GoodsModel::getCategoryOne($id) : '';
            $this->assign('data', $row);
            return $this->fetch('category/category_edit');
        }
    }

    public function categoryDel($id = null)
    {
        (!$id || !is_numeric($id)) && $this->error('参数错误');

        $result = GoodsModel::delCategory($id);

        empty($result) ? $this->success('操作成功') : $this->error($result);
    }
}