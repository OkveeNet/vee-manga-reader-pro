<?php
/**
 * @author mr.v
 * @copyright http://okvee.net
 */

class genre_model extends CI_Model {
	
	
	public function __construct() {
		parent::__construct();
	}// __construct
	
	
	/**
	 * add_genre
	 * @param array $data
	 * @return boolean 
	 */
	function add_genre($data = '') {
		// insert/add
		if ( is_array($data) ) {
			foreach ( $data as $key => $item ) {
				/* change genre_enable value from yes to 1 */
				if ( $key == "genre_enable" && $item == "yes" ) {
					$item = '1';
				} elseif ( $key == "genre_enable" && $item != "yes" ) {
					$item = '0';
				}
				/* end change genre_enable value */
				/* check nodup uri */
				if ( $key == "genre_uri" ) {
					$item = urlencode($this->nodup_uri($item));
				}
				/* end check nodup uri */
				$this->db->set($key, $item);
			}
			$this->db->set("genre_add", date("Y-m-d H:i:s", time()));
			$this->db->insert($this->db->dbprefix("genre"));
			return true;
		}
		return false;
	}// add_genre
	
	
	function edit_genre($data = '') {
		// update/edit
		if ( is_array($data) ) {
			foreach ( $data as $key => $item ) {
				/* change genre_enable value from yes to 1 */
				if ( $key == "genre_enable" && $item == "yes" ) {
					$item = '1';
				} elseif ( $key == "genre_enable" && $item != "yes" ) {
					$item = '0';
				}
				/* check nodup uri */
				if ( $key == "genre_uri" ) {
					$item = urlencode($this->nodup_uri( $item, 'yes', trim($this->input->get("id")) ));
				}
				$this->db->set($key, $item);
			}// endforeach
			$this->db->where("genre_id", trim($this->input->get("id")));
			$this->db->update($this->db->dbprefix("genre"));
			return true;
		}// endif is array
		return false;
	}// edit_genre
	
	
	/**
	 * list_item
	 * @param admin|front $list_for
	 * @return mixed 
	 */
	function list_item($list_for = 'front') {
		if ( $list_for != 'front' && $list_for != 'admin' ) {$list_for = 'front';}
		if ( $list_for == 'front' ) {
			$this->db->where("genre_enable", 1);
		}
		$this->db->order_by("genre_name", "asc"); 
		$query = $this->db->get("genre");
		if ( $query->num_rows() > 0 ) {
			$output['total_item'] = $query->num_rows();
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
	function nodup_uri($uri_check = 'g', $is_edit = 'no', $id_edit_check = '') {
		if ( $uri_check == null ) {$uri_check = "g_";}
		if ( $is_edit != "yes" && $is_edit != "no" ) {$is_edit == "no";}
		if ( $is_edit == "yes" && !is_numeric($id_edit_check) ) {return false;}// nodup check for edit but no id to check, return false
		/* check nodup for edit */
		if ( $is_edit == "yes" ) {
			$this->db->where("genre_id", $id_edit_check);
			$this->db->where("genre_uri", urlencode($uri_check));
			$query = $this->db->get($this->db->dbprefix("genre"));
			if ( $query->num_rows() > 0 ) {
				// this uri match its own id = ok
				$query->free_result();
				return $uri_check;
			}
			// if not found in above = uri is not its own id, use check like add new uri down there.
		}
		/* check nodup for edit */
		/* check nodup for add and changed for edit */
		$found = true;// do until not found.
		$count = 0;
		do {
			$new_uri = ($count === 0 ? $uri_check : $uri_check . "_" . $count);
			$this->db->where("genre_uri", urlencode($new_uri));
			$query = $this->db->get($this->db->dbprefix("genre"));
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
	
	
}

/* eof */