<?php
use Phalcon\Mvc\View;

class EventController extends ControllerBase {

   public function beforeExecuteRoute(){ // function ที่ทำงานก่อนเริ่มการทำงานของระบบทั้งระบบ
	  $this->checkAuthen();
   }

  //  public function initialize()
  //  {
  //    parent::initialize();
  //  $this->view->disableLevel(View::LEVEL_MAIN_LAYOUT);
  //  $this->view->setTemplateAfter('p');
   
  //  }
   
   public function showAction(){
    if($this->request->isPost()){
    }
  }
    public function indexAction(){
        if($this->request->isPost()){

	$eventname = trim($this->request->getPost('event_name'));
       $eventdate = trim($this->request->getPost('event_date')); // รับค่าจาก form     
       $eventdetail = trim($this->request->getPost('event_detail')); // รับค่าจาก form

 $photoUpdate='';
       if($this->request->hasFiles() == true){

//$photoUpdate= trim($this->request->getPost('event_detail'));

         $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
         $uploads = $this->request->getUploadedFiles();
         $isUploaded = false;			
         foreach($uploads as $upload){
           if(in_array($upload->gettype(), $allowed)){					
             $photoName=md5(uniqid(rand(), true)).strtolower($upload->getname());
             $path = '../public/event/'.$photoName;
             ($upload->moveTo($path)) ? $isUploaded = true : $isUploaded = false;
           }
         }
         if($isUploaded)  $photoUpdate=$photoName ;
         }else    
         die('You must choose at least one file to send. Please try again.');
     
         
	  $eventObj = new Event();
      $eventObj->event_name=$eventname;
      $eventObj->event_date=$eventdate;
      $eventObj->event_detail=$eventdetail;
      $eventObj->event_picture=$photoUpdate;
      $eventObj->save();

      $this->response->redirect('event/show');

    }
   }
 

 public function editAction($evnum){

  if($this->request->isPost()){


    $photoUpdate='';
	   if($this->request->hasFiles() == true){
		   	$allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
			$uploads = $this->request->getUploadedFiles();
		
				$isUploaded = false;			
				foreach($uploads as $upload){
					if(in_array($upload->gettype(), $allowed)){					
					 $photoName=md5(uniqid(rand(), true)).strtolower($upload->getname());
					 $path = '../public/event/'.$photoName;
					 ($upload->moveTo($path)) ? $isUploaded = true : $isUploaded = false;
					}
				}
							 
				if($isUploaded)  $photoUpdate=$photoName ;
		}

      // $eventnum = trim($this->request->getPost('event_number')); 
      // $eventnum = $evnum;
      $eventlist = Event::findFirst($evnum);

      if(isset($eventlist->event_name)) echo $eventlist->event_name;

      // $eventnum = $eventlist->event_number;
      // $eventname = $eventlist->event_name;
      // $eventdate = $eventlist->event_date;
      // $eventdetail = $eventlist->event_detail;


      $eventname = trim($this->request->getPost('event_name')); 
      $eventdate = trim($this->request->getPost('event_date')); // รับค่าจาก form     
      $eventdetail = trim($this->request->getPost('event_detail')); // รับค่าจาก form

      $eventlist->event_name=$eventname;
      $eventlist->event_date=$eventdate;
      $eventlist->event_detail=$eventdetail;
      $eventlist->event_picture=$photoUpdate;
      $eventlist->save();
      $this->response->redirect('event/show');
    }else
    $eventname=$this->session->get('memberAuthen');

    $event=Event::findFirst($evnum);
    $this->view->event=$event;
  }

  public function deleteAction($evnum){

    if($this->request->isPost()){
     
      $eventdel = Event::findFirst($evnum);

      $eventnum = $eventdel->event_number;

      // $eventdel->event_name=$eventname;
      // $eventdel->event_date=$eventdate;
      // $eventdel->event_detail=$eventdetail;
      // $eventdel->event_picture=$eventphoto;
      $eventdel->delete();
      $this->response->redirect('event/show');
      }
    else
    $eventname=$this->session->get('memberAuthen');
    }

}

?>
