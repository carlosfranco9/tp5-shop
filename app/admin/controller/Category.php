<?php
namespace app\admin\controller;

use think\Request;
use app\admin\Model\Category as CategoryModel;
class Category extends Base
{
	//分类
	public function index()
	{

		$data = new CategoryModel();
	    //获取分类后的数据
		$category = $data->getCategory();
		//显示数据
		$this->assign("list", $category);
		return $this->fetch();
	}

	//添加分类
	public function add(Request $request)
	{
		if ($request->isPost()) {
			if (empty($request->post('category_name'))) {
				return $this->error("请填写完整");
			}
			$data = [
				'category_name' => $request->post('category_name'),
				'category_pid' => $request->post('pid'),
				'create_at' => time()
			];
			if (!CategoryModel::insert($data)) {
				return $this->error("添加失败");
			}
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
		return $this->success("删除成功", 'admin/category/index');
	}

	//修改
	public function edit($category_id)
	{
		if (request()->isPost()) {
			if (empty(request()->post('category_name'))) {
				return $this->error("请填写完整");
			}
			$data = [
				'category_pid' => request()->post('pid'),
				'category_name' => request()->post('category_name'),
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
		return $this->fetch();
	}
}