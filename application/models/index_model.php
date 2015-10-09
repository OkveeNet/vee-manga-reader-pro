<?php
/**
 * @author mr.v
 * @website http://okvee.
 */

class index_model extends CI_Model {


	public $atleast_dat = "10";


	function  __construct() {
		parent::__construct();
		// load model
		$this->load->model(array("genre_model", "manga_model"));
	}
	

	/**
	 * list chapters in ...
	 * @param int $story_id
	 * @return mixed
	 */
	function list_chapters_in($story_id = '') {
		if ( !is_numeric($story_id) ) {return false;}
		$this->db->where("story_id", $story_id);
		$this->db->where("chapter_enable", "1");
		$this->db->order_by("abs(chapter_order)", "desc");
		$query = $this->db->get($this->db->dbprefix("chapters"));
		if ( $query->num_rows() > 0 ) {
			foreach ($query->result() as $row) {
				$output[$row->chapter_id]['chapter_name'] = $row->chapter_name;
				$output[$row->chapter_id]['scanlator'] = $row->scanlator;
				$output[$row->chapter_id]['chapter_uri'] = $row->chapter_uri;
				$output[$row->chapter_id]['chapter_add'] = $row->chapter_add;
				$output[$row->chapter_id]['chapter_update'] = $row->chapter_update;
				$output[$row->chapter_id]['chapter_enable'] = $row->chapter_enable;
			}
			$query->free_result();
			return $output;
		} else {
			$query->free_result();
			return false;
		}
	}
	

	/**
	 * list page in chapter
	 * @param int $story_id
	 * @param int $chapter_id
	 * @return mixed
	 */
	function list_chapter_page_in($story_id = '', $chapter_id = '') {
		if ( !is_numeric($story_id) ) {return false;}
		if ( !is_numeric($chapter_id) ) {return false;}
		$this->db->where("story_id", $story_id);
		$this->db->where("chapter_id", $chapter_id);
		$this->db->order_by("abs(image_order)", "asc");
		$query = $this->db->get($this->db->dbprefix("chapter_images"));
		if ( $query->num_rows() > 0 ) {
			foreach ( $query->result() as $row ) {
				$output[$row->chapter_image_id]['image_file'] = $row->image_file;
				$output[$row->chapter_image_id]['image_order'] = $row->image_order;
			}
			$query->free_result();
			return $output;
		} else {
			$query->free_result();
			return false;
		}
	}


