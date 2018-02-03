<?php

namespace app\admin\controller;

use app\admin\model\Consult as ConsultModel;
use think\Cache;
use think\Request;

class Consult extends Base
{
    public function index()
    {
        if (!Cache::has("consult_list")) {
            $data = new ConsultModel();
            $consult = $data->getConsult();
        } else {
            $consult = Cache::get("consult_list");
        }
        $this->assign("list", $consult);
        return $this->fetch();
    }

    //删除
    public function delete($ids)
    {

    }

    public function setShow($consult_id)
    {

    }

    //查看
    public function detail($consult_id)
    {
        return $this->fetch();
    }

    //搜索
    public function search()
    {
        $keyword = Request::instance()->param('keyword');
        if (!Cache::has("consult_search_" . $keyword)) {
            $data = new ConsultModel();
            $consult = $data->search($keyword);
        } else {
            $consult = Cache::get("consult_search_" . $keyword);
        }
        $this->assign("list", $consult);
        return $this->fetch('index');
    }

}
