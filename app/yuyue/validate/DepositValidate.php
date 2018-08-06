<?php
/**
 * Created by PhpStorm.
 * User: Crazypeak
 * Date: 2017/2/22
 * Time: 11:15
 */

namespace app\yuyue\validate;

use think\Validate;

class DepositValidate extends Validate
{
    protected $rule = [
        'name' => ['require',],
        'code' => ['require', 'alphaNum'],

        'price'      => ['require', 'float'],
        'unit_price' => ['require', 'float'],

        'status'     => ['require', 'number'],
        'sequence'   => ['require', 'number'],
        'img_url'    => ['require',],
    ];

    protected $message = [
        'name.require'       => '名称不能为空',
        'code.require'       => '编号错误',
        'code.alphaNum'      => '编号错误',
        'price.require'      => '预约价格错误',
        'price.float'        => '预约价格错误',
        'unit_price.require' => '额外单价错误',
        'unit_price.float'   => '额外单价错误',
        'status.require'     => '状态错误',
        'status.number'      => '状态错误',
        'sequence.require'   => '排序错误',
        'sequence.number'    => '排序错误',
        'img_url.require'    => '请上传封面图片',
    ];

    protected $scene = [
        'deposit_save' => [
            'name',
            'price',
            'unit_price',
            'status',
            'sequence',
            'img_url',
        ],
    ];
}