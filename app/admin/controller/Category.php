<?php
namespace app\admin\controller;

use think\Request;
use think\Validate;
use think\Cache;
use app\admin\Model\Category as CategoryModel;
class Category extends Base
{
	//分类
	public function index()
	{
		if (!Cache::has("category_list")) {
			$data = new CategoryModel();
	    	//获取分类后的数据
			$category = $data->getCategory();
			Cache::set("category_list", $category);
		} else {
			$category = Cache::get("category_list");
		}
		//显示数据
		$this->assign("list", $category);
		return $this->fetch();
	}

	//添加分类
	public function add(Request $request)
	{
		if ($request->isPost()) {
			$validate = new Validate([
				'pid' => 'require',
				'category_name' => 'require',
				'is_show' => 'require',
				'sort' => 'require'
			]);
			//检测数据是否填写完整
			if (!$validate->check($request->post())) {
				return $this->error($validate->getError());
			}
			$data = [
				'category_name' => $request->post('category_name'),
				'category_pid' => $request->post('pid'),
				'is_show' => $request->post('is_show'),
				'sort' => $request->post('sort'),
				'create_at' => time(),
				'updaet_at' => time()
			];
			if (!CategoryModel::insert($data)) {
				return $this->error("添加失败");
			}
			Cache::rm("category_list");
			return redirect("/admin/category/index");
		}
		$data = new CategoryModel();
	    //获取分类后的数据
		$category = $data->getCategory();
		//显示数据
		$this->assign("category_list", $category);
		return $this->fetch();
	}

	//删除分类
	public function delete($category_id)
	{
		//如果是父类删除该类下的所有。
		$category = new CategoryModel();
		$category->deleteCategory($category_id);
		Cache::rm("category_list");
		return $this->success("删除成功", 'admin/category/index');
	}

	//修改
	public function edit($category_id)
	{
		if (request()->isPost()) {
			$validate = new Validate([
				'pid' => 'require',
				'category_name' => 'require',
				'sort' => 'require'
			]);
			//检测数据是否填写完整
			if (!$validate->check(request()->post())) {
				return $this->error($validate->getError());
			}
			$data = [
				'category_name' => request()->post('category_name'),
				'category_pid' => request()->post('pid'),
				'is_show' => request()->post('is_show', 0),
				'sort' => request()->post('sort'),
				'update_at' => time()
			];
			if (!CategoryModel::where('category_id', $category_id)->update($data)) {
				return $this->error("修改失败");
			}
			return redirect("admin/category/index");
		}
		$category = new CategoryModel();
		$cate_list = $category->getCategory();
		$cate = $category->get($category_id);
		$this->assign("category_list", $cate_list);
		$this->assign("cate", $cate);
		Cache::rm("category_list");
		return $this->fetch();
	}

	public function setShow()
	{
		if (request()->isAjax()) {
			$cate = new CategoryModel();
			if ($cate->setShow(request()->post("x"))) {
				Cache::clear();
				$this->success('状态开启');
			} else {
				Cache::clear();
				$this->success('状态禁止');
			}
		}
	}
}