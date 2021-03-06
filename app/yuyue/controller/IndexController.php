<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/5
 * Time: 14:53
 */
namespace app\yuyue\controller;
use cmf\controller\AdminBaseController;

class IndexController extends AdminBaseController{

    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }

    /**
     * 搜索器
     * @param $model
     * @return array
     */
    protected function allWhere($model = '',$arr=[])
    {
        $where = [];
        $get   = input('get.');
//        $controller = explode('.', Request::instance()->controller())[1];

        switch ($model) {
            case 'goods':
                $text_all     = ['name', 'product_code'];
                $select_all   = ['category_id'];
                $time_status  = false;
                $depot_status = true;
                break;
            case 'deposit':
                $text_all     = ['name', 'code'];
                $select_all   = [];
                $time_status  = false;
                $depot_status = true;
                break;
            case 'order':
                $text_all     = ['order_code'];
                $select_all   = ['status', 'ck_status'];
                $time_status  = true;
                $depot_status = true;
                break;
            default:
                $text_all     = $arr[0];
                $select_all   = $arr[1];
                $time_status  = $arr[2];
                $depot_status = $arr[3];
                break;
        }

        //时间范围搜索
        if ($time_status) {
            $get['sta_date'] = isset($get['sta_date']) ? $get['sta_date'] : date('Y-m-d', strtotime(date('Y-m')));
            $get['end_date'] = isset($get['end_date']) ? $get['end_date'] : date('Y-m-d', time());

            $this->assign('sta_date', $get['sta_date']);
            $this->assign('end_date', $get['end_date']);

            $where['create_time'] = ['BETWEEN', [$get['sta_date'], $get['end_date'] . ' 23:59:59']];
        }

//    //栏目下拉选择
//    if ($depot_status) {
//        $this->assign('barn_list', Depot::getUserBarnName());
//        !IS_ROOT && $where['depot_id'] = ['IN', Depot::getUserBarn(USER_ID) ? Depot::getUserBarn(USER_ID) : ''];
//    }

//    //商品分类+选择
//    if (isset($get['category_id']) && isset($select_all['category_id'])) {
//        $where['category_id'] = ['IN', Commodity::getCategoryID($get['category_id'])];
//        unset($get['category_id']);
//    }

        //模糊搜索与一般选择
        foreach ($get as $key => $value) {
            //状态下拉菜单搜索
            if (in_array($key, $select_all)) {
                $where[$key] = $value;
            } //模糊搜索
            else if (in_array($key, $text_all)) {
                $where[$key] = ['LIKE', '%' . trim($value) . '%'];
            }
        }

        return $where;
    }
}