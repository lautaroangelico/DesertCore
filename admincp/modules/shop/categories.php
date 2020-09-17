<?php
/**
 * DesertCore CMS
 * https://desertcore.com/
 * 
 * @version 1.2.0
 * @author Lautaro Angelico <https://lautaroangelico.com/>
 * @copyright (c) 2018-2020 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 * 
 * Donate to the Project:
 * https://desertcore.com/donate
 * 
 * Contribute:
 * https://github.com/lautaroangelico/DesertCore
 */

// edit category
if(check($_POST['edit_submit'])) {
	try {
		$Shop = new Shop();
		$Shop->setCategory($_POST['id']);
		$Shop->setTitle($_POST['title']);
		if(check($_POST['parent'])) $Shop->setParent($_POST['parent']);
		$Shop->editCategory();
		redirect('shop/categories');
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

// change category status
if(check($_GET['id'], $_GET['status'])) {
	try {
		$Shop = new Shop();
		$newStatus = $_GET['status'] == 'enable' ? 1 : 0;
		$Shop->changeCategoryStatus($_GET['id'], $newStatus);
		redirect('shop/categories');
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

// add new category
if(check($_POST['add_category'])) {
	try {
		$Shop = new Shop();
		$Shop->setParent($_POST['add_parent']);
		$Shop->setTitle($_POST['add_title']);
		$Shop->addCategory();
		redirect('shop/categories');
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

$Shop = new Shop();
$Shop->ignoreStatus();

echo '<div class="row">';
	echo '<div class="col-md-8">';
		echo '<div class="card">';
			echo '<div class="header">Categories List</div>';
			echo '<div class="content">';
				
				// CATEGORY LIST
				$result = $Shop->getCategoriesList();
				if(is_array($result)) {
					echo '<table class="table table-hover">';
					echo '<thead>';
						echo '<tr>';
							echo '<th style="width:50%;">Title</th>';
							echo '<th>ID</th>';
							echo '<th style="width:5%;">Parent</th>';
							echo '<th>Status</th>';
							echo '<th style="width:30%;"></th>';
						echo '</tr>';
					echo '</thead>';
					echo '<tbody>';
						foreach($result as $row) {
							
							echo '<form action="'.admincp_base('shop/categories').'" method="post">';
							echo '<input type="hidden" name="id" value="'.$row['id'].'"/>';
							echo '<tr>';
								echo '<td><input class="form-control" type="text" name="title" value="'.$row['title'].'"/></td>';
								echo '<td>'.$row['id'].'</td>';
								echo '<td></td>';
								if($row['status'] == 1) {
									echo '<td><a href="'.admincp_base('shop/categories/id/'.$row['id'].'/status/disable').'" class="btn btn-success btn-xs push-5-r push-10"><i class="fa fa-check"></i></a></td>';
								} else {
									echo '<td><a href="'.admincp_base('shop/categories/id/'.$row['id'].'/status/enable').'" class="btn btn-warning btn-xs push-5-r push-10"><i class="fa fa-exclamation-circle"></i></a></td>';
								}
								echo '<td class="text-right">';
									echo '<button class="btn btn-default btn-xs" type="submit" name="edit_submit" value="ok"/>Save</button> ';
									echo '<a href="'.admincp_base('shop/items/c/'.$row['id']).'" class="btn btn-default btn-xs">Items</a> ';
									echo '<a href="#" onclick="confirmationMessage(\''.admincp_base('shop/delete/category/'.$row['id']).'\', \'Are you sure?\', \'This action will permanently delete the category, subcategories and all items in them.\', \'Confirm\', \'Cancel\')" class="btn btn-danger btn-xs">Delete</a>';
								echo '</td>';
							echo '</tr>';
							echo '</form>';
							
							// childs
							if(is_array($row['childs'])) {
								foreach($row['childs'] as $child) {
									echo '<form action="'.admincp_base('shop/categories').'" method="post">';
									echo '<input type="hidden" name="id" value="'.$child['id'].'"/>';
									echo '<tr>';
										echo '<td style="padding-left: 50px;"><input class="form-control" type="text" name="title" value="'.$child['title'].'"/></td>';
										echo '<td>'.$child['id'].'</td>';
										echo '<td><input class="form-control" type="text" name="parent" value="'.$child['parent'].'"/></td>';
										if($child['status'] == 1) {
											echo '<td><a href="'.admincp_base('shop/categories/id/'.$child['id'].'/status/disable').'" class="btn btn-success btn-xs push-5-r push-10"><i class="fa fa-check"></i></a></td>';
										} else {
											echo '<td><a href="'.admincp_base('shop/categories/id/'.$child['id'].'/status/enable').'" class="btn btn-warning btn-xs push-5-r push-10"><i class="fa fa-exclamation-circle"></i></a></td>';
										}
										echo '<td class="text-right">';
											echo '<button class="btn btn-default btn-xs" type="submit" name="edit_submit" value="ok"/>Save</button> ';
											echo '<a href="'.admincp_base('shop/items/c/'.$row['id'].'/s/'.$child['id']).'" class="btn btn-default btn-xs">Items</a> ';
											echo '<a href="#" onclick="confirmationMessage(\''.admincp_base('shop/delete/subcategory/'.$child['id']).'\', \'Are you sure?\', \'This action will permanently delete the subcategory and all the items in it.\', \'Confirm\', \'Cancel\')" class="btn btn-danger btn-xs">Delete</a>';
										echo '</td>';
									echo '</tr>';
									echo '</form>';
								}
							}
							
							echo '<tr><td colspan="5"><br /></td></tr>'; // separator
						}
					echo '</tbody>';
					echo '</table>';
				} else {
					message('warning', 'There are no categories.');
				}

			echo '</div>';
		echo '</div>';
	echo '</div>';
	
	// RIGHT
	echo '<div class="col-md-4">';
		echo '<div class="card">';
			echo '<div class="header">Add Category / Sub-Category</div>';
			echo '<div class="content">';
				
				echo '<form action="'.admincp_base('shop/categories').'" method="post">';
						echo '<div class="form-group">';
							echo '<select class="form-control" name="add_parent">';
								echo '<option value="0">No Parent</option>';
								foreach($result as $cats) {
									echo '<option value="'.$cats['id'].'">'.$cats['title'].'</option>';
								}
							echo '</select>';
						echo '</div>';
						echo '<div class="form-group">';
							echo '<input type="text" name="add_title" class="form-control" placeholder="Title / Language Phrase">';
						echo '</div>';
						echo '<button type="submit" class="btn btn-primary" name="add_category" value="ok">Add Category</button>';
					echo '</form><br />';
					
			echo '</div>';
		echo '</div>';
	echo '</div>';
	
echo '</div>';