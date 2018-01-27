<?php
namespace app\admin\controller;

use app\admin\model\Category as CategoryModel;
use app\admin\model\Goods as GoodsModel;
use think\Cache;
use think\Validate;

class Goods extends Base
{
    private $save_path = "goods/";

    /**
     * 商品列表
     * @return 视图
     */
    public function index()
    {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        if (!Cache::has("goods_list_" . $page)) {
            $data  = new GoodsModel;
            $goods = $data->getGoods();
            Cache::tag("goods")->set("goods_list_" . $page, $goods);
        } else {
            $goods = Cache::get("goods_list_" . $page);
        }
        $this->assign("list", $goods);
        return $this->fetch();
    }

    /**
     * 设置推荐、新品、热卖、上架
     */
    public function setInfo()
    {
        $goods = new GoodsModel();
        $bool  = $goods->setInfo(request()->post("x"), request()->post("type"));
        Cache::clear("goods");
        if (!$bool) {
            return $this->success("否");
        }
        return $this->success("是");
    }

    /**
     * 编辑商品页
     * @param  $goods_id 商品id
     * @return [type]
     */
    public function edit($goods_id)
    {
        if (request()->isPost()) {
            if (!$this->validatePostData(request()->post())) {
                return $this->error($res);
            }
            $data  = request()->post();
            $Goods = new GoodsModel();
            $good  = $Goods->get(request()->post('goods_id'));
            $good->save([
                'cat_id'           => $data['cate_id'],
                'goods_sn'         => $data['goods_sn'],
                'goods_name'       => $data['goods_name'],
                'store_count'      => $data["store_count"],
                'market_price'     => $data['market_price'],
                'shop_price'       => $data['shop_price'],
                'cost_price'       => $data['cost_price'],
                'keywords'         => $data['keywords'],
                'goods_remark'     => $data['goods_remark'],
                'goods_content'    => $data['goods_content'],
                'original_img'     => $data['original_img'],
                'is_on_sale'       => isset($data["is_on_sale"]) ? $data['is_on_sale'] : 0,
                'is_free_shipping' => isset($data['is_free_shipping']) ? $data['is_free_shipping'] : 0,
                'sort'             => $data['sort'],
                'is_recommend'     => isset($data['is_recommend']) ? $data['is_recommend'] : 0,
                'is_new'           => isset($data['is_new']) ? $data['is_new'] : 0,
                'is_hot'           => isset($data['is_hot']) ? $data['is_hot'] : 0,
                'last_update'      => time(),
            ]);
            return redirect("/admin/goods/index");
        }
        //分类
        $cate       = new GoodsModel();
        $goods_cate = $cate->getCategory($goods_id);
        //所有类别
        $category     = new CategoryModel();
        $all_category = $category->getCategory();
        $goodsModel   = GoodsModel::get($goods_id);
        $this->assign("goods", $goodsModel);
        $this->assign("goods_category", $goods_cate);
        $this->assign("category_list", $all_category);
        return $this->fetch();
    }

    /**
     * 检测数据是否填写。删除一些不需要检测的字段
     * @param  [type]
     * @return [type]
     */
    protected function validatePostData($data)
    {
        $array = [];
        if (isset($data['top_id'])) {
            unset($data['top_id'], $data['goods_remark'], $data['keywords'], $data['is_on_sale'], $data['is_hot'], $data['is_free_shipping'], $data['is_recommend'], $data['goods_sn']);
        }
        foreach ($data as $key => $val) {
            $array[$key] = "require";
        }
        $validate = new Validate($array);
        //检测数据是否填写完整
        if (!$validate->check($data)) {
            return $this->error($validate->getError());
        }
        return true;
    }

    /**
     * 删除商品
     * @param  [type]
     * @return [type]
     */
    public function delete($goods_id)
    {
        $bool = GoodsModel::destroy($goods_id);
        if (!$bool) {
            return $this->error("删除失败");
        }
        Cache::clear("goods");
        return $this->success("删除成功","/admin/goods/index");
    }

    /**
     * 添加商品
     * @return [type]
     */
    public function store()
    {
        if (request()->isPost()) {
            $data     = request()->post();
            $goods_sn = $data['goods_sn'];
            unset($data['goods_sn'], $data['top_id']);
            if (!$this->validatePostData(request()->post())) {
                return $this->error($res);
            }
            $data['cat_id'] = $data['cate_id'];
            unset($data['cate_id']);
            if (isset($goods_sn) and empty($goods_sn)) {
                $res = GoodsModel::event("after_insert", function ($user) {
                    $n              = 1000000 + $user->goods_id;
                    $n              = preg_replace("/^1*/", "0", $n);
                    $user->goods_sn = "TP".$n;
                    $user->save();
                });
            } else {
                $data['goods_sn'] = $goods_sn;
            }
            $Goods        = new GoodsModel();
            $Goods_insert = $Goods->save($data);
            Cache::clear("goods");
            return redirect("/admin/goods/index");
        }

        $category      = new CategoryModel();
        $category_list = $category->getCategory();
        $this->assign("category_list", $category_list);
        return $this->fetch("add");
    }

    /**
     * @return [type]
     */
    public function upload()
    {
        // 上传图片框中的描述表单名称，
        $pictitle = input('pictitle');
        $dir      = input('dir');
        $title    = htmlspecialchars($pictitle, ENT_QUOTES);
        $path     = htmlspecialchars($dir, ENT_QUOTES);
        //$input_file ['upfile'] = $info['Filedata'];  一个是上传插件里面来的, 另外一个是 文章编辑器里面来的
        // 获取表单上传文件
        $file = request()->file('file');
        if (empty($file)) {
            $file = request()->file('upfile');
        }

        $result = $this->validate(
            ['file' => $file],
            ['file' => 'image|fileSize:40000000|fileExt:jpg,jpeg,gif,png,bmp'],
            ['file.image' => '上传文件必须为图片', 'file.fileSize' => '上传文件过大', 'file.fileExt' => '上传文件后缀名必须为jpg,jpeg,gif,png,bmp']
        );
        if (true !== $result || !$file) {
            $state = "ERROR" . $result;
        } else {
            $savePath = $this->save_path . date('Y') . '/' . date('m-d') . '/';
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->rule(function ($file) {
                return md5(mt_rand()); // 使用自定义的文件保存规则
            })->move('public/upload/' . $savePath);
            if ($info) {
                $state = "SUCCESS";
            } else {
                $state = "ERROR" . $file->getError();
            }
            $return_url = '/public/upload/' . $savePath . $info->getSaveName();
            // }
            $return_data['url'] = $return_url;
        }
        $return_data['title']    = $title;
        $return_data['original'] = ''; // 这里好像没啥用 暂时注释起来
        $return_data['state']    = $state;
        $return_data['path']     = $path;
        return response(json_encode($return_data));
        // $this->ajaxReturn($return_data,'json');
    }
}
