<?php

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
         #    $count = $_GET['count'];
	     $criteria = new CDbCriteria;
             $criteria->condition = "tag='$tag' ";
	     if($lastId != -1)
    	     {
		$criteria->condition = "tag='$tag' and id < '$lastId'";
	     }
	     $criteria->order = "date DESC";
	     $criteria->limit = $limit;
	     $images = Images::model()->findAll($criteria);
	     $data   = array_map(create_function('$m','return $m->getAttributes(array(\'id\', \'category\', \'imageUrl\', \'date\', \'slug\', \'likeCount\'));'),$images);
	     $this->layout=false;
		header('Content-type: application/json');
		echo json_encode($data);
		Yii::app()->end(); 
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
}
