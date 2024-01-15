<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Imageupload extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('player_model');

		$is_logged_in = $this->is_logged_in();
		if (!empty($is_logged_in)) {
			echo '<script type="text/javascript">parent.location.href = "' . site_url($is_logged_in) . '";</script>';
		}
	}

	public function upload_player_bank_image(){
		$this->load->helper('file');
		$data_array = array(
			'get' => $_GET,
			'post' => $_POST,
			'request' => $_REQUEST,
			'file' => $_FILES,
			'sasas' => file_get_contents('php://input'),
		);
		$string = json_encode($data_array,true);
		//if ( ! write_file(FCPATH .'/log_'.time().'.txt', $string)){

		//}
		$username = $_POST['username'];
		$type = $_POST['type'];
		if(!empty($username) && !empty($type)){
			$player_data = $this->player_model->get_player_data_by_username($username);
			if(!empty($player_data)){
				$targetDir = BANKS_PLAYER_USER_BANK_PATH;
				if(file_exists($targetDir)){
					if(!file_exists(BANKS_PLAYER_USER_BANK_PATH.$username)){
						mkdir(BANKS_PLAYER_USER_BANK_PATH.$username);
					}
					if(!file_exists(BANKS_PLAYER_USER_BANK_PATH.$username."/".$type)){
						mkdir(BANKS_PLAYER_USER_BANK_PATH.$username."/".$type);
					}

					$fileBlob = "file-".$type;
					$file = $_FILES[$fileBlob]['tmp_name'][0];
					$fileName  = $_FILES[$fileBlob]['name'][0];
					$fileSize = $_FILES[$fileBlob]['size'][0];
					$fileId = $_POST['fileId'];

					$output = array(
						'fileBlob' => $fileBlob,
						'file' => $file,
						'fileName' => $fileName,
						'fileSize' => $fileSize,
						'fileId' => $fileId,
					);
					if(isset($_FILES[$fileBlob]['name'][0])){
						$_FILES[$fileBlob] = array(
							'name' => $_FILES[$fileBlob]['name'][0],
							'type' => $_FILES[$fileBlob]['type'][0],
							'tmp_name' => $_FILES[$fileBlob]['tmp_name'][0],
							'size' => $_FILES[$fileBlob]['size'][0],
							'error' => $_FILES[$fileBlob]['error'][0],
						);
					}else{
						$_FILES[$fileBlob] = array(
							'name' => $_FILES[$fileBlob]['name'],
							'type' => $_FILES[$fileBlob]['type'],
							'tmp_name' => $_FILES[$fileBlob]['tmp_name'],
							'size' => $_FILES[$fileBlob]['size'],
							'error' => $_FILES[$fileBlob]['error'],
						);
					}
					


					$config['upload_path'] = BANKS_PLAYER_USER_BANK_PATH.$username."/".$type."/";
					$config['max_size'] = BANKS_PLAYER_USER_BANK_SIZE;
					$config['allowed_types'] = 'gif|jpg|jpeg|png';
					$config['overwrite'] = TRUE;
					$this->load->library('upload', $config);
					if(isset($_FILES[$fileBlob]['size']) && $_FILES[$fileBlob]['size'] > 0)
					{
						$config['file_name']  = $_FILES[$fileBlob]['name'];
						$this->upload->initialize($config);
						if( ! $this->upload->do_upload($fileBlob)) 
						{
							$output = array('error' => $this->lang->line('error_invalid_filetype'));
							$allow_to_update = FALSE;
						}else{
							$output = array();
							/*
							$output = array(
								'initialPreview' => BANKS_PLAYER_USER_BANK_SOURCE_PATH.$username."/".$type."/".$_FILES[$fileBlob]['name'],
								'initialPreviewConfig' => array(
									'caption' => $_FILES[$fileBlob]['name'],
									'size' => $_FILES[$fileBlob]['size'],
									'width' => 100,
									'url' => base_url().'imageupload/delete_player_bank_image?username='.$username.'&type='.$type."&image_name=".$_FILES[$fileBlob]['name'],
									//'fileId' => $fileId,
									//'key' => $fileId,
								),
                				'append' => true
							);
							*/
						}
					}else{
						$output = array('error' => $this->lang->line('error_invalid_filetype'));
					}
				}else{
					$output = array('error' => $this->lang->line('error_system_error'));
				}
			}else{
				$output = array('error' => $this->lang->line('error_failed_player_username_not_found'));
			}
		}
		//Output
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($output))
				->_display();
				
		exit();	
	}

	public function delete_player_bank_image(){
		$username = $_GET['username'];
		$type = $_GET['type'];
		$filename = $_GET['image_name'];
		if(file_exists(BANKS_PLAYER_USER_BANK_PATH.$username."/".$type."/".$filename)){
			unlink(BANKS_PLAYER_USER_BANK_PATH.$username."/".$type."/".$filename);
			$output = array();
		}else{
			$output = array('error' => TRUE);
		}

		//Output
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($output))
				->_display();
				
		exit();	
	}

	public function rotate_player_bank_image(){
		//Initial output data
		$json = array(
			'status' => EXIT_ERROR, 
			'msg' => array(
				'general_error' => ''
			),
		);

		$image_src_destination_value = $_POST['image_src_destination_value'];
		$image_rotate_value = $_POST['image_rotate_value'];
		$image_src_destination_array = explode("/",explode("?",$image_src_destination_value)[0]);
		if(!empty($image_rotate_value)){
			$degrees = -90;
			$filename = $_POST['image_src_destination_alt'];
			$type = $image_src_destination_array[sizeof($image_src_destination_array)-2];
			$username = $image_src_destination_array[sizeof($image_src_destination_array)-3];
			$fileType = strtolower(substr($filename, strrpos($filename, '.') + 1));
			if(file_exists(BANKS_PLAYER_USER_BANK_PATH.$username."/".$type."/".$filename)){
				if($fileType == 'png'){
				   header('Content-type: image/png');
				   $source = imagecreatefrompng(BANKS_PLAYER_USER_BANK_PATH.$username."/".$type."/".$filename);
				   $bgColor = imagecolorallocatealpha($source, 255, 255, 255, 127);
				   // Rotate
				   $rotate = imagerotate($source, $degrees, $bgColor);
				   imagesavealpha($rotate, true);
				   imagepng($rotate,BANKS_PLAYER_USER_BANK_PATH.$username."/".$type."/".$filename);

				}

				if($fileType == 'jpg' || $fileType == 'jpeg'){
				   header('Content-type: image/jpeg');
				   $source = imagecreatefromjpeg(BANKS_PLAYER_USER_BANK_PATH.$username."/".$type."/".$filename);
				   // Rotate
				   $rotate = imagerotate($source, $degrees, 0);
				   imagejpeg($rotate,BANKS_PLAYER_USER_BANK_PATH.$username."/".$type."/".$filename);
				}
			}

			sleep(3);
		}
		
		//Output
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($json))
				->_display();
				
		exit();	
	}


	public function testing(){
		$url = 'https://918nba.net/uploads/userbank/ystest/creditcard/%E5%8F%B2%E8%BF%AA%E5%A5%870.png?v=2066';
		ad($url);
		$string = preg_replace('/%u([0-9A-F]+)/', '&#x$1;', $url);
		echo html_entity_decode($string, ENT_COMPAT, 'UTF-8');
	}
}