	/**
	 * list directory manga
	 * same as manga_model but list manga in directory, or upto genre
	 * @param string $status
	 * @param string $list_for
	 * @return array
	 */
	function list_directory_manga($status = '1', $list_for = "admin", $genre_uri = '') {
		if ( $list_for != "admin" && $list_for != "front" ) {$list_for = "admin";}
		if ( $genre_uri != null ) {$genre_id = $this->genre_model->show_genre_info("genre_id", '', $genre_uri);}
		$sql = "select * from " . $this->db->dbprefix('story') . " s";
		$sql .= " inner join " . $this->db->dbprefix("genre_story") . " gs";
		$sql .= " on s.story_id = gs.story_id";
		// conditions
		if ( $status !== '1' && $status !== '0' && $status !== 'all' ) {$status = '1';}
		if ( $list_for == "front" ) {$status = "1";}// force set status = 1 because show in front page.
		if ( $status !== 'all' ) {
			$sql .= " where story_enable = " .$this->db->escape($status);
		}
		// genre condition
		if ( isset($genre_id) && $genre_id != null ) {
			$sql .= " and genre_id = " . $genre_id;
		}
		// group for correct count
		$sql .= " group by s.story_id";
		// orders
		$sql .= " order by story_name asc";
		// query for count total
		$query = $this->db->query($sql);
		$total = $query->num_rows();
		$query->free_result();
		// pagination-----------------------------
		$this->load->library('pagination');
		if ( $list_for == "admin" ) {
			$config['base_url'] = base_url().$this->uri->segment(1)."/".$this->uri->segment(2)."?orders=".$this->input->get("orders", true);
			$config['per_page'] = $this->config_model->load('admin_items_per_page');
		} else {
			$config['base_url'] = base_url().$this->uri->segment(1)."/".$this->uri->segment(2)."?orders=".$this->input->get("orders", true);
			$config['per_page'] = $this->config_model->load('front_manga_per_page');
		}
		$config['total_rows'] = $total;
		$config['num_links'] = 5;
		$config['page_query_string'] = true;
		$config['full_tag_open'] = "<div class=\"pagination\">";
		$config['full_tag_close'] = "</div>\n";
		$config['first_link'] = $this->lang->line("admin_first_page");
		$config['last_link'] = $this->lang->line("admin_last_page");
		$this->pagination->initialize($config);
		//you may need this in view if you call this in controller or model --> $this->pagination->create_links();
		$start_item = ($this->input->get("per_page") == null ? "0" : $this->input->get("per_page"));
		// end pagination-----------------------------
		$sql .= " limit ".$start_item.", ".$config['per_page']."";
		$query = $this->db->query($sql);
		if ( $query->num_rows() > 0 ) {
			$output['total_manga'] = $total;
			foreach ( $query->result() as $row ) {
				$output[$row->story_id]['story_name'] = $row->story_name;
				$output[$row->story_id]['story_statinfo'] = $row->story_statinfo;
				$output[$row->story_id]['story_summary'] = $row->story_summary;
				$output[$row->story_id]['story_author'] = $row->story_author;
				$output[$row->story_id]['story_artist'] = $row->story_artist;
				$output[$row->story_id]['story_cover'] = $row->story_cover;
				// cover sizes
					$output[$row->story_id]['cover_small'] = $this->manga_model->set_image_size($row->story_cover, "small");
					$output[$row->story_id]['cover_medium'] = $this->manga_model->set_image_size($row->story_cover, "medium");
					$output[$row->story_id]['cover_large'] = $this->manga_model->set_image_size($row->story_cover, "large");
					$output[$row->story_id]['cover_extralarge'] = $this->manga_model->set_image_size($row->story_cover, "extralarge");
				$output[$row->story_id]['story_uri'] = $row->story_uri;
				$output[$row->story_id]['story_add'] = $row->story_add;
				$output[$row->story_id]['story_update'] = $row->story_update;
				$output[$row->story_id]['story_enable'] = $row->story_enable;
				// count total chapters
				$this->db->where("chapter_enable", "1");
				$this->db->where("story_id", $row->story_id);
				$output[$row->story_id]['total_chapter'] = $this->db->count_all_results($this->db->dbprefix("chapters"));
				// get last chapter
				$this->db->where("chapter_enable", "1");
				$this->db->where("story_id", $row->story_id);
				$this->db->order_by("chapter_add", "desc");
				$query_lc = $this->db->get($this->db->dbprefix("chapters"));
				if ( $query_lc->num_rows() > 0 ) {
					$row_lc = $query_lc->row();
					$output[$row->story_id]['last_chapter'] = $row_lc->chapter_name;
					$output[$row->story_id]['last_chapter_uri'] = $row_lc->chapter_uri;
				}
				$query_lc->free_result();
				// list chapters that update
				/*$sql2 = "select * from " . $this->db->dbprefix("chapters");
				$sql2 .= " where story_id = " . $row->story_id;
				$sql2 .= " and chapter_enable = 1";
				$sql2 .= " order by chapter_add desc";
				$query2 = $this->db->query($sql2);
				if ( $query2->num_rows() > 0 ) {
					foreach ( $query2->result() as $row2 ) {
						$output[$row->story_id]['chapter_list'][$row2->chapter_id]['chapter_name'] = $row2->chapter_name;
						$output[$row->story_id]['chapter_list'][$row2->chapter_id]['chapter_uri'] = $row2->chapter_uri;
					}
				}
				$query2->free_result();*/
			}
			$query->free_result();
			return $output;
		} else {
			$query->free_result();
			return null;
		}
	}


