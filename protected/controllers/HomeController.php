<?php

class HomeController extends Controller
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

		$date = date('Y-m-d');
		$date1 = $date.' 23:59:59';
		$this->g_guest = Yii::app()->admin->isGuest;
		$this->g_username = Yii::app()->admin->name;
		$criteria = new CDbCriteria;
		$criteria->condition = 'source ="qq" and date >= "'.$date.'" and date <= "'.$date1.'"';
		$criteria->order = 'date desc';
		
		$qq = Music::model()->findAll($criteria);

                $criteria = new CDbCriteria;
                $criteria->condition = 'source ="xiami" and date >= "'.$date.'" and date <= "'.$date1.'"';

                $criteria->order = 'date desc';

                $xiami = Music::model()->findAll($criteria);
		$other_comment = array();
		foreach($xiami as $t)
		{
			$criteria = new CDbCriteria;
			$criteria->condition = "album_id = $t->id";
			$criteria->limit = 1;
			$result = Other_comment::model()->findAll($criteria);
			if($result)
			{
				$other_comment[$t->id]=$result[0]->comment;
			}
		
		}
	//	$other_comment = Other_comment::model()->findAll();
		
			
			

                $criteria = new CDbCriteria;
                $criteria->condition = 'source ="163" and date >= "'.$date.'" and date <= "'.$date1.'"';


                $criteria->order = 'date desc';

                $net = Music::model()->findAll($criteria);
                foreach($net as $t)
                {
                        $criteria = new CDbCriteria;
                        $criteria->condition = "album_id = $t->id";
                        $criteria->limit = 1;
                        $result = Other_comment::model()->findAll($criteria);
                        if($result)
                        {
                               $other_comment[$t->id]=$result[0]->comment;
                        }
                }
		
		

			
			
                $criteria = new CDbCriteria;
		$criteria->limit = 100;
		$criteria->condition = "judge=1";
		$criteria->order = 'date desc';
		$r = Recommend::model()->findAll($criteria);	

                $this->render('index', array('qq'=>$qq, 'xiami'=>$xiami, 'net'=>$net, 'recommend'=>$r, 'other_comment'=>$other_comment));
	
        }


	public function actionVote()
	{

		echo Yii::app()->admin->isGuest;
	        if(Yii::app()->admin->isGuest){
        		   $this->redirect(Yii::app()->admin->loginUrl);
	        }
		else
		{

                        $ip = Yii::app()->request->userHostAddress;
                        $user = Yii::app()->admin->name;

			$id = $_GET['id'];


			$criteria = new CDbCriteria;
			$criteria->condition = "(ip='$ip' or user='$user') and new =$id ";
			$count = Record::model()->count($criteria);
			if($count == 0)
			{
				
			

				$score = $_GET['score'];
				$score += 1;
				Music::model()->updateByPk($id, array('vote'=>$score));


	                        $record = new Record;
        	                $record->ip = $ip;
                	        $record->user = $user;
                        	$record->date =  date('Y-m-d H:i:s');
	                        $record->new = $id;
        	                $record->save();
			}


			$this->redirect(Yii::app()->request->urlReferrer);
		}
	}


        public function actionVote_recommend()
        {

                echo Yii::app()->admin->isGuest;
                if(Yii::app()->admin->isGuest){
                           $this->redirect(Yii::app()->admin->loginUrl);
                }
                else
                {
                        $ip = Yii::app()->request->userHostAddress;
                        $user = Yii::app()->admin->name;
			
                        $id = $_GET['id'];


                        $criteria = new CDbCriteria;
                        $criteria->condition = "(ip='$ip' or user='$user') and recommend =$id ";
                        $count = Record::model()->count($criteria);
                        if($count == 0)
                        {



	                        $score = $_GET['score'];
        	                $score += 1;
                	        Recommend::model()->updateByPk($id, array('vote'=>$score));

				$record = new Record;
				$record->ip = $ip;
				$record->user = $user;
				$record->date =  date('Y-m-d H:i:s');
				$record->recommend = $id;
				$record->save();
			}
                        $this->redirect(Yii::app()->request->urlReferrer);
                }
        }


	
	public function actionSubmit()
	{
		$msg = '';
		if(isset($_POST['msg']))
		{
			$msg=$_POST['msg'];
		}

		$html = '';
		
		if($msg != '')
		{

			$m = new Chat;
			$m->ip = Yii::app()->request->userHostAddress;

                        if(Yii::app()->admin->isGuest)
                        {
				$m->user = '匿名';

			}
			else
			{
				$m->user = Yii::app()->admin->name;
			}
			$m->date =  date('Y-m-d H:i:s');
			$m->content = $msg;
			$m->save();
			
			$html = $this->actionLoadMsg();
			
		}
		echo $html;
	}

	public function get_item($date, $name, $content)
	{
		return "<div class='item'><span class='item_date'>$date</span><span class='item_ip'> $name </span><div class='item_content'>$content</div></div>";

	}	
        public function actionLoadMsg()
        {


		$id = 0;
		if( isset(Yii::app()->request->cookies['cookie_id']))
		{
			$id =  Yii::app()->request->cookies['cookie_id']->value;	
		}	
                $criteria = new CDbCriteria;
		$criteria->condition = "id > $id";
                $criteria->order = "date asc";
                $chat = Chat::model()->findAll($criteria);

                $html = '';
                foreach($chat as $item)
                {
			$id = $item->id;	
			$html.=$this->get_item($item->date, $item->user, $item->content);

	        }


		Yii::app()->request->cookies['cookie_id'] = new CHttpCookie('cookie_id', $id);
                echo $html;
        }

	public function actionRecommend()
	{
	
                if(Yii::app()->admin->isGuest)
                {
                           $this->redirect(Yii::app()->admin->loginUrl);
                }
                else
                {
	
			$this->render('recommend');	
		}
	}

        public function actionSubmit_music()
        {


			$song = $_POST['song'];
			$singer = $_POST['singer'];
			$url = $_POST['url'];
			if($song != '' && $singer != '' && $url != '')
			{


	                	$r = new Recommend;
	                        $r->ip = Yii::app()->request->userHostAddress;
        	                $r->user = Yii::app()->admin->name;
                	        $r->date =  $date = date('Y-m-d H:i:s');

	        	        $r->song = $song;
		                $r->singer = $singer;
        		        $r->url = $url;
	                	$r->save();


			}
			$this->redirect("/");
        }





}
