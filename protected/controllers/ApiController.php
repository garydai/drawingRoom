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

        #     $page = $_GET['song'];
         #    $count = $_GET['count'];
	     $images = Images::model()->findAll();
	     $data   = array_map(create_function('$m','return $m->getAttributes(array(\'id\', \'category\', \'imageUrl\'));'),$images);
	     echo json_encode($data);	
	}
}
