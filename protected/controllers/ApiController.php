<?php
require_once '/home/admin/vendor/autoload.php';
 use Qiniu\Auth;

    // 引入上传类
 use Qiniu\Storage\UploadManager;


class ApiController extends Controller
{
	public $layout='column1';

	public $g_guest;
	
	public $g_username;	
	
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}


	public function actionGetImage()
	{

             $tag = $_GET['tag'];
	     $limit = $_GET['limit'];
	     $lastId = $_GET['lastId'];
	     $deviceId = $_GET['deviceId'];
         #    $count = $_GET['count'];
	     $criteria = new CDbCriteria;
             $condition = "tag='$tag' ";
	     if($lastId != -1)
    	     {
		$condition = "tag='$tag' and id < '$lastId'";
	     }
	     $condition.= " order by date DESC limit $limit";
	    # $criteria->order = "date DESC";
	    # $criteria->limit = $limit;
	    # $images = Images::model()->findAll($criteria)


 	     $query = "select  * from  images where  ".$condition;
  	     $result = Yii::app()->db->createCommand($query)->queryAll();
	     $query2 = "select bLike, imageId from favoriteImage where deviceId = '{$deviceId}'";
	     $result2 = Yii::app()->db->createCommand($query2)->queryAll();
	     foreach($result as &$json)
	     {
		$json['bLike'] = 0;
		$privateDownloadUrl = $this->genQiniuImageUrl($json['imageUrl']);
		//echo ($privateDownloadUrl);
		$json['imageUrl'] = $privateDownloadUrl;


		foreach($result2 as $item)
               		if($item['imageId'] == $json['id'])
				{
					$json['bLike'] = 1;
					break;
				}
	     }
             $this->layout=false;
             header('Content-type: application/json');
             echo json_encode($result);
     
    	     Yii::app()->end(); 
	}

	public function genQiniuImageUrl($key)
	{
		
                $bucket_domain = '7xlcoi.com1.z0.glb.clouddn.com'; #可以在空间设置的域名设置中找到
                $key = str_replace(array("\r\n", "\r", "\n", ''), "", $key);
                $base_url = 'http://'.$bucket_domain.'/'.$key;
                $access_key = 'IfQYsH_aGxIgmmwS5zumpuTwzOV39FhA4VTC8K57';
                $secret_key = 'zY4kEBSxJfMhgDFFJiPBQRN2xgOjgKvHmke8ewJC';
                $auth  = new Auth($access_key, $secret_key);
                $privateDownloadUrl = $auth->privateDownloadUrl($base_url);
		return $privateDownloadUrl;
	}

	public function actionLikeImage()
	{
		$imageId = $_GET['imageId'];
		$deviceId = $_GET['deviceId'];	
		$criteria = new CDbCriteria;
		$criteria->condition = "imageId='$imageId' and deviceId = '$deviceId'";
		$result = FavoriteImage::model()->find($criteria);
		$liked = 0;
		//存在记录
		if(count($result))
		{
			//被点过
			if($result->bLike == 1)
			{
				FavoriteImage::model()->updateByPk($result->id, array('bLike' => 0));
				Images::model()->updateCounters(array('likeCount' =>-1), 'id=:id', array(':id' =>$imageId));
				$liked = 1;	
			}
			//被取消过
			else
			{
				FavoriteImage::model()->updateByPk($result->id, array('bLike' => 1));
				Images::model()->updateCounters(array('likeCount' =>1), 'id=:id', array(':id' =>$imageId));
			}
		}
		else
		{
			$model = new FavoriteImage();
			$model->imageId = $imageId;
			$model->deviceId = $deviceId;	
			$model->save();
			Images::model()->updateCounters(array('likeCount' =>1), 'id=:id', array(':id' =>$imageId));
		}
		$data = array( 'liked' => $liked );	
		$this->layout=false;
                header('Content-type: application/json');
                echo json_encode($data);
                Yii::app()->end();
	}
	public function actionGetFavoriteImage()
	{

             $limit = $_GET['limit'];
             $lastId = $_GET['lastId'];
	     $deviceId = $_GET['deviceId'];
         #    $count = $_GET['count'];
             #$criteria = new CDbCriteria;
             #$criteria->condition = "deviceId='$deviceId' ";
             #$criteria->limit = $limit;

             $condition = "";
             if($lastId != -1)
             {
                $condition = " and favoriteImage.id < $lastId";
             }
             $condition.= "order by date DESC limit $limit";

	    $query = "select  * from favoriteImage, images where favoriteImage.imageId=images.id and favoriteImage.deviceId = '$deviceId'".$condition;
            $result = Yii::app()->db->createCommand($query)->queryAll();
            foreach($result as &$json)
            {
                $privateDownloadUrl = $this->genQiniuImageUrl($json['imageUrl']);
                $json['imageUrl'] = $privateDownloadUrl;
	    }   
	    $this->layout=false;
            header('Content-type: application/json');
            echo json_encode($result);
            Yii::app()->end();
	}
}
