<?php
namespace app\admin\controller;

use think\Cache;
use app\admin\model\Goods as GoodsModel;
class Goods extends Base
{
	public function index()
	{
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		if (!Cache::has("goods_list_".$page)) {
			$data = new GoodsModel;
			$goods = $data->getGoods();
			Cache::set("goods_list_".$page, $goods);
		} else {
			$goods = Cache::get("goods_list_".$page);
		}
		$this->assign("list", $goods);
		return $this->fetch();
	}
}