<?php
/**
 * @author mr.v
 */

class genre_model extends CI_Model {


	public function __construct() {
		parent::__construct();
	}


	/**
	 * add genre
	 * @param array $data
	 * @return bool
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
					$item = $this->nodup_uri($item);
				}
				/* end check nodup uri */
				$this->db->set($key, $item);
			}
			$this->db->set("genre_add", date("Y-m-d H:i:s", time()));
			$this->db->insert($this->db->dbprefix("genre"));
			return true;
		}
		return false;
	}


	/**
	 * edit genre
	 * @param array $data
	 * @return bool
	 */
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
					$item = $this->nodup_uri($item, 'yes', trim($this->input->get("id")));
				}
				$this->db->set($key, $item);
			}// endforeach
			$this->db->where("genre_id", trim($this->input->get("id")));
			$this->db->update($this->db->dbprefix("genre"));
			return true;
		}// endif is array
		return false;
	}


	/**
	 * list genre
	 * @param string $enable (0,1,all)
	 * @return mixed
	 */
	function list_genre($enable = '1') {
		if ( $enable !== '1' && $enable !== '0' && $enable !== 'all' ) {$enable = '1';}
		if ( $enable !== 'all' ) {
			$this->db->where("genre_enable", $enable);
		}
		$query = $this->db->get($this->db->dbprefix("genre"));
		if ( $query->num_rows() > 0 ) {
			foreach ( $query->result() as $row ) {
				$output[$row->genre_id]['genre_name'] = $row->genre_name;
				$output[$row->genre_id]['genre_description'] = $row->genre_description;
				$output[$row->genre_id]['genre_uri'] = $row->genre_uri;
				$output[$row->genre_id]['genre_add'] = $row->genre_add;
				$output[$row->genre_id]['genre_enable'] = $row->genre_enable;
			}
			$query->free_result();
			return $output;
		} else {
			$query->free_result();
			return null;
		}
	}


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
			$this->db->where("genre_uri", $uri_check);
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
			$this->db->where("genre_uri", $new_uri);
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
	}


	/**
	 * show genre info
	 * @param string $field
	 * @param int $genre_id
	 * @param string $genre_uri
	 * @return string
	 */
	function show_genre_info($field = 'genre_name', $genre_id = '', $genre_uri = '') {
		if ( !is_numeric($genre_id) && $genre_uri == null ) {return false;}
		if ( $genre_id != null ) {
			$this->db->where("genre_id", $genre_id);
		} else {
			$this->db->where("genre_uri", $genre_uri);
		}
		$query = $this->db->get($this->db->dbprefix("genre"));
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