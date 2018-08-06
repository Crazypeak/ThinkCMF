<?php
/**
 * Created by PhpStorm.
 * User: Crazypeak
 * Date: 2017/2/22
 * Time: 11:15
 */

namespace app\yuyue\validate;

use think\Validate;

class GoodsValidate extends Validate
{
    protected $rule = [
        'name'             => ['require',],
        'pid'              => ['require', 'number'],
        'category_id'         => ['require', 'number'],
        'bar_code'         => ['require', 'alphaNum'],
        'product_code'     => ['require', 'alphaNum'],
//        'min_price'        => ['require', 'float'],
//        'max_price'        => ['require', 'float'],
        'selling_price'    => ['require', 'float'],
        'market_price'     => ['require', 'float'],
        'supply_price'     => ['require', 'float'],
        'stock'            => ['require', 'number'],
//        'img_url'          => ['require'],
        'status'           => ['require', 'number'],
        'img_url'          => ['require',],
        'spec'             => ['require'],
//        'currency'         => ['require'],
        'brokerage_type'   => ['require', 'number'],
        'brokerage_number' => ['require', 'float'],
    ];

    protected $message = [
        'name.require'             => '名称不能为空',
        'pid.require'              => '上级分类错误',
        'pid.number'               => '上级分类错误',
        'category_id.require'         => '分类错误',
        'category_id.number'          => '分类错误',
        'bar_code.require'         => '条形码错误',
        'bar_code.alphaNum'        => '条形码错误',
        'product_code.require'     => '平台货号错误',
        'product_code.alphaNum'    => '平台货号错误',
//        'min_price.require'        => '最低价格错误',
//        'min_price.float'          => '最低价格错误',
//        'max_price.require'        => '最高价格错误',
//        'max_price.float'          => '最高价格错误',
        'selling_price.require'    => '售卖价格错误',
        'selling_price.float'      => '售卖价格错误',
        'market_price.require'     => '市场价格错误',
        'market_price.float'       => '市场价格错误',
        'supply_price.require'     => '成本价格错误',
        'supply_price.float'       => '成本价格错误',
        'stock.require'            => '库存错误',
        'stock.float'              => '库存错误',
        'status.require'           => '状态错误',
        'status.number'             => '状态错误',
        'img_url.require'          => '请上传封面图片',
        'spec.require'             => '规格不能为空',
//        'unit.require'             => '单位不能为空',
//        'currency.require'         => '币种不能为空',
        'brokerage_type.require'   => '提成类型错误',
        'brokerage_type.number'    => '提成类型错误',
        'brokerage_number.require' => '提成数值错误',
        'brokerage_number.float'   => '提成数值错误',
    ];

    protected $scene = [
        'goods_category' => ['name','status'],
        'goods_save'  => [
            'name',
            'category_id',
            'selling_price',
            'market_price',
            'supply_price',
            'stock',
            'img_url',
            'status',
        ],
    ];
}