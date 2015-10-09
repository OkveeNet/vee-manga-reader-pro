<?php
/**
 * @author mr.v
 * @website http://okvee.
 */

class manga_model extends CI_Model {
	
	
	function  __construct() {
		parent::__construct();
	}
	
	
	/**
	 * add chapter
	 * @param array $data
	 * @return string 
	 */
	function add_chapter($data = '') {
		if ( $data == null ) {return "<div class=\"txt_error\">Form empty.</div>";}
		// find nodup uri
		$data['chapter_uri'] = $this->nodup_chapter_uri($data['chapter_uri']);
		// get manga uri, folder
		$manga_dir = $this->show_manga_info("story_uri", $data['story_id']);
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
			return "<div class=\"txt_error\">".$this->upload->display_errors()."</div>";
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
			return "<div class=\"txt_error\">".lang("admin_zipped_image_only")."</div>";
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
		$this->db->set("chapter_uri", $data['chapter_uri']);
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
		return "<div class=\"txt_success\">".lang("admin_add_complete")."</div>";
	}
	

	/**
	 * add manga
	 * @param array $data
	 * @return string
	 */
	function add_manga($data = '') {
		if ( $data == null ) {return "<div class=\"txt_error\">Form empty.</div>";}
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
			return "<div class=\"txt_error\">".$this->upload->display_errors()."</div>";
		} else {
			$filedata = $this->upload->data();
			$file_ext = $filedata['file_ext'];
			$file_size = $filedata['file_size'];
			$file_name = $filedata['file_name'];
			$raw_name = $filedata['raw_name'];// file_name with no ext \\
		}
		// resize image ----------------------------------------------------------------------------#
		$this->load->library("vimage", $filedata['full_path']);
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
		$this->db->set("story_uri", $data['story_uri']);
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
		return "<div class=\"txt_success\">".lang("admin_add_complete")."</div>";
	}
	

	/**
	 * delete chapter
	 * @param int $chapter_id
	 * @return bool
	 */
	function delete_chapter($chapter_id = '') {
		if ( ! is_numeric($chapter_id) ) {return false;}
		$this->db->where("chapter_id", $chapter_id);
		$query = $this->db->get($this->db->dbprefix("chapter_images"));
		if ( $query->num_rows() > 0 ) {
			foreach ( $query->result() as $row ) {
				if ( file_exists(dirname(BASEPATH)."/".$row->image_file) ) {
					unlink(dirname(BASEPATH)."/".$row->image_file);
				}
			}
			$story_id = $row->story_id;
			sleep(3);// delay it for unlink many images
			if ( file_exists(dirname(BASEPATH)."/".$this->config_model->load("manga_dir").$this->show_manga_info("story_uri", $story_id)."/".$this->show_chapter_info("chapter_uri", $chapter_id)) ) {
				rmdir(dirname(BASEPATH)."/".$this->config_model->load("manga_dir").$this->show_manga_info("story_uri", $story_id)."/".$this->show_chapter_info("chapter_uri", $chapter_id));
			}
		}
		$query->free_result();
		$this->db->delete($this->db->dbprefix("chapter_images"), array("chapter_id" => $chapter_id));
		$this->db->delete($this->db->dbprefix("chapters"), array("chapter_id" => $chapter_id));
		return true;
	}


	/**
	 * delete manga
	 * @param int $story_id
	 * @return bool
	 */
	function delete_manga($story_id = '') {
		if ( ! is_numeric($story_id) ) {return false;}
		// delete chapters and images in it.
		$this->db->where("story_id", $story_id);
		$query = $this->db->get($this->db->dbprefix("chapters"));
		if ( $query->num_rows() > 0 ) {
			foreach ( $query->result() as $row ) {
				$this->delete_chapter($row->chapter_id);
			}
		}
		$query->free_result();
		// delete all files and folders in this manga
		if ( $this->show_manga_info("story_uri", $story_id) != null ) {
			$manga_dir = dirname(BASEPATH)."/".$this->config_model->load("manga_dir").$this->show_manga_info("story_uri", $story_id)."/";
			$this->delTree($manga_dir);
		}
		// delete chapters table
		$this->db->delete($this->db->dbprefix("story"), array("story_id" => $story_id));
		return true;
	}
	

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
	}
	
	
	function edit_chapter($data = '') {
		if ( $data == null ) {return "<div class=\"txt_error\">Form empty.</div>";}
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
		return "<div class=\"txt_success\">".lang("admin_edit_complete")."</div>";
	}


	/**
	 * edit manga
	 * @param array $data
	 * @return string
	 */
	function edit_manga($data = '') {
		if ( $data == null ) {return "<div class=\"txt_error\">Form empty.</div>";}
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
				return "<div class=\"txt_error\">".$this->upload->display_errors()."</div>";
			} else {
				$filedata = $this->upload->data();
				$file_ext = $filedata['file_ext'];
				$file_size = $filedata['file_size'];
				$file_name = $filedata['file_name'];
				$raw_name = $filedata['raw_name'];// file_name with no ext \\
				// upload success... delete old cover
				if ( file_exists(dirname(BASEPATH)."/".$data['cover_old']) ) {
					unlink(dirname(BASEPATH)."/".$data['cover_old']);
				}
			}
			// resize image ----------------------------------------------------------------------------#
			$this->load->library("vimage", $filedata['full_path']);
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
		$this->db->set("story_uri", $data['story_uri']);
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
		return "<div class=\"txt_success\">".lang("admin_edit_complete")."</div>";
	}


	/**
	 * list chapter
	 * @param int $story_id
	 * @param string $enable
	 * @return mixed
	 */
	function list_chapter($story_id = '', $enable = '1') {
		$sql = "select * from " . $this->db->dbprefix("chapters");
		if ( is_numeric($story_id) || $enable != "all" ) {
			$sql .= " where";
		}
		if ( is_numeric($story_id) ) {
			$sql .= " story_id = $story_id";
		}
		if ( is_numeric($story_id) && $enable != "all" ) {
			$sql .= " and";
		}
		if ( $enable != "all" ) {
			$sql .= " chapter_enable = " . $enable;
		}
		// sort orders
		$orders = $this->input->get("orders");
		if ( $orders == "id" ) {$orders = "chapter_id"; }
		elseif ( $orders == "n" ) { $orders = "chapter_name"; }
		elseif ( $orders == "s" ) { $orders = "scanlator"; }
		elseif ( $orders == "u" ) { $orders = "chapter_uri"; }
		elseif ( $orders == "e" ) { $orders = "chapter_enable"; }
		else { $orders = "abs(chapter_order)"; }
		$sql .= " order by $orders asc";
		// query for count total
		$query = $this->db->query($sql);
		$total = $query->num_rows();
		$query->free_result();
		// pagination-----------------------------
		$this->load->library('pagination');
		$config['base_url'] = base_url().$this->uri->segment(1)."/".$this->uri->segment(2)."?manga_id=".trim($this->input->get("manga_id", true))."&orders=".trim($this->input->get("orders", true));
		$config['total_rows'] = $total;
		$config['per_page'] = $this->config_model->load('admin_items_per_page');
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
			$output['total_chapter'] = $total;
			foreach ( $query->result() as $row ) {
				$output[$row->chapter_id]['chapter_order'] = $row->chapter_order;
				$output[$row->chapter_id]['story_id'] = $row->story_id;
				$output[$row->chapter_id]['manga_name'] = $this->show_manga_info("story_name", $row->story_id);
				$output[$row->chapter_id]['chapter_name'] = $row->chapter_name;
				$output[$row->chapter_id]['scanlator'] = $row->scanlator;
				$output[$row->chapter_id]['chapter_uri'] = $row->chapter_uri;
				$output[$row->chapter_id]['chapter_add'] = $row->chapter_add;
				$output[$row->chapter_id]['chapter_update'] = $row->chapter_update;
				$output[$row->chapter_id]['chapter_enable'] = $row->chapter_enable;
				// load images
				$this->db->where("chapter_id", $row->chapter_id);
				$query2 = $this->db->get($this->db->dbprefix("chapter_images"));
				if ( $query2->num_rows() > 0 ) {
					$im = 0;
					foreach ( $query2->result() as $row2 ) {
						$output[$row->chapter_id]['chapter_images'][$row2->chapter_image_id] = $row2->image_file;
						$im++;
					}
					$output[$row->chapter_id]['total_chapter_image'] = $im;
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


	/**
	 * list genre that manga is in.
	 * @param int $story_id
	 * @param string $story_uri
	 * @return array
	 */
	function list_current_genre($story_id = '', $story_uri = '') {
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
	}


	/**
	 * list manga
	 * @param string $status
	 * @param string $list_for
	 * @return array
	 */
	function list_manga($status = '1', $list_for = "admin") {
		if ( $list_for != "admin" && $list_for != "front" ) {$list_for = "admin";}
		$sql = "select * from " . $this->db->dbprefix('story');
		// conditions
		if ( $status !== '1' && $status !== '0' && $status !== 'all' ) {$status = '1';}
		if ( $list_for == "front" ) {$status = "1";}
		if ( $status !== 'all' ) {
			$sql .= " where story_enable = " .$this->db->escape($status);
		}
		// orders
		$orders = $this->input->get("orders");
		if ( $orders == "id" ) { $orders = "story_id"; }
		elseif ( $orders == "n" ) { $orders = "story_name"; }
		elseif ( $orders == "au" ) { $orders = "story_author"; }
		elseif ( $orders == "ar" ) { $orders = "story_artist"; }// LL
		elseif ( $orders == "u" ) { $orders = "story_update"; }
		elseif ( $orders == "e" ) { $orders = "story_enable"; }
		else { $orders = "story_name"; }
		$sql .= " order by ".$orders." asc";
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
					$output[$row->story_id]['cover_small'] = $this->set_image_size($row->story_cover, "small");
					$output[$row->story_id]['cover_medium'] = $this->set_image_size($row->story_cover, "medium");
					$output[$row->story_id]['cover_large'] = $this->set_image_size($row->story_cover, "large");
					$output[$row->story_id]['cover_extralarge'] = $this->set_image_size($row->story_cover, "extralarge");
				$output[$row->story_id]['story_uri'] = $row->story_uri;
				$output[$row->story_id]['story_add'] = $row->story_add;
				$output[$row->story_id]['story_update'] = $row->story_update;
				$output[$row->story_id]['story_enable'] = $row->story_enable;
			}
			$query->free_result();
			return $output;
		} else {
			$query->free_result();
			return null;
		}
	}
	
	
	/**
	 * nodup chapter uri
	 * @param string $uri_check
	 * @param string $is_edit
	 * @param int $id_edit_check
	 * @return mixed
	 */
	function nodup_chapter_uri($uri_check = 'c', $is_edit = 'no', $id_edit_check = '') {
		if ( $uri_check == null ) {$uri_check = "c_";}
		if ( $is_edit != "yes" && $is_edit != "no" ) {$is_edit == "no";}
		if ( $is_edit == "yes" && !is_numeric($id_edit_check) ) {return false;}// nodup check for edit but no id to check, return false
		/* check nodup for edit */
		if ( $is_edit == "yes" ) {
			$this->db->where("chapter_id", $id_edit_check);
			$this->db->where("chapter_uri", $uri_check);
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
			$this->db->where("chapter_uri", $new_uri);
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
	}


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
			$this->db->where("story_uri", $uri_check);
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
			$this->db->where("story_uri", $new_uri);
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
	}


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
		if ( $size == 'small' ) {
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
	}
	

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
	}
	
	
}

/* eof */