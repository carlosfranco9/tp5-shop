<?php

namespace app\admin\model;

use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\Model;

class Consult extends Model
{
    /**
     * 获取商品咨询列表
     */
    public function getConsult()
    {
        $data = Db::view('consult')
            ->view('goods', 'goods_name', 'goods.goods_id=consult.goods_id')
            ->order("add_time", 'desc')
            ->paginate(10);
        return $data;
    }

    public function search($keyword)
    {

        $data = Db::view('consult')
            ->view('goods', 'goods_name', 'goods.goods_id=consult.goods_id')
            ->where('username|content', 'LIKE', "%" . $keyword . "%")
            ->order("add_time", 'desc')
            ->paginate(10, false, ['query' => [keyword => $keyword]]);
        return $data;
    }
}