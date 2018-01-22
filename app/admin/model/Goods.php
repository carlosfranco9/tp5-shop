<?php
namespace app\admin\model;

use think\Model;
class Goods extends Model
{
	public function getGoods()
	{
		return self::paginate(20);
	}
}