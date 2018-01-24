<?php
namespace app\admin\controller;

use app\admin\model\Goods as GoodsModel;
use app\admin\model\Category;
use think\Cache;

class Goods extends Base
{
    private $save_path = "goods/";

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
     **/
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

    public function edit($goods_id)
    {
    	if (request()->isPost()) {
    		return ;
    	}
        //分类
        $cate = new GoodsModel();
        $goods_cate = $cate->getCategory($goods_id);
        //所有类别
        $category = new Category();
        $all_category = $category->getCategory();
    	$goodsModel = GoodsModel::get($goods_id);
    	$this->assign("goods", $goodsModel);
        $this->assign("goods_category", $goods_cate);
        $this->assign("category_list", $all_category);
    	return $this->fetch();
    }

    /**
     * @function imageUp
     */
    public function upload()
    {
        // 上传图片框中的描述表单名称，
        $pictitle = input('pictitle');
        $dir = input('dir');
        $title = htmlspecialchars($pictitle , ENT_QUOTES);
        $path = htmlspecialchars($dir, ENT_QUOTES);
        //$input_file ['upfile'] = $info['Filedata'];  一个是上传插件里面来的, 另外一个是 文章编辑器里面来的
        // 获取表单上传文件
        $file = request()->file('file');
        if(empty($file))
            $file = request()->file('upfile');

        $result = $this->validate(
                ['file' => $file],
                ['file'=>'image|fileSize:40000000|fileExt:jpg,jpeg,gif,png,bmp'],
                ['file.image' => '上传文件必须为图片','file.fileSize' => '上传文件过大','file.fileExt'=>'上传文件后缀名必须为jpg,jpeg,gif,png,bmp']
        );
        if (true !== $result || !$file) {
            $state = "ERROR" . $result;
        } else {
            $savePath = $this->save_path.date('Y').'/'.date('m-d').'/';
            // $ossConfig = tpCache('oss');
            // $ossSupportPath = ['goods', 'water'];
            //水印

            /*if (in_array(input('savepath'), $ossSupportPath) ) {//&& $ossConfig['oss_switch']) {
                //商品图片可选择存放在oss
                $object = 'public/upload/'.$savePath.md5(time()).'.'.pathinfo($file->getInfo('name'), PATHINFO_EXTENSION);
                // $ossClient = new \app\common\logic\OssLogic;
                // $return_url = $ossClient->uploadFile($file->getRealPath(), $object);
                if (!$return_url) {
                    $state = "ERROR" . $ossClient->getError();
                    $return_url = '';
                } else {
                    $state = "SUCCESS";
                }
                @unlink($file->getRealPath());
            } else { */
                // 移动到框架应用根目录/public/uploads/ 目录下
                $info = $file->rule(function ($file) {
                return  md5(mt_rand()); // 使用自定义的文件保存规则
                })->move('public/upload/'.$savePath);
                if ($info) {
                    $state = "SUCCESS";
                } else {
                    $state = "ERROR" . $file->getError();
                }
                $return_url = '/public/upload/'.$savePath.$info->getSaveName();
            // }
            $return_data['url'] = $return_url;
        }

        /*if($state == 'SUCCESS'){
            if($this->save_path=='goods/'){
                $imgresource = ".".$return_url;
                $image = \think\Image::open($imgresource);
                // $water = tpCache('water');
                //$image->open($imgresource);
                $return_data['mark_type'] = $water['mark_type'];
                if($water['is_mark']==1 && $image->width()>$water['mark_width'] && $image->height()>$water['mark_height']){
                    if($water['mark_type'] == 'text'){
                        //$image->text($water['mark_txt'],'./hgzb.ttf',20,'#000000',9)->save($imgresource);
                        $ttf = './hgzb.ttf';
                        if (file_exists($ttf)) {
                            $size = $water['mark_txt_size'] ? $water['mark_txt_size'] : 30;
                            $color = $water['mark_txt_color'] ?: '#000000';
                            if (!preg_match('/^#[0-9a-fA-F]{6}$/', $color)) {
                                $color = '#000000';
                            }
                            $transparency = intval((100 - $water['mark_degree']) * (127/100));
                            $color .= dechex($transparency);
                            $image->open($imgresource)->text($water['mark_txt'], $ttf, $size, $color, $water['sel'])->save($imgresource);
                            $return_data['mark_txt'] = $water['mark_txt'];
                        }
                    }else{
                        //$image->water(".".$water['mark_img'],9,$water['mark_degree'])->save($imgresource);
                        $waterPath = "." . $water['mark_img'];
                        $quality = $water['mark_quality'] ? $water['mark_quality'] : 80;
                        $waterTempPath = dirname($waterPath).'/temp_'.basename($waterPath);
                        $image->open($waterPath)->save($waterTempPath, null, $quality);
                        $image->open($imgresource)->water($waterTempPath, $water['sel'], $water['mark_degree'])->save($imgresource);
                        @unlink($waterTempPath);
                    }
                }
            }
        }*/
        $return_data['title'] = $title;
        $return_data['original'] = ''; // 这里好像没啥用 暂时注释起来
        $return_data['state'] = $state;
        $return_data['path'] = $path;
        return response(json_encode($return_data));
        // $this->ajaxReturn($return_data,'json');
    }
}
