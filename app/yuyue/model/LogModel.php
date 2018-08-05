<?php
/**
 * Created by PhpStorm.
 * User: Crazypeak
 * Date: 2017/2/25
 * Time: 15:18
 */

namespace app\yuyue\model;

use think\Db;
use think\Model;
use think\Request;

class LogModel extends Model
{

    public static function getLogList($where = [], $page = 1)
    {
        return Db::name('log')->where($where)->order(['create_time' => 'ASC'])->page(intval($page), 20)->select();
    }

//    public static function getLogOne($id)
//    {
////        $where = ['record_id' => $record_id,'record_table'=>$record_table];
////        $user_id && $where['user_id'] = $user_id;
//        return Db::table('log')->where(['id' => $id])->find();
//    }

    /**
     * 操作日志
     * @param int $type 对象表
     * @param int $record_id 对象id
     * @param string $remark 备注
     * @return int|string
     */

    public static function addLog($type = 0, $record_id = 0, $remark = '',$nickname = '')
    {
        return true;
//        $record_text = ['user', 'auth_rule', 'goods', 'goods_class', 'api'];
//
//        $data['create_time']  = time();
//        $data['record_table'] = $record_text[$type];
//        $data['record_id']    = $record_id;
//        $data['remark']       = $remark;
//        $data['nickname']     = $nickname ? $nickname : session('user_auth.nickname');
//        $data['ip_address']   = Request::instance()->ip(1);
//
//        return Db::name('log')->insert($data);
    }
}