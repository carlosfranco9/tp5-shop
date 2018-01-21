<?php
namespace app\admin\model;

use think\Model;
class Category extends Model
{
	//获取所有分类
	public function getCategory()
	{
		$data = self::all();
		$arr = [];
		foreach ($data as $key => $val) {
			$arr[] = $val->toArray();
		}
		return $this->infinitus($arr, 'category_id', 'category_pid');
	}

	//无限极分类
	protected function infinitus($data, $id = 'id', $find_id = 'pid', $pid = 0, $level = 1)
	{
		$array = [];
		foreach ($data as $key => $val) {
			if ($val[$find_id] == $pid) {
			    $array[$key] = $val;
			    $array[$key]['str'] = str_repeat("&nbsp;", $level).'|'.str_repeat("-", $level);
			    $array[$key]['level'] = $level;
			    $array[$key]['children'] = $this->infinitus($data, $id, $find_id,$val['category_id'], $level + 1);
		    }
		}
		return $array;
	}
	
	//删除分类
	public function deleteCategory($category_id)
	{
		if (!$children = $this->findChildren($category_id)) {
			//无子类。直接删除
			return self::destroy(['category_id' => $category_id]);
		}
		//有子类。连同子类删除
		//删除父类
		self::destroy(['category_id' => $category_id]);
		foreach ($children as $key => $val) {
			//删除子类
			$val->delete();
		}
		return true;

	}

	//寻找子类
	protected function findChildren($category_id)
	{
		$children = self::where('category_pid', $category_id)->select();
		return $children;
	}
}