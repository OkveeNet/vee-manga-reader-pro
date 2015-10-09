<?php
/**
 * @author vee
 * @copyright http://www.okvee.net
 */

class manga_model extends CI_Model {

	
	function __construct() {
		parent::__construct();
	}// __construct
	
	
	/**
	 * add_manga
	 * @param array $data
	 * @return mixed 
	 */
	function add_manga($data = '') {
		if ( $data == null ) {return "Form empty.";}
		// find nodup uri
		$data['story_uri'] = $this->nodup_uri($data['story_uri']);
		// load upload library
		$this->load->library('upload');
		$config['upload_path'] = dirname(BASEPATH)."/".$this->config_model->load("manga_dir").$data['story_uri'];
		$config['allowed_types'] = 'jpg|jpeg|gif|png';
		$config['encrypt_name'] = true;
		$config['remove_spaces'] = true;
		$this->upload->initialize($config);
		// check if target folder exists
		if ( ! file_exists($config['upload_path']) ) {
			$old = umask(0);
			mkdir($config['upload_path'], 0777, true);
			umask($old);
		}
		// start upload
		if ( ! $this->upload->do_upload("story_cover") ) {
			return $this->upload->display_errors();
		} else {
			$filedata = $this->upload->data();
			$file_ext = $filedata['file_ext'];
			$file_size = $filedata['file_size'];
			$file_name = $filedata['file_name'];
			$raw_name = $filedata['raw_name'];// file_name with no ext \\
		}
		// resize image ----------------------------------------------------------------------------#
		$this->load->library("vimage", $filedata['full_path']);
		// tiny image
		$new_width ='100';
		$new_height = '100';
		$this->vimage->resize_ratio($new_width, $new_height);
		$this->vimage->save('', $filedata['file_path'].$raw_name."-ss".$file_ext);
		// small image ----------------------------------------------------------------------------
		$new_width ='300';
		$new_height = '300';
		$this->vimage->resize_ratio($new_width, $new_height);
		$this->vimage->save('', $filedata['file_path'].$raw_name."-s".$file_ext);
		// medium image ----------------------------------------------------------------------------
		$new_width ='500';
		$new_height = '500';
		$this->vimage->resize_ratio($new_width, $new_height);
		$this->vimage->save('', $filedata['file_path'].$raw_name."-m".$file_ext);
		// large image ----------------------------------------------------------------------------
		$new_width ='700';
		$new_height = '700';
		$this->vimage->resize_ratio($new_width, $new_height);
		$this->vimage->save('', $filedata['file_path'].$raw_name."-l".$file_ext);
		// extra-large image ----------------------------------------------------------------------------
		$new_width ='900';
		$new_height = '900';
		$this->vimage->resize_ratio($new_width, $new_height);
		$this->vimage->save('', $filedata['file_path'].$raw_name."-xl".$file_ext);
		// before end resize, clear some var.
		unset($new_height, $new_width);
		// end resize image ----------------------------------------------------------------------------#
		// set file path for add to db
		$image_file = $this->config_model->load("manga_dir").$data['story_uri']."/".$file_name;
		// add to db --------------------------------------------------------------------------#
		// add to story
		$this->db->set("story_name", $data['story_name']);
		$this->db->set("story_statinfo", $data['story_statinfo']);
		$this->db->set("story_summary", $data['story_summary']);
		$this->db->set("story_author", $data['story_author']);
		$this->db->set("story_artist", $data['story_artist']);
		$this->db->set("story_cover", $image_file);
		$this->db->set("story_uri", urlencode($data['story_uri']));
		$this->db->set("story_add", date("Y-m-d H:i:s", time()));
		$this->db->set("story_update", date("Y-m-d H:i:s", time()));
		$this->db->set("story_enable", $data['story_enable']);
		$this->db->insert($this->db->dbprefix("story"));
		$story_id = $this->db->insert_id();
		// add to genre_story
		if ( is_array($data['story_genre']) ) {
			foreach ( $data['story_genre'] as $genre_id ) {
				$this->db->set("genre_id", $genre_id);
				$this->db->set("story_id", $story_id);
				$this->db->insert($this->db->dbprefix("genre_story"));
			}
		}
		// end add to db --------------------------------------------------------------------------#
		return true;
	}// add_manga
	
	
	/**
	 * delete manga
	 * @param int $story_id
	 * @return bool
	 */
	function delete_manga($story_id = '') {
		if ( ! is_numeric($story_id) ) {return false;}
		$this->load->model("chapter_model");
		// delete chapters and images in it.
		$this->db->where("story_id", $story_id);
		$query = $this->db->get($this->db->dbprefix("chapters"));
		if ( $query->num_rows() > 0 ) {
			foreach ( $query->result() as $row ) {
				$this->chapter_model->delete_chapter($row->chapter_id);
			}
		}
		$query->free_result();
		// delete all files and folders in this manga
		if ( $this->show_manga_info("story_uri", $story_id) != null ) {
			$manga_dir = dirname(BASEPATH)."/".$this->config_model->load("manga_dir").urldecode($this->show_manga_info("story_uri", $story_id))."/";
			$this->delTree($manga_dir);
		}
		// delete chapters table
		$this->db->delete($this->db->dbprefix("story"), array("story_id" => $story_id));
		$this->db->query("ALTER TABLE `".$this->db->dbprefix("story")."` AUTO_INCREMENT =1");
		return true;
	}// delete_manga
	
	
	function count_views($story_id = '') {
		if ( !is_numeric($story_id) ) {return false;}
			$story_views = ($this->show_manga_info('story_views', $story_id)+1);
			$this->db->set("story_views", $story_views);
			$this->db->where("story_id", $story_id);
			$this->db->update("story");
			return true;
	}// count_views
	
	
	/**
	 * delete tree folder
	 * @param string $dir always end with slash/
	 * @return bool
	 */
	function delTree($dir) {
		if ( $dir == dirname(BASEPATH)."/".$this->config_model->load("manga_dir") ) {return false;}
		$files = glob( $dir . '*', GLOB_MARK );
		foreach( $files as $file ){
			if( substr( $file, -1 ) == '/' )
				delTree( $file );
			else
				unlink( $file );
		}
		if ( file_exists($dir) ) {
			rmdir( $dir );
		}
	}// delTree
	
	
	/**
	 * edit manga
	 * @param array $data
	 * @return string
	 */
	function edit_manga($data = '') {
		if ( $data == null ) {return "Form empty.";}
		// find nodup uri
		$data['story_uri'] = $this->nodup_uri($data['story_uri'], "yes", $this->input->get("id"));
		// if use upload ------------------------------------------------------------------------------------------------------------#
		if ( $data['use_upload'] == true ) {
			// load upload library
			$this->load->library('upload');
			$config['upload_path'] = dirname(BASEPATH)."/".$this->config_model->load("manga_dir").$data['story_uri'];
			$config['allowed_types'] = 'jpg|jpeg|gif|png';
			$config['encrypt_name'] = true;
			$config['remove_spaces'] = true;
			$this->upload->initialize($config);
			// check if target folder exists
			if ( ! file_exists($config['upload_path']) ) {
				$old = umask(0);
				mkdir($config['upload_path'], 0777, true);
				umask($old);
			}
			// start upload
			if ( ! $this->upload->do_upload("story_cover") ) {
				return $this->upload->display_errors();
			} else {
				$filedata = $this->upload->data();
				$file_ext = $filedata['file_ext'];
				$file_size = $filedata['file_size'];
				$file_name = $filedata['file_name'];
				$raw_name = $filedata['raw_name'];// file_name with no ext \\
				// upload success... delete old cover
				if ( file_exists(dirname(BASEPATH)."/".$data['cover_old']) ) {
					@unlink(dirname(BASEPATH)."/".$this->set_image_size($data['cover_old'], "tiny"));
					@unlink(dirname(BASEPATH)."/".$this->set_image_size($data['cover_old'], "small"));
					@unlink(dirname(BASEPATH)."/".$this->set_image_size($data['cover_old'], "medium"));
					@unlink(dirname(BASEPATH)."/".$this->set_image_size($data['cover_old'], "large"));
					@unlink(dirname(BASEPATH)."/".$this->set_image_size($data['cover_old'], "extralarge"));
					@unlink(dirname(BASEPATH)."/".$data['cover_old']);
				}
			}
			// resize image ----------------------------------------------------------------------------#
			$this->load->library("vimage", $filedata['full_path']);
			// tiny image
			$new_width ='100';
			$new_height = '100';
			$this->vimage->resize_ratio($new_width, $new_height);
			$this->vimage->save('', $filedata['file_path'].$raw_name."-ss".$file_ext);
			// small image ----------------------------------------------------------------------------
			$new_width ='300';
			$new_height = '300';
			$this->vimage->resize_ratio($new_width, $new_height);
			$this->vimage->save('', $filedata['file_path'].$raw_name."-s".$file_ext);
			// medium image ----------------------------------------------------------------------------
			$new_width ='500';
			$new_height = '500';
			$this->vimage->resize_ratio($new_width, $new_height);
			$this->vimage->save('', $filedata['file_path'].$raw_name."-m".$file_ext);
			// large image ----------------------------------------------------------------------------
			$new_width ='700';
			$new_height = '700';
			$this->vimage->resize_ratio($new_width, $new_height);
			$this->vimage->save('', $filedata['file_path'].$raw_name."-l".$file_ext);
			// extra-large image ----------------------------------------------------------------------------
			$new_width ='900';
			$new_height = '900';
			$this->vimage->resize_ratio($new_width, $new_height);
			$this->vimage->save('', $filedata['file_path'].$raw_name."-xl".$file_ext);
			// before end resize, clear some var.
			unset($new_height, $new_width);
			// end resize image ----------------------------------------------------------------------------#
			// set file path for add to db
			$image_file = $this->config_model->load("manga_dir").$data['story_uri']."/".$file_name;
		}
		// end if use upload ------------------------------------------------------------------------------------------------------------#
		// update to db --------------------------------------------------------------------------#
		// update to story
		$this->db->set("story_name", $data['story_name']);
		$this->db->set("story_statinfo", $data['story_statinfo']);
		$this->db->set("story_summary", $data['story_summary']);
		$this->db->set("story_author", $data['story_author']);
		$this->db->set("story_artist", $data['story_artist']);
		if ( $data['use_upload'] == true ) {
			$this->db->set("story_cover", $image_file);
		}
		//$this->db->set("story_uri", urlencode($data['story_uri']));
		$this->db->set("story_update", date("Y-m-d H:i:s", time()));
		$this->db->set("story_enable", $data['story_enable']);
		$this->db->where("story_id", $this->input->get("id"));
		$this->db->update($this->db->dbprefix("story"));
		$story_id = $this->input->get("id");
		// update to genre_story
		$this->db->delete($this->db->dbprefix("genre_story"), array("story_id" => $story_id));// delete genre_story then add
		if ( is_array($data['story_genre']) ) {
			foreach ( $data['story_genre'] as $genre_id ) {
				$this->db->set("genre_id", $genre_id);
				$this->db->set("story_id", $story_id);
				$this->db->insert($this->db->dbprefix("genre_story"));
			}
		}
		// end update to db --------------------------------------------------------------------------#
		return true;
	}// edit_manga
	
	
	/**
	 * list_item
	 * @param all|admin|front $list_for
	 * @return mixed 
	 */
	function list_item($list_for = 'front') {// list manga
		$sql = "select * from " . $this->db->dbprefix("story");
		$sql .= " where 1";
		if ( $list_for == 'front' ) {
			$sql .= " and story_enable = 1";
		}
		$q = trim($this->input->get("q"));
		if ( $q != null ) {
			$sql .= " and (";
			$sql .= " story_name like '%".$this->db->escape_like_str($q)."%'";
			$sql .= " or story_statinfo like '%".$this->db->escape_like_str($q)."%'";
			$sql .= " or story_summary like '%".$this->db->escape_like_str($q)."%'";
			$sql .= " or story_author like '%".$this->db->escape_like_str($q)."%'";
			$sql .= " or story_artist like '%".$this->db->escape_like_str($q)."%'";
			$sql .= ")";
		}
		$orders = trim($this->input->get("orders"));
		$sort = trim($this->input->get("sort"));
		if ( $orders == null ) {$orders = "story_name";}
		if ( $orders != "story_id" && $orders != "story_name" && $orders != "story_author" && $orders != "story_artist" && $orders != "story_views" && $orders != "story_add" && $orders != "story_update" && $orders != "story_enable") {$orders = "story_id";}
		if ( $sort == null || ($sort != "asc" && $sort != "desc") ) {$sort = "asc";}
		$sql .= " order by ".$orders." ".$sort;
		// query for count total
		$query = $this->db->query($sql);
		$total = $query->num_rows();
		$query->free_result();
		if ( $list_for != "all" ) {
			// pagination-----------------------------
			$this->load->library('pagination');
			if ( $list_for == "admin" ) {
				$config['base_url'] = site_url($this->uri->uri_string())."?orders=".$this->input->get("orders", true)."&sort=".$this->input->get("sort", true)."&q=".trim($this->input->get("q", true));
				$config['per_page'] = $this->config_model->load('admin_items_per_page');
			} else {
				$config['base_url'] = site_url($this->uri->uri_string())."?";
				$config['per_page'] = $this->config_model->load('web_items_per_page');
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
		}
		$query = $this->db->query($sql);
		if ( $query->num_rows() > 0 ) {
			$output['total_item'] = $total;
			$output['list'] = $query->result();
			$query->free_result();
			return $output;
		} else {
			$query->free_result();
			return null;
		}
	}// list_item
	
	
	/**
	 * list_item_genre
	 * list manga to genre
	 * @param all|admin|front $list_for
	 * @return mixed 
	 */
	function list_item_genre($genre_id = '', $list_for = 'front') {// list manga
		//if ( !is_numeric($genre_id) ) {return null;}
		$sql = "select * from " . $this->db->dbprefix("story") . " s";
		$sql .= " inner join " . $this->db->dbprefix("genre_story") . " gs";
		$sql .= " on s.story_id = gs.story_id";
		$sql .= " where 1";
		if ( $genre_id != null ) {
			$sql .= " and genre_id = " . $genre_id;
		}
		if ( $list_for == 'front' ) {
			$sql .= " and story_enable = 1";
		}
		$q = trim($this->input->get("q"));
		if ( $q != null ) {
			$sql .= " and (";
			$sql .= " story_name like '%".$this->db->escape_like_str($q)."%'";
			$sql .= " or story_statinfo like '%".$this->db->escape_like_str($q)."%'";
			$sql .= " or story_summary like '%".$this->db->escape_like_str($q)."%'";
			$sql .= " or story_author like '%".$this->db->escape_like_str($q)."%'";
			$sql .= " or story_artist like '%".$this->db->escape_like_str($q)."%'";
			$sql .= ")";
		}
		$sql .= " group by s.story_id";
		$orders = trim($this->input->get("orders"));
		$sort = trim($this->input->get("sort"));
		if ( $orders == null ) {$orders = "story_name";}
		if ( $orders != "story_id" && $orders != "story_name" && $orders != "story_author" && $orders != "story_artist" && $orders != "story_views" && $orders != "story_add" && $orders != "story_update" && $orders != "story_enable") {$orders = "story_id";}
		if ( $sort == null || ($sort != "asc" && $sort != "desc") ) {$sort = "asc";}
		$sql .= " order by ".$orders." ".$sort;
		// query for count total
		$query = $this->db->query($sql);
		$total = $query->num_rows();
		$query->free_result();
		if ( $list_for != "all" ) {
			// pagination-----------------------------
			$this->load->library('pagination');
			if ( $list_for == "admin" ) {
				$config['base_url'] = site_url($this->uri->uri_string())."?orders=".$this->input->get("orders", true)."&sort=".$this->input->get("sort", true)."&q=".trim($this->input->get("q", true));
				$config['per_page'] = $this->config_model->load('admin_items_per_page');
			} else {
				$config['base_url'] = site_url($this->uri->uri_string())."?";
				$config['per_page'] = $this->config_model->load('web_items_per_page');
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
		}
		$query = $this->db->query($sql);
		if ( $query->num_rows() > 0 ) {
			$output['total_item'] = $total;
			foreach ( $query->result() as $row ) {
				$output[$row->story_id]['story_name'] = $row->story_name;
				$output[$row->story_id]['story_statinfo'] = $row->story_statinfo;
				$output[$row->story_id]['story_summary'] = $row->story_summary;
				$output[$row->story_id]['story_author'] = $row->story_author;
				$output[$row->story_id]['story_artist'] = $row->story_artist;
				$output[$row->story_id]['story_cover'] = $row->story_cover;
				// cover sizes
					$output[$row->story_id]['cover_tiny'] = $this->manga_model->set_image_size($row->story_cover, "tiny");
					$output[$row->story_id]['cover_small'] = $this->manga_model->set_image_size($row->story_cover, "small");
					$output[$row->story_id]['cover_medium'] = $this->manga_model->set_image_size($row->story_cover, "medium");
					$output[$row->story_id]['cover_large'] = $this->manga_model->set_image_size($row->story_cover, "large");
					$output[$row->story_id]['cover_extralarge'] = $this->manga_model->set_image_size($row->story_cover, "extralarge");
				$output[$row->story_id]['story_uri'] = $row->story_uri;
				$output[$row->story_id]['story_views'] = $row->story_views;
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
			}
			$query->free_result();
			return $output;
		} else {
			$query->free_result();
			return null;
		}
	}// list_item_genre
	
	
	/**
	 * list genre for this manga.
	 * @param int $story_id
	 * @param string $story_uri
	 * @return array
	 */
	function manga_genres($story_id = '', $story_uri = '') {
		if ( $story_id == null && $story_uri == null ) {return false;}
		if ( ! is_numeric($story_id) ) {return false;}
		$sql = "select * from " . $this->db->dbprefix("genre_story");
		$sql .= " where";
		if ( $story_id != null ) {
			$sql .= " story_id = " . $story_id;
		} else {
			$sql .= " story_uri = " . $this->db->escape($story_uri);
		}
		$query = $this->db->query($sql);
		if ( $query->num_rows() > 0 ) {
			foreach ( $query->result() as $row ) {
				$output[]= $row->genre_id;
			}
			$query->free_result();
			return $output;
		} else {
			$query->free_result();
			return false;
		}
	}// manga_genres
	
	
	/**
	 * nodup uri
	 * @param string $uri_check
	 * @param string $is_edit
	 * @param int $id_edit_check
	 * @return mixed
	 */
	function nodup_uri($uri_check = 'm', $is_edit = 'no', $id_edit_check = '') {
		if ( $uri_check == null ) {$uri_check = "m_";}
		if ( $is_edit != "yes" && $is_edit != "no" ) {$is_edit == "no";}
		if ( $is_edit == "yes" && !is_numeric($id_edit_check) ) {return false;}// nodup check for edit but no id to check, return false
		/* check nodup for edit */
		if ( $is_edit == "yes" ) {
			$this->db->where("story_id", $id_edit_check);
			$this->db->where("story_uri", urlencode($uri_check));
			$query = $this->db->get($this->db->dbprefix("story"));
			if ( $query->num_rows() > 0 ) {
				// this uri match its own id = ok
				$query->free_result();
				return $uri_check;
			}
			// if not found in above = uri is not its own id, use check like add new uri down there.
		}
		/* end check nodup for edit */
		/* check nodup for add and changed for edit */
		$found = true;// do until not found.
		$count = 0;
		do {
			$new_uri = ($count === 0 ? $uri_check : $uri_check . "_" . $count);
			$this->db->where("story_uri", urlencode($new_uri));
			$query = $this->db->get($this->db->dbprefix("story"));
			if ( $query->num_rows() > 0 ) {
				$found = true;
			} else {
				$found = false;
			}
			$query->free_result();
			$count++;
		} while ($found == true);
		return $new_uri;
		/* check nodup for add and changed for edit */
	}// nodup_uri
	
	
	/**
	 * enter image file name with ext and choose what size you want.
	 * @param string $image_file
	 * @param string $size small, medium, large, huge
	 * @return string string
	 */
	function set_image_size($image_file = '', $size = 'small') {
		if ( $image_file == NULL ) {return $image_file;}
		$this->load->helper('file');
		$fileext = ".".show_file_ext($image_file);
		$filename = str_replace($fileext, "",$image_file);
		if ( $size == 'tiny' ) {
			return $filename."-ss".$fileext;
		} elseif ( $size == 'small' ) {
			return $filename."-s".$fileext;
		} elseif ( $size == 'medium' ) {
			return $filename."-m".$fileext;
		} elseif ( $size == 'large' ) {
			return $filename."-l".$fileext;
		} elseif ( $size == 'extralarge' ) {
			return $filename."-xl".$fileext;
		} else {
			return $image_file;
		}
	}// set_image_size
	
	
	/**
	 * show manga info
	 * @param string $field
	 * @param int $story_id
	 * @param string $story_uri
	 * @return mixed
	 */
	function show_manga_info($field = "story_name", $story_id = '', $story_uri = '') {
		if ( !is_numeric($story_id) && $story_uri == null ) {return false;}
		if ( $story_id != null ) {
			$this->db->where("story_id", $story_id);
		} else {
			$this->db->where("story_uri", $story_uri);
		}
		$query = $this->db->get($this->db->dbprefix("story"));
		if ( $query->num_rows() > 0 ) {
			$row = $query->row();
			$query->free_result();
			return $row->$field;
		} else {
			$query->free_result();
			return false;
		}
	}// show_manga_info
	

}

