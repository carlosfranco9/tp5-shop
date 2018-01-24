<?php
namespace app\admin\model;

use think\Model;

class Goods extends Model
{
    public function getGoods()
    {
        $data = self::alias('g')
            ->join("yf_category c", 'c.category_id = g.cat_id', 'left')
            ->order("goods_id", 'desc')
            ->paginate(15);
        return $data;
    }

    //设置推荐、新品、热卖、上架
    public function setInfo($goods_id, $type)
    {
        $res = self::get($goods_id);
        if (!$res->$type) {
            //状态为否。即0
            $res->$type = 1;
            $res->save();
            return true;
        }
        $res->$type = 0;
        $res->save();
        return false;
    }

    public function editGoods($goods_id)
    {
        $goods = self::get($goods_id);
        dump($goods);
    }

    //查找分类
    public function getCategory($goods_id)
    {
        $data = self::alias('g')
            ->join("yf_category c", 'c.category_id = g.cat_id', 'left')
            ->where("g.goods_id", $goods_id)
            ->find();
        return $data;
    }
}
