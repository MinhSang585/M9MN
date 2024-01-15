<?php
class Blog_model extends CI_Model {
	protected $table_blogs = 'blogs';
	protected $table_blogs_category = 'blogs_category';
	protected $table_blogs_lang = 'blogs_lang';
	protected $table_blog_category_lang = 'blogs_category_lang';
	protected $table_blog_category = 'blogs_category';


	public function get_blogs_category_list(){
		$result = NULL;
		$query = $this
				->db
				->select('blog_category_id, blog_category_name')
				->where('active', STATUS_ACTIVE)
				->order_by('blog_category_id','ASC')
				->get($this->table_blogs_category);

		if($query->num_rows() > 0)
		{
			$result = $query->result_array();  
		}
		
		$query->free_result();
		
		return $result;
	}

	public function get_blogs_category_data($blog_category_id = NULL){
		$result = NULL;
		$query = $this
				->db
				->where('blog_category_id',$blog_category_id)
				->where('active', STATUS_ACTIVE)
				->limit(1)
				->get($this->table_blogs_category);

		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}

	public function get_blog_data($blog_id = NULL){
		$result = NULL;
		$query = $this
				->db
				->where('blog_id',$blog_id)
				->limit(1)
				->get($this->table_blogs);

		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}

	public function add_blog($category_id = NULL ,$category_name = NULL){
		$domain = $this->input->post('domain', TRUE);
		$domain_array = array_filter(explode('#',strtolower($domain)));
		$DBdata = array(
			'blog_name' => $this->input->post('blog_name', TRUE),
			'blog_display' => $this->input->post('blog_display', TRUE),
			'blog_category_id' => $category_id,
			'blog_category_name' => $category_name,
			'blog_pathname' => $this->input->post('blog_pathname', TRUE),
			'seo_header' => html_entity_decode($_POST['seo_header']),
			'seo_title' => $this->input->post('seo_title', TRUE),
			'seo_og_title' => $this->input->post('seo_og_title', TRUE),
			'seo_twitter_title' => $this->input->post('seo_twitter_title', TRUE),
			'seo_meta_keywords' => $this->input->post('seo_meta_keywords', TRUE),
			'seo_og_keywords' => $this->input->post('seo_og_keywords', TRUE),
			'seo_twitter_keywords' => $this->input->post('seo_twitter_keywords', TRUE),
			'seo_description' => $this->input->post('seo_description', TRUE),
			'seo_og_description' => $this->input->post('seo_og_description', TRUE),
			'seo_twitter_description' => $this->input->post('seo_twitter_description', TRUE),
			'seo_og_url' => $this->input->post('seo_og_url', TRUE),
			'seo_canonical' => $this->input->post('seo_canonical', TRUE),
			'blog_sequence' => $this->input->post('blog_sequence', TRUE),
			'is_top' => (($this->input->post('is_top', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'domain' => ((!empty($domain_array)) ? ",".implode(',',$domain_array)."," : ""),
			'logo_image' => $this->input->post('logo_image', TRUE),
			'og_image' => $this->input->post('og_image', TRUE),
			'twitter_image' => $this->input->post('twitter_image', TRUE),
			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'created_by' => $this->session->userdata('username'),
			'created_date' => time()
		);

		$this->db->insert($this->table_blogs, $DBdata);
		$DBdata['blog_id'] = $this->db->insert_id();
		return $DBdata;
	}

	public function update_blog($blog_id = NULL, $category_id = NULL ,$category_name = NULL){
		$domain = $this->input->post('domain', TRUE);
		$domain_array = array_filter(explode('#',strtolower($domain)));
		$DBdata = array(
			'blog_name' => $this->input->post('blog_name', TRUE),
			'blog_display' => $this->input->post('blog_display', TRUE),
			'blog_category_id' => $category_id,
			'blog_category_name' => $category_name,
			'blog_pathname' => $this->input->post('blog_pathname', TRUE),
			'seo_header' => html_entity_decode($_POST['seo_header']),
			'seo_title' => $this->input->post('seo_title', TRUE),
			'seo_og_title' => $this->input->post('seo_og_title', TRUE),
			'seo_twitter_title' => $this->input->post('seo_twitter_title', TRUE),
			'seo_meta_keywords' => $this->input->post('seo_meta_keywords', TRUE),
			'seo_og_keywords' => $this->input->post('seo_og_keywords', TRUE),
			'seo_twitter_keywords' => $this->input->post('seo_twitter_keywords', TRUE),
			'seo_description' => $this->input->post('seo_description', TRUE),
			'seo_og_description' => $this->input->post('seo_og_description', TRUE),
			'seo_twitter_description' => $this->input->post('seo_twitter_description', TRUE),
			'seo_canonical' => $this->input->post('seo_canonical', TRUE),
			'blog_sequence' => $this->input->post('blog_sequence', TRUE),
			'seo_og_url' => $this->input->post('seo_og_url', TRUE),
			'is_top' => (($this->input->post('is_top', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'domain' => ((!empty($domain_array)) ? ",".implode(',',$domain_array)."," : ""),
			'logo_image' => $this->input->post('logo_image', TRUE),
			'og_image' => $this->input->post('og_image', TRUE),
			'twitter_image' => $this->input->post('twitter_image', TRUE),
			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);
		
		$this->db->where('blog_id', $blog_id);
		$this->db->limit(1);
		$this->db->update($this->table_blogs, $DBdata);
		$DBdata['blog_id'] = $blog_id;
		return $DBdata;
	}

	public function get_promotion_lang_data($id = NULL){
		$result = NULL;
		$query = $this
				->db
				->where('promotion_id', $id)
				->get($this->table_promotion_lang);

		if($query->num_rows() > 0)
		{
			$result_query = $query->result_array();
			foreach($result_query as $row){
				$result[$row['language_id']] = $row;
			}
		}
		
		$query->free_result();
		
		return $result;
	}

	public function get_blog_lang_data($id = NULL){
		$result = NULL;

		$query = $this
				->db
				->where('blog_id', $id)
				->get($this->table_blogs_lang);

		if($query->num_rows() > 0)
		{
			$result_query = $query->result_array();
			foreach($result_query as $row){
				$result[$row['language_id']] = $row;
			}
		}
		
		$query->free_result();
		
		return $result;
	}

	public function delete_blog($id = NULL)
	{	
		$this->db->where('blog_id', $id);
		$this->db->delete($this->table_blogs);
	}

	public function delete_blog_lang($blog_id){
		$this->db->where('blog_id',  $blog_id);
		$this->db->delete($this->table_blogs_lang);
	}

	public function add_blog_content($blog_id=NULL, $language_id = NULL){
		$DBdata = array(
			'blog_web_title' => $this->input->post('blog_web_title_'.$language_id, TRUE),
			'blog_mobile_title' => $this->input->post('blog_mobile_title_'.$language_id, TRUE),
			'blog_web_sub_title' => $this->input->post('blog_web_sub_title_'.$language_id, TRUE),
			'blog_mobile_sub_title' => $this->input->post('blog_mobile_sub_title_'.$language_id, TRUE),
			'blog_web_content' => html_entity_decode($_POST['blog_web_content_'.$language_id]),
			'blog_mobile_content' => html_entity_decode($_POST['blog_mobile_content_'.$language_id]),
			'blog_id' => $blog_id,
			'language_id' => $language_id,
		);
		
		$this->db->insert($this->table_blogs_lang, $DBdata);
		return $DBdata;
	}

	public function update_blog_content($blog_id=NULL, $language_id = NULL){
		$DBdata = array(
			'blog_web_title' => $this->input->post('blog_web_title_'.$language_id, TRUE),
			'blog_mobile_title' => $this->input->post('blog_mobile_title_'.$language_id, TRUE),
			'blog_web_sub_title' => $this->input->post('blog_web_sub_title_'.$language_id, TRUE),
			'blog_mobile_sub_title' => $this->input->post('blog_mobile_sub_title_'.$language_id, TRUE),
			'blog_web_content' => html_entity_decode($_POST['blog_web_content_'.$language_id]),
			'blog_mobile_content' => html_entity_decode($_POST['blog_mobile_content_'.$language_id]),
		);

		$this->db->where('blog_id', $blog_id);
		$this->db->where('language_id', $language_id);
		$this->db->limit(1);
		$this->db->update($this->table_blogs_lang, $DBdata);
		$DBdata['blog_id'] = $blog_id;
		$DBdata['language_id'] = $language_id;
		return $DBdata;
	}

	public function get_blog_category_data($blog_category_id = NULL){
		$result = NULL;
		$query = $this
				->db
				->where('blog_category_id',$blog_category_id)
				->limit(1)
				->get($this->table_blog_category);

		if($query->num_rows() > 0)
		{
			$result = $query->row_array();  
		}
		
		$query->free_result();
		
		return $result;
	}

	public function add_blog_category(){
		$DBdata = array(
			'blog_category_name' => $this->input->post('blog_category_name', TRUE),
			'blog_category_pathname' => $this->input->post('blog_category_pathname', TRUE),
			'blog_category_header' => html_entity_decode($_POST['blog_category_header']),
			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'created_by' => $this->session->userdata('username'),
			'created_date' => time()
		);
		
		$this->db->insert($this->table_blog_category, $DBdata);
		
		$DBdata['blog_category_id'] = $this->db->insert_id();
		
		return $DBdata;
	}

	public function update_blog_category($id = NULL){
		$DBdata = array(
			'blog_category_name' => $this->input->post('blog_category_name', TRUE),
			'blog_category_pathname' => $this->input->post('blog_category_pathname', TRUE),
			'blog_category_header' => html_entity_decode($_POST['blog_category_header']),
			'active' => (($this->input->post('active', TRUE) == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_INACTIVE),
			'updated_by' => $this->session->userdata('username'),
			'updated_date' => time()
		);
		
		$this->db->where('blog_category_id', $id);
		$this->db->limit(1);
		$this->db->update($this->table_blog_category, $DBdata);
		
		$DBdata['blog_category_id'] = $id;
		
		return $DBdata;
	}

	public function delete_blog_category($id = NULL)
	{	
		$this->db->where('blog_category_id', $id);
		$this->db->delete($this->table_blog_category);
	}

	public function add_blog_category_lang($blog_category_id = NULL, $language_id = NULL){
		$DBdata = array(
			'blog_category_name' => $this->input->post('blog_category_name-'.$language_id, TRUE),
			'blog_category_id' => $blog_category_id,
			'language_id' => $language_id
		);
		$this->db->insert($this->table_blog_category_lang, $DBdata);
	}

	public function delete_blog_category_lang($id = NULL)
	{	
		$this->db->where('blog_category_id', $id);
		$this->db->delete($this->table_blog_category_lang);
	}

	public function get_blog_category_lang_data($id = NULL){
		$result = NULL;
		
		$query = $this
				->db
				->select('blog_category_name, language_id')
				->where('blog_category_id', $id)
				->get($this->table_blog_category_lang);
		
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$result[$row->language_id] = $row->blog_category_name;
			}	
		}
		
		$query->free_result();
		
		return $result;
	}
}