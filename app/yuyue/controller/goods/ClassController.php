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

class ClassController extends IndexController {

    /**
     * 前端输出，默认输出所有分类
     * @return mixed
     */
    public function classList($cid = 0)
    {
        $this->assign('cid',$cid);
        $class_list = GoodsModel::getClassList();
//        $this->assign('class_list', list_to_tree($class_list));
        $this->assign('class_list', $class_list);
        return $this->fetch('goods/class_list');
    }


    public function classAdd()
    {
        return $this->fetch('goods/class_add');
    }

    /**
     * 编辑商品分类
     * @param null $id
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function classEdit($id = null)
    {
        if ($data = input('post.')) {
            $result = GoodsModel::saveClass($data);
            empty($result) ? $this->success('操作成功','classList') : $this->error($result);
        } else {
            $row = $id ? GoodsModel::getClassOne($id) : '';
            $this->assign('data', $row);
            return $this->fetch('goods/class_edit');
        }
    }

    public function classDel($id = null)
    {
        (!$id || !is_numeric($id)) && $this->error('参数错误');

        $result = GoodsModel::delClass($id);

        empty($result) ? $this->success('操作成功') : $this->error($result);
    }
}