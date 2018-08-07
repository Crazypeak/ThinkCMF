<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/7
 * Time: 19:15
 */

namespace app\api\controller;

use app\user\model\UserModel;
use cmf\controller\CaptchaController;
use think\Request;
use think\Validate;

class UserController extends IndexController
{

    /**
     * @SWG\Get(path="/User/captcha",
     *   tags={"User"},
     *   summary="验证码获取",
     *   operationId="captcha",
     *   @SWG\Response(
     *     response=200,
     *     description="直接返回图片",
     *   ),
     * )
     */
    public static function captcha(Request $request)
    {
        return CaptchaController::index($request)->header(['Access-Control-Allow-Credentials' => 'true', 'Access-Control-Allow-Origin' => '*',]);
    }

    /**
     * @SWG\Post(path="/User/doLogin",
     *   tags={"User"},
     *   summary="登录提交接口",
     *   operationId="doLogin",
     *   @SWG\Parameter(
     *     description="用户名称",
     *     in="formData",
     *     name="username",
     *     required=true,
     *     type="string",
     *     default="admin",
     *   ),
     *   @SWG\Parameter(
     *     description="用户密码",
     *     in="formData",
     *     name="password",
     *     required=true,
     *     type="string",
     *     default="qweqwe",
     *   ),
     *   @SWG\Parameter(
     *     description="验证码",
     *     in="formData",
     *     name="captcha",
     *     required=true,
     *     type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="登录成功",
     *   ),
     * )
     */
    public function doLogin()
    {
        if ($this->request->isPost()) {
            $validate = new Validate([
                'captcha'  => 'require',
                'username' => 'require',
                'password' => 'require|min:6|max:32',
            ]);
            $validate->message([
                'username.require' => '用户名不能为空',
                'password.require' => '密码不能为空',
                'password.max'     => '密码不能超过32个字符',
                'password.min'     => '密码不能小于6个字符',
                'captcha.require'  => '验证码不能为空',
            ]);

            $data = $this->request->post();
            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }

            if (!cmf_captcha_check($data['captcha'])) {
                $this->error(lang('CAPTCHA_NOT_RIGHT'));
            }

            $userModel         = new UserModel();
            $user['user_pass'] = $data['password'];
            if (Validate::is($data['username'], 'email')) {
                $user['user_email'] = $data['username'];
                $log                = $userModel->doEmail($user);
            } else if (cmf_check_mobile($data['username'])) {
                $user['mobile'] = $data['username'];
                $log            = $userModel->doMobile($user);
            } else {
                $user['user_login'] = $data['username'];
                $log                = $userModel->doName($user);
            }
            $session_login_http_referer = session('login_http_referer');
//            $redirect                   = empty($session_login_http_referer) ? $this->request->root() : $session_login_http_referer;
            switch ($log) {
                case 0:
                    cmf_user_action('login');
                    $this->success(lang('LOGIN_SUCCESS'));
                    break;
                case 1:
                    $this->error(lang('PASSWORD_NOT_RIGHT'),-$log);
                    break;
                case 2:
                    $this->error('账户不存在',-$log);
                    break;
                case 3:
                    $this->error('账号被禁止访问系统',-$log);
                    break;
                default :
                    $this->error('未受理的请求');
            }
        } else {
            $this->error("请求错误");
        }
    }

}