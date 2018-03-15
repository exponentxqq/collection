<?php
/**
 * Created by PhpStorm.
 * User: xqq-exponent
 * file: Functions.class.php
 * Date: 2018/1/13
 * Time: 11:44
 */

namespace Extend\Base;


use Extend\Model\ImgModel;

trait Functions
{
    /**
     * @return mixed
     * @throws ModelException
     */
    public function upImg(){
        if($this->data['img_id'] && $_FILES['upPic']['size'] <= 0){
            return $this->data['img_id'];
        }
        if($_FILES['upPic']['size'] <= 0){
            throw new ModelException('请上传图片！');
        }
        $img = new ImgModel();
        $img_info = $img->up($_FILES['upPic']);
        if(!$img_info){
            throw new ModelException('图片上传失败！');
        }

        return $img_info['id'];
    }

    /**
     * @return mixed
     * @throws ModelException
     */
    public function upImgAndReturnUrl(){
        if($this->data['img_id'] && $_FILES['upPic']['size'] <= 0){
            return $this->data['img_id'];
        }
        if($_FILES['upPic']['size'] <= 0){
            throw new ModelException('请上传图片！');
        }
        $img = new ImgModel();
        $img_info = $img->up($_FILES['upPic']);
        if(!$img_info){
            throw new ModelException('图片上传失败！');
        }

        return $url =  C('NEW_IMG_PATH_PREFIX') . $img_info['path'];
    }
}