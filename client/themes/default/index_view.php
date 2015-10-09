
<?php
$this->load->module("homebanner");
if ( method_exists($this->homebanner, "showtofront") )
	echo $this->homebanner->showtofront();
?>

<h1><?php echo lang("front_new_manga"); ?></h1>

<div class="list_index">
	<?php if ( isset($list_item['list']) && is_array($list_item['list']) ): ?>
	<table class="manga">
		<?php foreach ( $list_item['list'] as $key ): ?>
		<tr>
			<td class="name">
				<a href="<?php echo site_url("manga/".$key->story_uri); ?>"><img src="<?php echo $this->manga_model->set_image_size($key->story_cover, "tiny"); ?>" alt="<?php echo $key->story_name; ?>" /></a>
				<?php echo anchor("manga/".$key->story_uri, $key->story_name); ?>
				<?php
				// list chapters
				$this->db->where("story_id", $key->story_id);
				$this->db->where("chapter_enable", 1);
				$this->db->where("chapter_update >= DATE_SUB( NOW(), INTERVAL 10 DAY)");
				$this->db->order_by("chapter_add", "desc");
				$this->db->limit(3);
				$query = $this->db->get("chapters");
				if ( $query->num_rows() > 0 ) {
					echo "<ul>\n";
					foreach ( $query->result() as $row ) {
						echo "<li>" . anchor("manga/".$key->story_uri."/".$row->chapter_uri, $row->chapter_name) . "</li>\n";
					}
					echo "</ul>\n";
				}
				?>
			</td>
			<td><?php echo $key->story_update; ?></td>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php else: ?>
	<p><?php echo lang("front_nodata"); ?></p>
	<?php endif; ?>
</div><!--.list_index-->