	/**
	 * list front new manga
	 * same as manga_model but order by new and more chapter detail and date new condition
	 * @param string $status
	 * @param string $list_for
	 * @return array
	 */
	function list_front_new_manga($status = '1', $list_for = "admin") {
		if ( $list_for != "admin" && $list_for != "front" ) {$list_for = "admin";}
		$sql = "select * from " . $this->db->dbprefix('story');
		// conditions
		if ( $status !== '1' && $status !== '0' && $status !== 'all' ) {$status = '1';}
		if ( $list_for == "front" ) {$status = "1";}// force set status = 1 because show in front page.
		if ( $status !== 'all' ) {
			$sql .= " where story_enable = " .$this->db->escape($status);
		}
		$sql .= " and (1 or story_update >= DATE_SUB( NOW(), INTERVAL ".$this->atleast_dat." DAY))";
		// orders
		$sql .= " order by story_update desc";
		// query for count total
		$query = $this->db->query($sql);
		$total = $query->num_rows();
		$query->free_result();
		// pagination-----------------------------
		$this->load->library('pagination');
		if ( $list_for == "admin" ) {
			$config['base_url'] = base_url().$this->uri->segment(1)."/".$this->uri->segment(2)."?orders=".$this->input->get("orders", true);
			$config['per_page'] = $this->config_model->load('admin_items_per_page');
		} else {
			$config['base_url'] = base_url().$this->uri->segment(1)."/".$this->uri->segment(2)."?orders=".$this->input->get("orders", true);
			$config['per_page'] = $this->config_model->load('front_manga_per_page');
		}
		$config['total_rows'] = $total;
		$config['num_links'] = 5;
		$config['page_query_string'] = true;
		$config['full_tag_open'] = "<div class=\"pagination\">";
		$config['full_tag_close'] = "</div>\n";
		$config['first_link'] = $this->lang->line("admin_first_page");
		$config['last_link'] = $this->lang->line("admin_last_page");
		$this->pagination->initialize($config);
		//you may need this in view if you call this in controller or model --> $this->pagination->create_links();
		$start_item = ($this->input->get("per_page") == null ? "0" : $this->input->get("per_page"));
		// end pagination-----------------------------
		$sql .= " limit ".$start_item.", ".$config['per_page']."";
		$query = $this->db->query($sql);
		if ( $query->num_rows() > 0 ) {
			$output['total_manga'] = $total;
			foreach ( $query->result() as $row ) {
				$output[$row->story_id]['story_name'] = $row->story_name;
				$output[$row->story_id]['story_statinfo'] = $row->story_statinfo;
				$output[$row->story_id]['story_summary'] = $row->story_summary;
				$output[$row->story_id]['story_author'] = $row->story_author;
				$output[$row->story_id]['story_artist'] = $row->story_artist;
				$output[$row->story_id]['story_cover'] = $row->story_cover;
				// cover sizes
					$output[$row->story_id]['cover_small'] = $this->manga_model->set_image_size($row->story_cover, "small");
					$output[$row->story_id]['cover_medium'] = $this->manga_model->set_image_size($row->story_cover, "medium");
					$output[$row->story_id]['cover_large'] = $this->manga_model->set_image_size($row->story_cover, "large");
					$output[$row->story_id]['cover_extralarge'] = $this->manga_model->set_image_size($row->story_cover, "extralarge");
				$output[$row->story_id]['story_uri'] = $row->story_uri;
				$output[$row->story_id]['story_add'] = $row->story_add;
				$output[$row->story_id]['story_update'] = $row->story_update;
				$output[$row->story_id]['story_enable'] = $row->story_enable;
				// list chapters that update
				$sql2 = "select * from " . $this->db->dbprefix("chapters");
				$sql2 .= " where story_id = " . $row->story_id;
				$sql2 .= " and chapter_enable = 1";
				$sql2 .= " and chapter_update >= DATE_SUB( NOW(), INTERVAL ".$this->atleast_dat." DAY)";
				$sql2 .= " order by chapter_add desc";
				$query2 = $this->db->query($sql2);
				if ( $query2->num_rows() > 0 ) {
					foreach ( $query2->result() as $row2 ) {
						$output[$row->story_id]['chapter_list'][$row2->chapter_id]['chapter_name'] = $row2->chapter_name;
						$output[$row->story_id]['chapter_list'][$row2->chapter_id]['chapter_uri'] = $row2->chapter_uri;
					}
				}
				$query2->free_result();
			}
			$query->free_result();
			return $output;
		} else {
			$query->free_result();
			return null;
		}
	}


}

/* eof */