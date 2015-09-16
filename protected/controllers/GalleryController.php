<?php

class GalleryController extends Controller
{
	public $layout='column2';

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

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$headers="From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}


	
        public function actionIndex()
        {


		$this->render("index");	
        }

	public function actionSumiao()
	{

		$model = new Images;

		$this->render("index");
	}


        public function actionAdd()
        {

               # $area = Area::model()->findAll();
               # $this->render('add',array('area'=>$area));
		$this->render('add');
        }


       	public function actionGet_data()
        {
        //      var_dump($_POST);
                $count = Images::model()->count();
                $criteria = new CDbCriteria;
                if($_POST['searchPhrase'] !='')
                {
                        $criteria->condition='name like '.'"%'.$_POST['searchPhrase'].'%" ';
                }
                if(isset($_POST['sort']['id'] ))
                {

                        $criteria->order = " id  {$_POST['sort']['id']} ";
                }
                else if(isset($_POST['sort']['port']))
                {
                         $criteria->order = "name {$_POST['sort']['port']} ";
                }
                $criteria->limit = $_POST['rowCount'];
                $criteria->offset= (intval($_POST['current']) -1)*$_POST['rowCount'];

                $model = Images::model()->findAll($criteria);
        //      var_dump($model);
                $arr = array();
                foreach($model as $o)
                {
			if($o->tag == 11)
			{
				$t = '素描';
			}
			else if($o->tag == 10)
				$t = '色彩';
			else $t = '速写';
                        $json = array('id'=>intval($o->id), 'tag'=>$t);
                        array_push($arr, $json);

                }
        //      var_dump( $arr);        
                echo json_encode(array('rowCount'=>$_POST['rowCount'], 'current'=>$_POST['current'], 'rows'=>$arr, 'total'=>$count));

        }
	
	//增加图片
        public function actionAddPic()
        {
//                if(Yii::app()->request->isAjaxRequest)
                {


                        $image = new Images;
                        $image->tag = $_POST['tag'];
			$image->date = $_POST['date'];
			$image->slug = $_POST['slug'];
                        if(isset($_POST['source']))
                        {

                                /*
                                $t = explode(',', $_POST['source']);
                                $id = '';
                                foreach($t as $a)
                                {
                                        if($a != '')
                                        {
                                                $source = new Source;
                                                $source->source = $a;
                                                $source->save();
                                                $id .= $source->id.','; 
                                        }
                                
                                }

                                */
                                $image->imageUrl = $_POST['source'];

                        }
                        if(isset($_POST['thumb']))
                        {
                                /*
                                $t = explode(',', $_POST['thumb']);
                                $id = '';
                                foreach($t as $a)
                                {
                                        if($a != '')
                                        {
                                                $thumb = new Thumb;
                                                $thumb->thumb = $a;
                                                $thumb->save();
                                                $id .= $thumb->id.',';  
                                        }
                                }
                                */
                                $image->thumb = $_POST['thumb'];
                        }
                        $image->save();
                        echo 1;
                }

	}

}
