<?php

class CommentController extends Controller
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

		if(isset($_GET['nid']))
		{
			$id = $_GET['nid'];
			$title = Music::model()->findByPk($id);
			$criteria = new CDbCriteria;
			$criteria->condition = "new=$id";
			$comments = Comment::model()->findAll($criteria);
			
		}			
		else if(isset($_GET['rid']))
		{
			$id = $_GET['rid'];
			$title = Recommend::model()->findByPk($id);


                        $criteria = new CDbCriteria;
                        $criteria->condition = "recommend=$id";
                        $comments = Comment::model()->findAll($criteria);



		}
		$this->render('index', array('comments'=>$comments, 'title'=>$title));
	
        }


	public function actionAdd_comment()
	{
	
                if(Yii::app()->admin->isGuest)
		{
                           $this->redirect(Yii::app()->admin->loginUrl);
                }
                else
                {



                        $ip = Yii::app()->request->userHostAddress;
                        $user = Yii::app()->admin->name;

			$content = $_POST['content'];
			$comment = new Comment;
			$comment->user = $user;
			$comment->ip = $ip;
                        $comment->date =  date('Y-m-d H:i:s');
			if($_POST['type'] == 'new')
			{
	                        $comment->new = $_POST['id'];
			}
			else 
			{
				$comment->recommend = $_POST['id'];
			}
			$comment->content = $content;
                        $comment->save();
			#echo 11;
                        #如果删除评论，music数据库的comment也要减1
                        if($_POST['type'] == 'new')
                        {

                        	$connection=Yii::app()->db;
	                        $sql = "update music set comment=comment+1 where id = ".$_POST['id'];
        	                $command=$connection->createCommand($sql);
				$command->execute();
			}
			else
			{
                                $connection=Yii::app()->db;
                                $sql = "update recommend set comment=comment+1 where id = ".$_POST['id'];
                                $command=$connection->createCommand($sql);
				$command->execute();
	
			}
			$this->redirect(Yii::app()->request->urlReferrer);

		}
		
	}

}
