<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/2
 * Time: 16:07
 */

namespace app\api\controller;

use think\Controller;
use think\Response;
use Swagger;

class IndexController extends Controller
{

    protected function foot($data, $code = 1, $msg = '成功',$url=null)
    {
        $result = [
            'code' => $code,
            'msg'  => $msg,
            'data' => $data,
            'url'  => $url,
        ];

        $header = [
            'Content-Type'                     => 'application/json; charset=utf-8',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Allow-Origin'      => '*',
        ];

        Response::create($result, 'json', 200, $header)->send();
        die;
    }

    /**
     * @SWG\Swagger(
     *     schemes={"http","https"},
     *     host="127.0.0.1",
     *     basePath="/api",
     *     @SWG\Info(
     *         version="1.0.0",
     *         title="This is my website cool API",
     *         description="Api description...",
     *         termsOfService="",
     *     ),
     *     @SWG\Definition(
     *       definition="goodsCart",
     *        @SWG\Property(
     *          property="id",
     *          type="integer",
     *          description="商品id",
     *          default="10",
     *        ),
     *        @SWG\Property(
     *          property="number",
     *          type="integer",
     *          description="商品数量",
     *          default="10",
     *        )
     *     ),
     *     @SWG\Tag(
     *      name="User",
     *      description="用户接口",
     *     ),
     * )
     */
    public function index()
    {
        $path = __DIR__ . '/../../'; //你想要哪个文件夹下面的注释生成对应的API文档
        $swagger = \Swagger\scan($path);
        // header('Content-Type: application/json');
        // echo $swagger;
        $swagger_json_path = __DIR__ . '/../../../public/swagger/swagger.json';
        $res = file_put_contents($swagger_json_path, $swagger);
        if ($res == true) {
            $this->redirect('/swagger');
        }
    }
}