<?php
/**
 * @author vee
 * @copyright http://www.okvee.net
 */

class chapter_model extends CI_Model {

	
	function __construct() {
		parent::__construct();
	}// __construct
	
	
	/**
	 * add chapter
	 * @param array $data
	 * @return string 
	 */
	function add_chapter($data = '') {
		if ( $data == null ) {return "Form empty.";}
		// find nodup uri
		$data['chapter_uri'] = $this->nodup_uri($data['chapter_uri']);
		// get manga uri, folder
		$manga_dir = $this->manga_model->show_manga_info("story_uri", $data['story_id']);
		// load upload library
		$this->load->library('upload');
		$config['upload_path'] = dirname(BASEPATH)."/".$this->config_model->load("manga_dir").$manga_dir."/".$data['chapter_uri'];
		$config['allowed_types'] = 'zip';
		$config['encrypt_name'] = true;
		$config['remove_spaces'] = true;
		$this->upload->initialize($config);
		// check if target folder exists
		if ( ! file_exists($config['upload_path']) ) {
			//$old = umask(0);
			mkdir($config['upload_path'], 0777, true);
			//umask($old);
		}
		// start upload
		if ( ! $this->upload->do_upload("image_file") ) {
			return $this->upload->display_errors();
		} else {
			$filedata = $this->upload->data();
			$file_ext = $filedata['file_ext'];
			$file_size = $filedata['file_size'];
			$file_name = $filedata['file_name'];
			$raw_name = $filedata['raw_name'];// file_name with no ext \\
		}
		// upzip -------------------------------------------------------------------------------------------#
		// load upzip library
		require(dirname(dirname(__FILE__))."/libraries/dunzip/dUnzip2.inc.php");
		$zip = new dUnzip2($config['upload_path']."/".$filedata['file_name']);
		$list = $zip->getList();
		// load helper
		$this->load->helper('file');
		$checked_img_ext = true;
		foreach($list as $fileName=>$zippedFile) {
			$zfileext = strtolower(show_file_ext($fileName));
			if ( $zfileext != "jpg" && $zfileext != "jpeg" && $zfileext != "jpe" && $zfileext != "gif"&& $zfileext != "png" ) {
				// there is a file that is not 'safe' image file, alert and delete.
				$checked_img_ext = false;
				break;
			}
		}
		// if check ext is false
		if ( $checked_img_ext == false ) {
			$zip->__destroy();
			if ( file_exists($config['upload_path']."/".$filedata['file_name']) ) {
				unlink($config['upload_path']."/".$filedata['file_name']);
			}
			rmdir($config['upload_path']);
			return lang("chapter_zipped_image_only");
		} else {
			$zip->unzipall($config['upload_path']);
			$zip->__destroy();
			// remove zip file
			if ( file_exists($config['upload_path']."/".$filedata['file_name']) ) {
				unlink($config['upload_path']."/".$filedata['file_name']);
			}
		}
		// end upzip -------------------------------------------------------------------------------------------#
		// add to db
		$this->db->set("chapter_order", $data['chapter_order']);
		$this->db->set("story_id", $data['story_id']);
		$this->db->set("chapter_name", $data['chapter_name']);
		$this->db->set("scanlator", $data['scanlator']);
		$this->db->set("chapter_uri", urlencode($data['chapter_uri']));
		$this->db->set("chapter_add", date("Y-m-d H:i:s", time()));
		$this->db->set("chapter_update", date("Y-m-d H:i:s", time()));
		$this->db->set("chapter_enable", $data['chapter_enable']);
		$this->db->insert($this->db->dbprefix("chapters"));
		$chapter_id = $this->db->insert_id();
		// list files in unzipped folder and add to db.
		$this->load->helper("directory");
		$directory_map = directory_map($config['upload_path']);
		natsort($directory_map);
		$pagesort = 1;
		foreach ( $directory_map as $key => $item ) {
			$this->db->set("chapter_id", $chapter_id);
			$this->db->set("story_id", $data['story_id']);
			$this->db->set("image_file", $this->config_model->load("manga_dir").$manga_dir."/".$data['chapter_uri']."/".$item);
			$this->db->set("image_order", ($pagesort));
			$this->db->insert($this->db->dbprefix("chapter_images"));
			$pagesort++;
		}
		// update to story table
		$this->db->set("story_update", date("Y-m-d H:i:s", time()));
		$this->db->where("story_id", $data['story_id']);
		$this->db->update($this->db->dbprefix("story"));
		// it's done
		return true;
	}// add_chapter
	
	
	/**
	 * delete chapter
	 * @param int $chapter_id
	 * @return bool
	 */
	function delete_chapter($chapter_id = '') {
		if ( ! is_numeric($chapter_id) ) {return false;}
		$this->db->where("chapter_id", $chapter_id);
		$query = $this->db->get("chapter_images");
		if ( $query->num_rows() > 0 ) {
			foreach ( $query->result() as $row ) {
				if ( file_exists(dirname(BASEPATH)."/".$row->image_file) ) {
					unlink(dirname(BASEPATH)."/".$row->image_file);
				}
			}
			$story_id = $row->story_id;
			sleep(3);// delay it for unlink many images
			if ( file_exists(dirname(BASEPATH)."/".$this->config_model->load("manga_dir").urldecode($this->manga_model->show_manga_info("story_uri", $story_id))."/".urldecode($this->show_chapter_info("chapter_uri", $chapter_id))) ) {
				rmdir(dirname(BASEPATH)."/".$this->config_model->load("manga_dir").urldecode($this->manga_model->show_manga_info("story_uri", $story_id))."/".urldecode($this->show_chapter_info("chapter_uri", $chapter_id)));
			}
		}
		$query->free_result();
		$this->db->delete($this->db->dbprefix("chapter_images"), array("chapter_id" => $chapter_id));
		$this->db->query("ALTER TABLE `".$this->db->dbprefix("chapter_images")."` AUTO_INCREMENT =1");
		$this->db->delete($this->db->dbprefix("chapters"), array("chapter_id" => $chapter_id));
		$this->db->query("ALTER TABLE `".$this->db->dbprefix("chapters")."` AUTO_INCREMENT =1");
		return true;
	}// delete_chapter
	
	
	/**
	 * edit_chapter
	 * @param array $data
	 * @return tymixede 
	 */
	function edit_chapter($data = '') {
		if ( $data == null ) {return "Form empty.";}
		// edit chapter is juse update to db easy?
		$this->db->set("chapter_order", $data['chapter_order']);
		$this->db->set("chapter_name", $data['chapter_name']);
		$this->db->set("scanlator", $data['scanlator']);
		$this->db->set("chapter_update", date("Y-m-d H:i:s", time()));
		$this->db->set("chapter_enable", $data['chapter_enable']);
		$this->db->where("story_id", $data['story_id']);
		$this->db->where("chapter_id", $data['chapter_id']);
		$this->db->update($this->db->dbprefix("chapters"));
		// update to story table
		$this->db->set("story_update", date("Y-m-d H:i:s", time()));
		$this->db->where("story_id", $data['story_id']);
		$this->db->update($this->db->dbprefix("story"));
		return true;
	}// edit_chapter
	
	
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
	}// list_chapter_page_in
	
	
	/**
	 * list_item
	 * @param integer $story_id
	 * @param admin|front $list_for
	 * @return mixed 
	 */
	function list_item($story_id = '', $list_for = 'front') {
		$sql = "select * from " . $this->db->dbprefix("chapters");
		$sql .= " where 1";
		if ( is_numeric($story_id) ) {
			$sql .= " and story_id = " . $this->db->escape($story_id);
		}
		$q = trim($this->input->get("q"));
		if ( $q != null ) {
			$sql .= " and (";
			$sql .= " chapter_name like '%".$this->db->escape_like_str($q)."%'";
			$sql .= " or scanlator like '%".$this->db->escape_like_str($q)."%'";
			$sql .= " or chapter_uri like '%".$this->db->escape_like_str(urlencode($q))."%'";
			$sql .= ")";
		}
		if ( is_numeric($story_id) && $list_for == 'front' ) {
			$sql .= " and";
		}
		if ( $list_for == 'front' ) {
			$sql .= " chapter_enable = 1";
		}
		$orders = trim($this->input->get("orders"));
		$sort = trim($this->input->get("sort"));
		if ( $orders == null ) {$orders = "chapter_order";}
		if ( $sort == null || ($sort != "asc" && $sort != "desc") ) {$sort = "desc";}
		$sql .= " order by ".$orders." ".$sort;
		// query for count total
		$query = $this->db->query($sql);
		$total = $query->num_rows();
		$query->free_result();
		if ( $list_for == 'admin' ) {
			// pagination-----------------------------
			$this->load->library('pagination');
			if ( $list_for == "admin" ) {
				$config['base_url'] = site_url($this->uri->uri_string())."?orders=".$this->input->get("orders", true)."&sort=".$this->input->get("sort", true)."&q=".trim($this->input->get("q", true));
				$config['per_page'] = $this->config_model->load('admin_items_per_page');
			} else {
				$config['base_url'] = site_url($this->uri->uri_string())."?orders=".$this->input->get("orders", true);
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
	 * nodup uri
	 * @param string $uri_check
	 * @param string $is_edit
	 * @param int $id_edit_check
	 * @return mixed
	 */
	function nodup_uri($uri_check = 'c', $is_edit = 'no', $id_edit_check = '') {
		if ( $uri_check == null ) {$uri_check = "c_";}
		if ( $is_edit != "yes" && $is_edit != "no" ) {$is_edit == "no";}
		if ( $is_edit == "yes" && !is_numeric($id_edit_check) ) {return false;}// nodup check for edit but no id to check, return false
		/* check nodup for edit */
		if ( $is_edit == "yes" ) {
			$this->db->where("chapter_id", $id_edit_check);
			$this->db->where("chapter_uri", urlencode($uri_check));
			$query = $this->db->get($this->db->dbprefix("chapters"));
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
			$this->db->where("chapter_uri", urlencode($new_uri));
			$query = $this->db->get($this->db->dbprefix("chapters"));
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
	 * show chapter info
	 * @param string $field
	 * @param int $chapter_id
	 * @param string $chapter_uri
	 * @return mixed
	 */
	function show_chapter_info($field = "chapter_name", $chapter_id = '', $chapter_uri = '') {
		if ( !is_numeric($chapter_id) && $chapter_uri == null ) {return false;}
		if ( $chapter_id != null ) {
			$this->db->where("chapter_id", $chapter_id);
		} else {
			$this->db->where("chapter_uri", $chapter_uri);
		}
		$query = $this->db->get($this->db->dbprefix("chapters"));
		if ( $query->num_rows() > 0 ) {
			$row = $query->row();
			$query->free_result();
			return $row->$field;
		} else {
			$query->free_result();
			return false;
		}
	}// show_chapter_info
	

}

