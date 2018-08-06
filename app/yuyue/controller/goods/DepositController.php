<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/2
 * Time: 17:44
 */

namespace app\yuyue\controller\goods;
use app\yuyue\controller\IndexController;
use app\yuyue\model\DepositModel;

class DepositController extends IndexController {

    /**
     * 前端输出，默认输出所有分类
     * @return mixed
     */
    public function depositList()
    {
        //遍历查询条件
        $where = $this->allWhere('deposit');

        $deposit_list = DepositModel::getDepositList($where);
        $this->assign('deposit_list', $deposit_list);
        return $this->fetch('deposit/deposit_list');
    }


    public function depositAdd()
    {
        if ($data = input('post.')) {
            $data['code'] = config('DEPOSIT_CODE_KEY') . date('YmdHis') . rand(1000, 9999);
            $result = DepositModel::saveDeposit($data);
            empty($result) ? $this->success('操作成功','depositList') : $this->error($result);
        } else {
            return $this->fetch('deposit/deposit_add');
        }
    }

    /**
     * 编辑商品分类
     * @param null $id
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function depositEdit($id = null)
    {
        if ($data = input('post.')) {
            $result = DepositModel::saveDeposit($data);
            empty($result) ? $this->success('操作成功','depositList') : $this->error($result);
        } else {
            $row = $id ? DepositModel::getDepositOne($id) : '';
            $this->assign('row', $row);
            return $this->fetch('deposit/deposit_edit');
        }
    }

    public function depositDel($id = null)
    {
        (!$id || !is_numeric($id)) && $this->error('参数错误');

        $result = DepositModel::delDeposit($id);

        empty($result) ? $this->success('操作成功') : $this->error($result);
    }
}