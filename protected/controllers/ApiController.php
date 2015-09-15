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
         #    $count = $_GET['count'];
	     $criteria = new CDbCriteria;
             $criteria->condition = "tag='$tag' ";


	     $images = Images::model()->findAll($criteria);
	     $data   = array_map(create_function('$m','return $m->getAttributes(array(\'id\', \'category\', \'imageUrl\', \'date\', \'slug\'));'),$images);
	     #echo json_encode($data);	
		$this->layout=false;
		header('Content-type: application/json');
		echo json_encode($data);
		Yii::app()->end(); 
}
}
