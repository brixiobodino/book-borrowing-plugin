<?php
/*
Plugin Name:Book-Borrowing-Wordpress-Plugin
Plugin URI: http:www.facebook.com/bodino
Description: Book-Borrowing-Wordpress-Plugin  
Author: Brixio Bodino
Version: 1.0
Author URI: http:brixiobodinoprogrammer.com.ph
*/
function AddBookMenu(){
	add_menu_page("Book Borrowing Plugin","Book Borrowing Dashboard","manage_options","bodino-book-borrowing-plugin","bodino_book_borrowing_plugin_dashboard", 'dashicons-book');
	add_submenu_page('bodino-book-borrowing-plugin','Book Borrowing Plugin Books','Books','manage_options','bodino-book-borrowing-plugin','bodino_book_borrowing_plugin_dashboard');
	add_submenu_page('bodino-book-borrowing-plugin','Book Borrowing Plugin Category','Category','manage_options','bodino-book-borrowing-plugin_category','book_borrowing_category');
	add_submenu_page('bodino-book-borrowing-plugin',' Book Borrowing Plugin Students','Students','manage_options','bodino_book_borrowing_plugin_students','book_borrowing_students');
	add_submenu_page('bodino-book-borrowing-plugin',' Book Borrowing Plugin Borrowed','Borrowed Books','manage_options','bodino_book_borrowing_plugin_borrowed','book_borrowing_borrowed');
	add_submenu_page('bodino-book-borrowing-plugin',' Book Borrowing Plugin Borrowing Form','Borrowing Form','manage_options','bodino_book_borrowing_plugin_borrowing_form','book_borrowing_form');
}
add_action('admin_menu','AddBookMenu');
?>
<?php
function activated(){
    global $wpdb;
    $book = "wp_bodino_books";
    if($wpdb->get_var("show tables like wp_bodino_books") != $book){
        $book_sql="CREATE TABLE wp_bodino_books ( `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) DEFAULT NULL,
  `description` text,
  `isbn_num` varchar(15) DEFAULT NULL,
  `date_published` varchar(250) DEFAULT NULL,
  `category_name` varchar(250) DEFAULT NULL,
  `book_author` varchar(250) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (`id`)) ENGINE = InnoDB;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($book_sql);
	}
    $category = "wp_bodino_category";
    if($wpdb->get_var("show tables like wp_bodino_category") != $category){
        $category_sql="CREATE TABLE wp_bodino_category ( `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(500) DEFAULT NULL,
  `category_description` varchar(500) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
   `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (`id`)) ENGINE = InnoDB;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($category_sql);
    }
	$student = "wp_bodino_student";
    if($wpdb->get_var("show tables like wp_bodino_student") != $student){
        $student_sql="CREATE TABLE wp_bodino_student ( `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(500) NOT NULL,
  `address` varchar(500) DEFAULT NULL,
  `contact` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `photo` varchar(500) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `student_id` varchar(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (`id`)) ENGINE = InnoDB;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($student_sql);
    }
	$borrowed = "wp_bodino_borrowed";
    if($wpdb->get_var("show tables like wp_bodino_student") != $borrowed){
        $borrowed_sql="CREATE TABLE wp_bodino_borrowed ( `id` int(111) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `book_title` varchar(250) NOT NULL,
  `student_id` int(11) NOT NULL,
   `student_name` varchar(250) NOT NULL,
  `date_borrowed` varchar(250) DEFAULT NULL,
  `date_returned` varchar(250) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `notes` text NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (`id`)) ENGINE = InnoDB;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($borrowed_sql);
    }
}
register_activation_hook(__FILE__,'activated');
?>
<?php
	function custome_style(){ // Start of referencing style.css and fontawesome
	    $plugin_url = plugin_dir_url( __FILE__ );
	    
	    wp_enqueue_style('mystyle',$plugin_url . "/distr/style.css");
	}
    add_action( 'admin_enqueue_scripts', 'custome_style' );	
    // End of referencing style.css and fontawesome
    ?>
<?php
function bodino_book_borrowing_plugin_dashboard(){ // Start of main dashboard includes book
	?>
	
	<?php
	$task=$_GET['task'];
	$id=$_REQUEST['id'];
	if (isset($_POST['savebooks-btn'])){
		//saving book
        global $wpdb;
        if ($id) {
            $wpdb->update('wp_bodino_books', array('title' => $_POST['book_title'], 'description' => $_POST['book_description'],'isbn_num' => $_POST['book_isbn'],'date_published' => $_POST['date_published'],'category_name' => $_POST['category_name'],'book_author' => $_POST['book_author']),array('id'=>$id));
            echo "<input type='hidden' value='updated' id='task_name'>";
        }else{
            $wpdb->insert( 'wp_bodino_books', array('title' => $_POST['book_title'], 'description' => $_POST['book_description'],'isbn_num' => $_POST['book_isbn'],'date_published' => $_POST['date_published'],'category_name' => $_POST['category_name'],'book_author' => $_POST['book_author']));
            echo "<input type='hidden' value='save' id='task_name'>";
        }
    }else{
    	echo "<input type='hidden' value='' id='task_name'>"; // remove the notification if update
    }
    if ($task=="delete_book") {
    	 global $wpdb;
    	 $wpdb->delete( 'wp_bodino_books',array('id'=>$id));
    }
?>
<div class="container">	 <!--Start of container --->
	<div class="bodino_header" > <!--Start of Bodino header --->
		<div class="left" >
			<i class="fas fa-book" style="visibility: hidden;"></i><span id="books_title">Book Borrowing Plugin</span>
			<p style="margin-top: -1px;margin-left: -165px;color:rgba(241, 241, 241,0.4);">by Brixio Bodino</p>
		</div>
		<div id="logo">
   			<img src="http://localhost/wordpress-bodino/wp-content/plugins/book-borrowing-wordpress-plugin_bodino/img/casaul_logo.png" width="70px">
   		</div>
   </div>	 <!--end of Bodino header --->
    <div class="option"> <!--start of option div-->
   		<div id="add">
   			<span id="add_books"><i class="fas fa-plus"></i><a href="<?php  get_admin_url();?>?page=bodino-book-borrowing-plugin&task=add_books">Add Books</a></span>
   		</div>
   		<div id="list">
   			<span id="list_book"><i class="fas fa-list"></i><a href="<?php  get_admin_url();?>?page=bodino-book-borrowing-plugin&task=list_book&page_number=1">List of Books</a></span>
   		</div>
   		<div id="search"> <!--search books  -->
   			<form action="<?php  get_admin_url();?>?page=bodino-book-borrowing-plugin&task=list_book" method="POST">
   			<input type="text" name="query_book" id="query_book" placeholder="Search Books" style="width: 79%;padding: 4px;float: left;height: 80%;margin-top:4px; ">
   			<button type="submit" name="search_book"  id="search_book">Search</button>
   			</form>
   		</div>	<!--end of search books-->
   </div>	<!--end of option div-->
	   	<?php
		$task=$_GET['task'];
		$id=$_GET['id'];
		if ($task=="add_books"  | $task=="edit_book" ){ 
			?>
			<div class="sign"><i class="fas fa-caret-up"></i></div>

			<?php
		 // start of add books form
			global $wpdb; // query database
	    		$results=$wpdb->get_row("select * from wp_bodino_books WHERE id=$id");
	    		$id2=$results->id;
			if ($id2) {
	    		$title=$results->title;
	    		$description= $results->description;
	    		$isbn= $results->isbn_num;
	    		$date_published= $results->date_published;
	    		$category_name= $results->category_name;
	    		$book_author= $results->book_author;
	    		$btn_text="Update Book";
	    		$task_name="Edit Book";
			}else{
				$id2='';
				$title='';
	    		$description= '';
	    		$isbn='';
	    		$category_name="Category";
	    		$date_published='';
	    		$book_author='';
	    		$btn_text="Save Book";
	    		$task_name="Add Book";
			}
		?>	
		<div class="form" id="form" > <!--start of form div -->
		<div id="form_title">
   				<h3 id="title"><?php echo $task_name;?></h3>	
   		</div>
   		<form action="<?php  get_admin_url();?>?page=bodino-book-borrowing-plugin&task=add_books" method="post" >
	    <div class="book" title="Book Title" style="">
	    	<input type="hidden" name="id" value="<?php echo $id2 ?>" id='book_id'>
	    	<input type="text" name="book_title" placeholder="Book Title" style="float: right;" value="<?php echo $title ?>" autocomplete="off">	
		</div>
		 <div class="book" title="Book Description">
	    	<textarea name="book_description" placeholder="Book Description" style="max-width:100%;width:100%;max-height: 80px;height: 80px;margin-top: -10px;" ><?php echo $description;?></textarea>
	    </div>
	    <div class="book" title="Isbn Number">
	    	<input type="text" name="book_isbn" placeholder="Isbn Number"  style="float:right;" value="<?php echo $isbn ?>" autocomplete="off">
	    </div>
	    <div class="book" title="Date Published">
	    	<input type="date" name="date_published"   style="float:right;" value="<?php echo $date_published ?>" autocomplete="off">
	    </div>
	    <div class="book" title="Book Author" style="margin-top: 10px;">
	    	<input type="text" name="book_author"  placeholder="Book Author" style="float:right;" value="<?php echo $book_author ?>" autocomplete="off">
	    </div>
	      <div class="book" title="Category" style="margin-top: 10px;margin-bottom: 30px">
	    	<select  name="category_name" >
	    		<option selected><?php 
	    		if (isset($category_name)){
	    			echo $category_name;
	    		}else{
	    			echo $category_name;
	    		};?></option>
	    		<?php
	    		global $wpdb;
	    		$results2=$wpdb->get_results("select * from wp_bodino_category order by category_name");
	    		foreach ($results2 as $row2) {
	  			?>
	  			<option value="<?php echo $row2->category_name;?>" style="color:rgb(35, 40, 45);height: 100px;"> <?php echo $row2->category_name;?></option>
	  			<?php
	    		}
	    		?>
	    	</select>
	    </div>
	     <input type="submit" name="savebooks-btn" id="save_btn"    style="" value="<?php echo $btn_text ?>">
	 </form>
   	</div>	<!--end of form div -->
	   	<div class="recently">
	   		<div id="recent_title">
	   			<span>Recently Added </span>
	   			</div>
	   				<?php
	   				$results=$wpdb->get_results("select * from wp_bodino_books	  ORDER by date_added DESC LIMIT 4" );
	   				foreach ($results as $row ){ 
	   			 ?>
	   			 <div  style="padding: 5px;background: #eee;width: 90%;margin: 0 auto;margin-bottom: 10px;height:auto;min-height: 70px;">
	   				<div style="width: 50px;float:left;height: 50px;">	
	   					<img src="<?php echo plugins_url();?>/book-borrowing-wordpress-plugin_bodino/img/book_icon.png" width="45px" height="45px" style="	">
	   				</div>
	   				<div style="width: 75%;float:left;min-height: 50px;height:auto;word-wrap: break-word;padding-left: 0px;">
	   				<span style="font-weight: 700;">Book Title</span><br>
	   					<span><?php echo $row->title; ?></span>
	   				</div>
	   				<!--
	   					<img src="<?php // echo $row->photo;?>" width="45px" height="45px" style="border-radius: 50%;">
	   				
	   					<p> <?php // echo $row->name;?></p> -->
	   			</div>
	   				<?php }?>	
	   		</div>
	<?php
	} // end of book form
   	if ($task=="delete_book"  | $task=="list_book" | $task==""){ 
   		?>
   		<div class="sign2" ><i class="fas fa-caret-up"></i></div>
   		
   		<?php
	   	global $wpdb;
   		$results=$wpdb->get_results("select * from wp_bodino_books  ORDER by date_added DESC LIMIT 10"); 
   		// Start of Search //
   		$download=$_REQUEST['download'];
   		if ($download=="pdf") {
   			include('download_pdf.php');
   		}
   		$query=$_POST['query_book'];
   		if (isset($_POST['search_book'])) {
   			if ($query=="") {
   				echo "<p id='total' style='color:rgb(200, 35, 51);'> Please provide word to search</p>";
   				$results=$wpdb->get_results("select * from wp_bodino_books  ORDER by date_added DESC LIMIT 10"); 
   				$row3=1;
   			}else{
   			 $results=$wpdb->get_results("SELECT  * From wp_bodino_books  WHERE title like '%".$query."%'");
   			 $row3=$wpdb->get_var("SELECT COUNT(*)FROM wp_bodino_books WHERE title like '%".$query."%'");
   			echo "<p id='total'>".$row3." books found </p>";
   		}
   		}else{
   		$row3=$wpdb->get_var("SELECT COUNT(*)FROM wp_bodino_books WHERE title like '%".$query."%'");
   		}
   		// End of Search //
 
   		// Start of Pagination //
   		$page_number=$_REQUEST['page_number'];
   		$per_page=10;
   		if ($page_number) {
   			if ($page_number=="") {
   				$page_number==1;
   			}else if ($page_number ==1) {
   				$page_number=0;
   			}else if ($page_number%2) {
   				$page_number=($page_number * $per_page)- $per_page;
   			}else{
   				$page_number=($page_number * $per_page)- $per_page;
   			}
   			$results=$wpdb->get_results("SELECT  * From wp_bodino_books ORDER by date_added DESC LIMIT $per_page offset $page_number");
   			//echo "<p id='total'>Showing ".$per_page . " records from ".$row3." total books found </p>";

   			// Start of showing page number out of total page //
			$total_paginate=$row3/$per_page;
			$remainder2=$row3%$per_page;
			$page_number2=$_REQUEST['page_number'];
			$remainder2=$row3%$per_page;
			for ($number=1; $number<=$total_paginate ; $number++) { // start of for loop
				} //end of for loop
				if ($remainder2) {
					echo "<p id='total'>Showing page ".$page_number2 . " of ".$number."</p>";
				}else{
					echo "<p id='total'>Showing page ".$page_number2 . " of ".($number-1)."</p>";
				} // End of showing page number out of total page //
   		}	// end of if ($page_number)
   		// End of Pagination //
   	?>
   		<div class="table_container" style="margin-top: -10px;">
			<table>
				<tr>
					<th >Title</th>
					<th>Description</th>
					<th>Isbn Number</th>
					<th>Date Published</th>
					<th>Category </th>
					<th>Author Name</th>
					<th colspan="2" style="text-align: center;">Action</th>
				</tr>
			<?php
   				 foreach ($results as $row ){ // foreach
			?>
				<tr style="text-align: center;">
					<td><?php  echo $row->title;?></td>
					<td><?php  echo $row->description;?></td>
					<td><?php  echo $row->isbn_num;?></td>
					<td><?php  echo $row->date_published;?></td>
					<td><?php  echo $row->category_name;?></td>
					<td><?php  echo $row->book_author;?></td>
					<td><a href="<?php  get_admin_url();?>?page=bodino-book-borrowing-plugin&task=edit_book&id=<?php  echo $row->id;?>"><button class="edit" id="edit_book">Edit</button></a></td>
					<td><a href="<?php  get_admin_url();?>?page=bodino-book-borrowing-plugin&task=delete_book&id=<?php echo $row->id;?>"><button class="delete">Delete</button></a></td>
				<?php 
				}	
				if ($row3<=0){
				?>
				<td colspan="7" style="text-align: center;padding-top: 50px;"><span id='no_record'>-------- No Book Found --------</span></td>
				<?php
					}
				?>
				</tr>
				
			</table>	
</div>	 <!-- end of container --->
</div>
<div class="bottom_container" >
<div id="paginate">	 <!-- start displaying pagination button --->	
<?php
$row4=$wpdb->get_var("SELECT COUNT(*)FROM wp_bodino_books");
$per_page=10; 
$total_paginate=$row4/$per_page;
$remainder=$row4%$per_page;
$page_number=$_REQUEST['page_number'];
for ($button=1; $button<=$total_paginate ; $button++) { // start of for loop
?>	
<a href="<?php  get_admin_url();?>?page=bodino-book-borrowing-plugin&page_number=<?php echo $button;  ?>" id="<?php echo $button;?>"><?php echo $button?></a>
<?php 
	} //end of for loop
	if ($remainder) { // start of if remainder
	?>
	<a href="<?php  get_admin_url();?>?page=bodino-book-borrowing-plugin&page_number=<?php echo $button;  ?>" id="<?php echo $button;?>"><?php echo $button?></a>
	
	<?php
	}else{
	}	// end of if remainder
?>

<?php 
	} // end of if	task=list
?>
<input type="hidden" id="page_num2" value="<?php echo $page_number ;?>">

</div>	<!-- end of displaying pagination button --->	

</div>
<script type="text/javascript">
	var current=document.getElementById('page_num2').value;
	window.onload=function(){
		if (current==""){
			document.getElementById("1").style.backgroundColor="rgb(200, 35, 51)";
		}else{
			document.getElementById(current).style.backgroundColor="rgb(200, 35, 51)";
		}
	}
</script>
  <?php
}     /* end of books menu */
function book_borrowing_category(){ // Start of category
	$task=$_GET['task'];
	//saving record
	global $wpdb;
    $id=$_REQUEST['id'];
	if (isset($_POST['save_category'])){
        if ($id) {
             $wpdb->update( 'wp_bodino_category', array('category_name' => $_POST['category_name'], 'category_description' => $_POST['category_description']),array('id'=>$id));
        }else{
            $wpdb->insert( 'wp_bodino_category', array('category_name' => $_POST['category_name'], 'category_description' => $_POST['category_description']));
        }
    } // End of saving record
     if ($task=="delete_category") {
    	 $wpdb->delete( 'wp_bodino_category',array('id'=>$id));
    }
	?>
	<div class="container">	 <!--Start of container --->
		<div class="bodino_header" > <!--Start of Bodino header --->
			<div class="left" >
				<i class="fas fa-book" style="visibility: hidden;"></i><span id="books_title">Book Borrowing Plugin</span>
			</div>
			<div id="logo">
   				<img src="http://localhost/wordpress-bodino/wp-content/plugins/book-borrowing-wordpress-plugin_bodino/img/casaul_logo.png" width="70px">
   			</div>
  		 </div>	 <!--end of Bodino header --->
   		<div class="option"> <!--start of option div-->
	   		<div id="add">
	   			<span id="add_books"><i class="fas fa-plus"></i><a href="<?php  get_admin_url();?>?page=bodino-book-borrowing-plugin_category&task=add_category">Add Category</a></span>
	   		</div>
	   		<div id="list">
	   			<span id="list_book"><i class="fas fa-list"></i><a href="<?php  get_admin_url();?>?page=bodino-book-borrowing-plugin_category&task=category_list">Category List</a></span>
	   		</div>
	   		<div id="search"> <!--search books  -->
	   			<form action="<?php  get_admin_url();?>?page=bodino-book-borrowing-plugin_category&task=category_list" method="post">
	   			<input type="text" name="query_category" placeholder="Search Category" style="width: 79%;padding: 4px;float: left;height: 80%;margin-top:4px; ">
	   			<input type="submit" name="search_category" value="search" id="search_book">
	   			</form>
	   		</div>	<!--end of search books-->
   		</div>	<!--end of option div-->
	   	<?php
		$task=$_GET['task'];
		$id=$_GET['id'];
		if ($task=="add_category"  | $task=="edit_category" | $task==""){ 
			?>
			<div class="sign"><i class="fas fa-caret-up"></i></div>
			<?php
		 // start of add books form
			global $wpdb; // query database
	    		$results=$wpdb->get_row("select * from wp_bodino_category WHERE id=$id");
	    		$id2=$results->id;
			if ($id2) {
	    		$category_name=$results->category_name;
	    		$category_description= $results->category_description;
	    		$btn_text="Update Category";
	    		$task_name="Edit Category";
			}else{
				$id2='';
				$category_name='';
	    		$category_description= '';
	    		$btn_text="Save Category";
	    		$task_name="Add Category";
			}
		?>	
	<div class="form" id="form"> <!--start of form div -->
		<div id="form_title">
   				<h3 id="title"><?php echo $task_name;?></h3>	
   		</div>
   		<form action="<?php  get_admin_url();?>?page=bodino-book-borrowing-plugin_category&task=add_category" method="post">
		    <div class="book" title="Category" style="">
		    	<input type="hidden" name="id" value="<?php echo $id2 ?>" id='book_id'>
		    	<input type="text" name="category_name" placeholder="Category" style="float: right;" value="<?php echo $category_name ?>" autocomplete="off">	
			</div>
			 <div class="book" title="Category Description">
		    	<textarea name="category_description" placeholder="Category Description" value="<?php echo $category_description; ?>"  style="max-width:100%;width:100%;max-height: 160px;height: 160px;margin-top: -10px;"></textarea>
		    </div>
		     <input type="submit" name="save_category" id="save_btn"    style="margin-top: 0px;" value="<?php echo $btn_text ?>" >
	 	</form>
   	</div>	<!--end of form div -->
	   	<div class="recently">
	   		<div id="recent_title">
	   			<span>Recently Added </span>
	   			</div>
	   			<?php
	   				$results=$wpdb->get_results("select * from wp_bodino_category ORDER by date_added DESC LIMIT 4" );
	   				foreach ($results as $row ){ 
	   			 ?>
	   			 <div  style="padding: 5px;background: #eee;width: 90%;margin: 0 auto;margin-bottom: 10px;height:auto;min-height: 70px;">
	   				<div style="width: 50px;float:left;height: 50px;">	
	   					<img src="<?php echo plugins_url();?>/book-borrowing-wordpress-plugin_bodino/img/category-icon.png" width="45px" height="45px" style="	">
	   				</div>
	   				<div style="width: 75%;float:left;min-height: 50px;height:auto;word-wrap: break-word;padding-left: 0px;">
	   				<span style="font-weight: 700;">Category Name</span><br>
	   					<span><?php echo $row->category_name; ?></span>
	   				</div>
	   				<!--
	   					<img src="<?php // echo $row->photo;?>" width="45px" height="45px" style="border-radius: 50%;">
	   				
	   					<p> <?php // echo $row->name;?></p> -->
	   			</div>
	   				<?php }?>	
	   		</div>
	<?php
	} // end of book form
   	if ($task=="delete_category"  | $task=="category_list" ){ 
   		?>
   		<div class="sign2" ><i class="fas fa-caret-up"></i></div>
   		<?php
	   		global $wpdb;
   		$results=$wpdb->get_results("select * from wp_bodino_category  ORDER by date_added DESC LIMIT 10"); 
   		// Start of Search //
   		$query=$_POST['query_category'];
   		if (isset($_POST['search_category'])) {
   			if ($query=="") {
   				echo "<p id='total' style='color:rgb(200, 35, 51);'> Please provide word to search</p>";
   				$results=$wpdb->get_results("select * from wp_bodino_category  ORDER by date_added DESC LIMIT 10"); 
   				$row3=1;
   			}else{
   			 $results=$wpdb->get_results("SELECT  * From wp_bodino_category  WHERE category_name like '%".$query."%'");
   			 $row3=$wpdb->get_var("SELECT COUNT(*)FROM wp_bodino_category WHERE category_name like '%".$query."%'");
   			echo "<p id='total'>".$row3." category found </p>";
   		}
   		}else{
   		$row3=$wpdb->get_var("SELECT COUNT(*)FROM wp_bodino_category WHERE category_name like '%".$query."%'");
   		}
   		// End of Search //
 
   		// Start of Pagination //
   		$page_number=$_REQUEST['page_number'];
   		$per_page=10;
   		if ($page_number) {
   			if ($page_number=="") {
   				$page_number==1;
   			}else if ($page_number ==1) {
   				$page_number=0;
   			}else if ($page_number%2) {
   				$page_number=($page_number * $per_page)- $per_page;
   			}else{
   				$page_number=($page_number * $per_page)- $per_page;
   			}
   			$results=$wpdb->get_results("SELECT  * From wp_bodino_category ORDER by date_added DESC LIMIT $per_page offset $page_number");
   			//echo "<p id='total'>Showing ".$per_page . " records from ".$row3." total books found </p>";

   			// Start of showing page number out of total page //
			$total_paginate=$row3/$per_page;
			$remainder2=$row3%$per_page;
			$page_number2=$_REQUEST['page_number'];
			$remainder2=$row3%$per_page;
			for ($number=1; $number<=$total_paginate ; $number++) { // start of for loop
				} //end of for loop
				if ($remainder2) {
					echo "<p id='total'>Showing page ".$page_number2 . " of ".$number."</p>";
				}else{
					echo "<p id='total'>Showing page ".$page_number2 . " of ".($number-1)."</p>";
				} // End of showing page number out of total page //
   		}	// end of if ($page_number)
   		// End of Pagination //

   		?>
   	
   		<div class="table_container">
		<table>
			<tr>
				<th >Category Name</th>
				<th>Category Description</th>
				<th colspan="2" style="text-align: center;">Action</th>
			</tr>
			<?php
   				 foreach ($results as $row ){ // foreach
			?>
			<tr style="text-align: center;">
				<td><?php  echo $row->category_name;?></td>
				<td><?php  echo $row->category_description;?></td>
				<td style="text-align: center;"><a href="<?php  get_admin_url();?>?page=bodino-book-borrowing-plugin_category&task=edit_category&id=<?php  echo $row->id;?>"><button class="edit" id="edit_book">Edit</button></a></td>
				<td style="text-align: center;"><a href="<?php  get_admin_url();?>?page=bodino-book-borrowing-plugin_category&task=delete_category&id=<?php echo $row->id;?>"><button class="delete">Delete</button></a></td>
				<?php 
				}	
				if ($row3<=0){
				?>
				<td colspan="6" style="text-align: center;padding-top: 50px;"><span id='no_record'>-------- No Result Found --------</span></td>
				<?php
				}
				?>
				</tr>
				
		</table>	
		
</div>	 <!-- end of container --->
</div>
<div id="paginate">		 <!-- start displaying pagination button --->	
<?php
$row4=$wpdb->get_var("SELECT COUNT(*)FROM wp_bodino_student");
$per_page=10; 
$total_paginate=$row4/$per_page;
$remainder=$row4%$per_page;
$page_number=$_REQUEST['page_number'];
for ($button=1; $button<=$total_paginate ; $button++) { // start of for loop
?>	
<a href="<?php  get_admin_url();?>?page=bodino-book-borrowing-plugin_category&task=category_list&page_number=<?php echo $button;  ?>" id="<?php echo $button;?>"><?php echo $button?></a>
<?php 
	} //end of for loop
	if ($remainder) { // start of if remainder
	?>
	<a href="<?php  get_admin_url();?>?page=bodino-book-borrowing-plugin_category&task=category_list&page_number=<?php echo $button;  ?>" id="<?php echo $button;?>"><?php echo $button?></a>
	
	<?php
	}else{
	}	// end of if remainder
?>

<?php 
	} // end of if	task=list
?>
<input type="hidden" id="page_num2" value="<?php echo $page_number ;?>">
</div>	<!-- end of displaying pagination button --->	
<script type="text/javascript">
	var current=document.getElementById('page_num2').value;
	window.onload=function(){
		if (current==""){
			document.getElementById("1").style.backgroundColor="rgb(200, 35, 51)";
		}else{
			document.getElementById(current).style.backgroundColor="rgb(200, 35, 51)";
		}
	}
</script>
<?php
}	// End of category
function book_borrowing_students(){
	$task=$_GET['task'];
	global $wpdb;
	//saving record
	if (isset($_POST['save-btn'])){
        
        $id=$_REQUEST['id'];
        if ($id) {
             $wpdb->update( 'wp_bodino_student', array('name' => $_POST['student_name'], 'address' => $_POST['student_address'],'contact' => $_POST['student_contact'],'email' => $_POST['student_email'],'photo' => $_POST['student_photo']),array('id'=>$id));
        }else{
            $wpdb->insert( 'wp_bodino_student', array('name' => $_POST['student_name'], 'address' => $_POST['student_address'],'contact' => $_POST['student_contact'],'email' => $_POST['student_email'],'photo' => $_POST['student_photo']));
        }
    } // End of saving record
    if ($task=="delete_student") {
    	$id=$_REQUEST['id'];
    	global $wpdb;
    	$wpdb->delete( 'wp_bodino_student',array('id'=>$id));
    }
?>
<div class="container">
	<div class="bodino_header" > <!--Start of Bodino header --->
		<div class="left" >
			<i class="fas fa-book" style="visibility: hidden;"></i><span id="books_title">Book Borrowing Plugin</span>
		</div>
		<div id="logo">
   			<img src="http://localhost/wordpress-bodino/wp-content/plugins/book-borrowing-wordpress-plugin_bodino/img/casaul_logo.png" width="70px">
   		</div>
   </div>	 <!--end of Bodino header --->
    <div class="option"> <!--start of option div-->
   		<div id="add">
   			<span id="add_books"><i class="fas fa-plus"></i><a href="<?php  get_admin_url();?>?page=bodino_book_borrowing_plugin_students&task=add_student">Add Student</a></span>
   		</div>
   		<div id="list">
   			<span id="list_book"><i class="fas fa-list"></i><a href="<?php  get_admin_url();?>?page=bodino_book_borrowing_plugin_students&task=student_list">List of Student</a></span>
   		</div>
   		<div id="search"> <!--search books  -->
   			<form action="<?php  get_admin_url();?>?page=bodino_book_borrowing_plugin_students&task=student_list" method="post">
   			<input type="text" name="query_student" placeholder="Search Student" style="width: 79%;padding: 4px;float: left;height: 80%;margin-top:4px; ">
   			<input type="submit" name="search_student" value="search" id="search_book">
   		</form>
   		</div>
   	</div>
		<?php
		$task=$_GET['task'];
		$id=$_GET['id'];
		if ($task=="add_student" | $task=="" | $task=="edit_student"){ // start of add student form
			?>
			<div class="sign" ><i class="fas fa-caret-up"></i></div>
			<?php
			global $wpdb; // query database
	    		$results=$wpdb->get_row("select * from wp_bodino_student WHERE id=$id");
	    		$id2=$results->id;
			if ($id2) {
	    		$name=$results->name;
	    		$address= $results->address;
	    		$contact= $results->contact;
	    		$email= $results->email;
	    		$photo= $results->photo;
	    		$btn_text="Update Student";
	    		$task_name="Edit Student";
			}else{
				$name='';
				$address='';
	    		$contact= '';
	    		$email='';
	    		$photo=plugins_url().'/book-borrowing-wordpress-plugin_bodino/img/avatar-blank.png';
	    		$btn_text="Save Student";
	    		$task_name="Add Student";
			}
		?>	
		<div class="form" id="form" >  <!--start of form div -->
			
		<div id="form_title">
   				<h3 id="title"><?php echo $task_name;?></h3>	
   		</div>
    		<form action="<?php get_admin_url();?>?page=bodino_book_borrowing_plugin_students&task=add_student" method="POST">
    			<div class="photo_container" style="height: 160px;">
    				<div id="student_photo">
	    				<img id='photo' height="auto" src="<?php echo $photo ?>">
	    				<img id='photo2' height="auto" style="display: none;" src="<?php echo plugins_url();?>/book-borrowing-wordpress-plugin_bodino/img/avatar-blank.png">
	    				<p id="select_photo" >Select Photo</p>
    				</div>
    			</div><br>
	    				
    			<input type="hidden" name="id" value="<?php echo $id2 ?>">
    			<input type="hidden" name="student_photo" id="photo_url" placeholder="Enter Name" style="padding: 1px;width: 100%" autocomplete="off" value= "<?php echo $photo ?>">
			    <div class="book" title="Student Name" >
			    	<input type="text" name="student_name" placeholder="Enter Name" style="float: right;margin-bottom: 30px;" autocomplete="off" value="<?php echo $name ?>">
				</div>
				 <div class="book" title="Student Address">
			    	<input type="text" name="student_address" placeholder="Enter  Address" style="float:right;margin-bottom: 30px;" autocomplete="off" value="<?php echo $address ?>">
			    </div>
			    <div class="book">
			    	<input type="text" name="student_contact" placeholder="Enter Contact Number" style="float:right;margin-bottom: 30px;" autocomplete="off" value="<?php echo $contact ?>" >
			    </div>
			    <div class="book" title="Email Account">
			    	<input type="email" name="student_email" placeholder="Enter Email Address" style="float:right;margin-bottom: 30px;" autocomplete="off" value="<?php echo $email ?>">
			    </div>
			      <input type="submit" name="save-btn" id="save_btn"    style="margin-top: -20px;" value="<?php echo $btn_text ?>" >
	 		</form>
		</div>
			<div class="recently" >
	   			<div id="recent_title">
	   				<span>Recently Added </span>
	   			</div>
	   			
	   			<?php
	   				$results=$wpdb->get_results("select * from wp_bodino_student  ORDER by date_added DESC LIMIT 4" );
	   				foreach ($results as $row ){ 
	   			 ?>
	   			 <div  style="padding: 5px;background: #eee;width: 90%;margin: 0 auto;margin-bottom: 10px;height:auto;min-height: 70px;">
	   				<div style="width: 50px;float:left;height: 50px;">	
	   					<img src="<?php echo $row->photo;?>" width="45px" height="45px" style="border-radius: 50%;	border: 1px solid rgba(35, 40, 45,0.2);">
	   				</div>
	   				<div style="width: 75%;float:left;min-height: 50px;height:auto;word-wrap: break-word;padding-left: 0px;">
	   				<span style="font-weight: 700;">Student Name</span><br>
	   					<span><?php echo $row->name; ?></span>
	   				</div>
	   				<!--
	   					<img src="<?php // echo $row->photo;?>" width="45px" height="45px" style="border-radius: 50%;">
	   				
	   					<p> <?php // echo $row->name;?></p> -->
	   			</div>
	   				<?php }?>	
	   		</div>
		<?php
		}	// end of add students form
		if ($task=="student_list" | $task=="delete_student"){ // Start of view students list
			global $wpdb;
		?>	
		<div class="sign2" style="margin-top: -25px" ><i class="fas fa-caret-up" ></i></div>
	

		<?php
	   	global $wpdb;
   		$results=$wpdb->get_results("select * from wp_bodino_student  ORDER by date_added DESC LIMIT 10"); 
   		// Start of Search //
   		$query=$_POST['query_student'];
   		if (isset($_POST['search_student'])) {
   			if ($query=="") {
   				echo "<p id='total' style='color:rgb(200, 35, 51);'> Please provide word to search</p>";
   				$results=$wpdb->get_results("select * from wp_bodino_student  ORDER by date_added DESC LIMIT 10"); 
   				$row3=1;
   			}else{
   			 $results=$wpdb->get_results("SELECT  * From wp_bodino_student  WHERE name like '%".$query."%'");
   			 $row3=$wpdb->get_var("SELECT COUNT(*)FROM wp_bodino_student WHERE name like '%".$query."%'");
   			echo "<p id='total'>".$row3." students found </p>";
   		}
   		}else{
   		$row3=$wpdb->get_var("SELECT COUNT(*)FROM wp_bodino_student WHERE name like '%".$query."%'");
   		}
   		// End of Search //
 
   		// Start of Pagination //
   		$page_number=$_REQUEST['page_number'];
   		$per_page=10;
   		if ($page_number) {
   			if ($page_number=="") {
   				$page_number==1;
   			}else if ($page_number ==1) {
   				$page_number=0;
   			}else if ($page_number%2) {
   				$page_number=($page_number * $per_page)- $per_page;
   			}else{
   				$page_number=($page_number * $per_page)- $per_page;
   			}
   			$results=$wpdb->get_results("SELECT  * From wp_bodino_student ORDER by date_added DESC LIMIT $per_page offset $page_number");
   			//echo "<p id='total'>Showing ".$per_page . " records from ".$row3." total books found </p>";

   			// Start of showing page number out of total page //
			$total_paginate=$row3/$per_page;
			$remainder2=$row3%$per_page;
			$page_number2=$_REQUEST['page_number'];
			$remainder2=$row3%$per_page;
			for ($number=1; $number<=$total_paginate ; $number++) { // start of for loop
				} //end of for loop
				if ($remainder2) {
					echo "<p id='total'>Showing page ".$page_number2 . " of ".$number."</p>";
				}else{
					echo "<p id='total'>Showing page ".$page_number2 . " of ".($number-1)."</p>";
				} // End of showing page number out of total page //
   		}	// end of if ($page_number)
   		// End of Pagination //
   	?>
   		<div class="table_container">
			<table>
				<tr >
					<th>Student Id</th>
					<th>Name</th>
					<th>Address</th>
					<th>Contact</th>
					<th>Email</th>
					<th style="text-align: center;">Photo</th>
					<th colspan="2" style="text-align: center;">Action</th>
				</tr>
			<?php
   				 foreach ($results as $row ){
			?>
				<tr style="text-align: center;">
					<td><?php echo $row->id;?></td>
					<td><?php echo $row->name;?></td>
					<td><?php echo $row->address;?></td>
					<td><?php echo $row->contact;?></td>
					<td><?php echo $row->email;?></td>
					<td><img src="<?php echo $row->photo;?>" width="45px" height="45px" style="border-radius: 50%;"></td>
					<td style="text-align: center;"><a href="<?php  get_admin_url();?>?page=bodino_book_borrowing_plugin_students&task=edit_student&id=<?php  echo $row->id;?>"><button class="edit" id="edit_book">Edit</button></a></td>
					<td style="text-align: center;"><a href="<?php  get_admin_url();?>?page=bodino_book_borrowing_plugin_students&task=delete_student&id=<?php echo $row->id;?>"><button class="delete">Delete</button></a></td>
				<?php 
				}	
				if ($row3<=0){
				?>
				<td colspan="7" style="text-align: center;padding-top: 50px;"><span id='no_record'>-------- No Student Found --------</span></td>
				<?php
					}
				?>
				</tr>
				
			</table>	
</div>	 <!-- end of container --->
</div>
<div id="paginate">		 <!-- start displaying pagination button --->	
<?php
$row4=$wpdb->get_var("SELECT COUNT(*)FROM wp_bodino_student");
$per_page=10; 
$total_paginate=$row4/$per_page;
$remainder=$row4%$per_page;
$page_number=$_REQUEST['page_number'];
for ($button=1; $button<=$total_paginate ; $button++) { // start of for loop
?>	
<a href="<?php  get_admin_url();?>?page=bodino_book_borrowing_plugin_students&task=student_list&page_number=<?php echo $button;  ?>" id="<?php echo $button;?>"><?php echo $button?></a>
<?php 
	} //end of for loop
	if ($remainder) { // start of if remainder
	?>
	<a href="<?php  get_admin_url();?>?page=bodino_book_borrowing_plugin_students&task=student_list&page_number=<?php echo $button;  ?>" id="<?php echo $button;?>"><?php echo $button?></a>
	
	<?php
	}else{
	}	// end of if remainder
?>

<?php 
	} // end of if	task=list
?>
<input type="hidden" id="page_num2" value="<?php echo $page_number ;?>">
</div>	<!-- end of displaying pagination button --->	
<script type="text/javascript">
	var current=document.getElementById('page_num2').value;
	window.onload=function(){
		if (current==""){
			document.getElementById("1").style.backgroundColor="rgb(200, 35, 51)";
		}else{
			document.getElementById(current).style.backgroundColor="rgb(200, 35, 51)";
		}
	}
</script>
	<script type="text/javascript">
	jQuery(document).ready(function($){
	if ($('#photo_url').val()=="") {
    	$('#photo2').css('display','inline-block');
    	$('#photo').css('display','none');
    }else{
    	$('#photo2').css('display','none');
    	$('#photo').css('display','inline-block');
    }
    $('#select_photo').click(function(e) {
        e.preventDefault();
        var image = wp.media({ 
            title: 'Upload Image',
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
        .on('select', function(e){
            // This will return the selected image from the Media Uploader, the result is an object
            var uploaded_image = image.state().get('selection').first();
            // We convert uploaded_image to a JSON object to make accessing it easier
            // Output to the console uploaded_image
            console.log(uploaded_image);
            var image_url = uploaded_image.toJSON().url;
            // Let's assign the url value to the input field
            $('#photo').attr("src",image_url);
            $('#photo2').attr("src",image_url);
            $('#photo_url').val(image_url);
        });
    });
});
</script>
    <?php 
        wp_enqueue_script('jquery');
        wp_enqueue_media(); // This will enqueue the Media Uploader script
	}
	function book_borrowing_borrowed(){
		$task=$_REQUEST['task'];
		$id=$_REQUEST['id'];
		global $wpdb;
		$wpdb->delete('wp_bodino_borrowed',array('id'=>$id));
		?>
	<div class="container">
		<div class="bodino_header" > <!--Start of Bodino header --->
			<div class="left" >
				<i class="fas fa-book" style="visibility: hidden;"></i><span id="books_title">Book Borrowing Plugin</span>
			</div>
			<div id="logo">
	   			<img src="http://localhost/wordpress-bodino/wp-content/plugins/book-borrowing-wordpress-plugin_bodino/img/casaul_logo.png" width="70px">
	   		</div>
	   </div>	 <!--end of Bodino header --->
    <div class="option"> <!--start of option div-->
   		<div id="search"> <!--search books  -->
   			<form action="<?php get_admin_url();?>?page=bodino_book_borrowing_plugin_borrowed" method="post">
   			<input type="text" name="query_borrowed" placeholder="Search Borrowed books" style="width: 79%;padding: 4px;float: left;height: 80%;margin-top:4px; ">
   			<input type="submit" name="search_borrowed" value="search" id="search_book">
   		</form>
   		</div>	<!--end of search books-->
   	</div>
   <div class="table_container">
	<?php
	   	global $wpdb;
   		$results=$wpdb->get_results("select * from wp_bodino_borrowed  ORDER by date_added DESC LIMIT 10"); 
   		// Start of Search //
   		$query=$_POST['query_borrowed'];
   		if (isset($_POST['search_borrowed'])) {
   			if ($query=="") {
   				echo "<p id='total' style='color:rgb(200, 35, 51);'> Please provide word to search</p>";
   				$results=$wpdb->get_results("select * from wp_bodino_borrowed  ORDER by date_added DESC LIMIT 10"); 
   				$row3=1;
   			}else{
   			 $results=$wpdb->get_results("SELECT  * From wp_bodino_borrowed  WHERE  student_name like '%".$query."%'");
   			 $row3=$wpdb->get_var("SELECT COUNT(*)FROM wp_bodino_borrowed WHERE student_name like '%".$query."%'");
   			echo "<p id='total'>".$row3." record found </p>";
   		}
   		}else{
   		$row3=$wpdb->get_var("SELECT COUNT(*)FROM wp_bodino_borrowed WHERE student_name like '%".$query."%'");
   		}
   		// End of Search //
 
   		// Start of Pagination //
   		$page_number=$_REQUEST['page_number'];
   		$per_page=10;
   		if ($page_number) {
   			if ($page_number=="") {
   				$page_number==1;
   			}else if ($page_number ==1) {
   				$page_number=0;
   			}else if ($page_number%2) {
   				$page_number=($page_number * $per_page)- $per_page;
   			}else{
   				$page_number=($page_number * $per_page)- $per_page;
   			}
   			$results=$wpdb->get_results("SELECT  * From wp_bodino_borrowed ORDER by date_added DESC LIMIT $per_page offset $page_number");
   			//echo "<p id='total'>Showing ".$per_page . " records from ".$row3." total books found </p>";

   			// Start of showing page number out of total page //
			$total_paginate=$row3/$per_page;
			$remainder2=$row3%$per_page;
			$page_number2=$_REQUEST['page_number'];
			$remainder2=$row3%$per_page;
			for ($number=1; $number<=$total_paginate ; $number++) { // start of for loop
				} //end of for loop
				if ($remainder2) {
					echo "<p id='total'>Showing page ".$page_number2 . " of ".$number."</p>";
				}else{
					echo "<p id='total'>Showing page ".$page_number2 . " of ".($number-1)."</p>";
				} // End of showing page number out of total page //
   		}	// end of if ($page_number)
   		// End of Pagination //
   	?>
			<table>
				<tr >
					<th>Book Id</th>
					<th>Book Title</th>
					<th>Student Id</th>
					<th>Student Name</th>
					<th>Date Borrowed</th>
					<th>Notes</th>
					<th style="text-align: center;">Action</th>
				</tr>
			<?php
   				 foreach ($results as $row ){ //start of foreach
			?>
				<tr style="text-align: center;">
					<td><?php echo $row->book_id;?></td>
					<td><?php echo $row->book_title;?></td>
					<td><?php echo $row->student_id;?></td>
					<td><?php echo $row->student_name;?></td>
					<td><?php echo $row->date_borrowed;?></td>
					<td><?php echo $row->notes;?></td>
					<td style="text-align: center;"><a href="<?php get_admin_url();?>?page=bodino_book_borrowing_plugin_borrowed&task=return_book&id=<?php echo $row->id;?>"><button class='delete'>Return Book</button></a></td>
				
				<?php 
				}	
				if ($row3<=0){
				?>
				<td colspan="7" style="text-align: center;padding-top: 50px;"><span id='no_record'>-------- No Record Found --------</span></td>
				<?php
					}
				?>
				</tr>
</table>
</div>
</div>
<div id="paginate" >		 <!-- start displaying pagination button --->	
<?php
$row4=$wpdb->get_var("SELECT COUNT(*)FROM wp_bodino_borrowed");
$per_page=10; 
$total_paginate=$row4/$per_page;
$remainder=$row4%$per_page;
$page_number=$_REQUEST['page_number'];
for ($button=1; $button<=$total_paginate ; $button++) { // start of for loop
?>	
<a href="<?php  get_admin_url();?>?page=bodino_book_borrowing_plugin_borrowed&page_number=<?php echo $button;  ?>" id="<?php echo $button;?>"><?php echo $button?></a>
<?php 
	} //end of for loop
	if ($remainder) { // start of if remainder
	?>
	<a href="<?php  get_admin_url();?>?page=bodino_book_borrowing_plugin_borrowed&page_number=<?php echo $button;  ?>" id="<?php echo $button;?>"><?php echo $button?></a>
	
	<?php
	}else{
	}	// end of if remainder
?>


<input type="hidden" id="page_num2" value="<?php echo $page_number ;?>">
</div>	<!-- end of displaying pagination button --->	
<script type="text/javascript">
	var current=document.getElementById('page_num2').value;
	window.onload=function(){
		if (current==""){
			document.getElementById("1").style.backgroundColor="rgb(200, 35, 51)";
		}else{
			document.getElementById(current).style.backgroundColor="rgb(200, 35, 51)";
		}
	}
</script>
<?php 
	} // end of if	task=list
?>
<?php

function book_borrowing_form(){
	if (isset($_POST['book_now'])){
		//saving book borrowing
        global $wpdb;
            $wpdb->insert( 'wp_bodino_borrowed', array('book_id' => $_POST['book_id'],'book_title' => $_POST['book_title'],  'student_id' => $_POST['student_id'],'student_name' => $_POST['student_name'], 'date_borrowed' => $_POST['date_borrowed'],'status' => 'borrowed','notes' => $_POST['notes']));
        
    } // End of saving record of books borrowed
?>
<div class="container">
<div class="bodino_header" > <!--Start of Bodino header --->
		<div class="left" >
			<i class="fas fa-book" style="visibility: hidden;"></i><span id="books_title">Book Borrowing Plugin</span>
		</div>
		<div id="logo">
   			<img src="http://localhost/wordpress-bodino/wp-content/plugins/book-borrowing-wordpress-plugin_bodino/img/casaul_logo.png" width="70px">
   		</div>
   </div>	 <!--end of Bodino header --->
    <div class="option"> <!--start of option div-->
   	</div>
    	<?php 
    		function register_session(){
   			 if(!session_id())
        		session_start();
			}
			add_action('init','register_session');
			$name=$_GET['name'];
			$title=$_GET['title'];
			$book_id=$_GET['book_id'];
			$student_id=$_GET['student_id'];
			$_SESSION['student_name'] =$name;
			$_SESSION['title'] =$title;
			$_SESSION['book_id'] =$book_id;
			$_SESSION['student_id'] =$student_id;
    	?>
<div class="form" id="form" style="width:50%;"> <!--start of form div -->
		<div id="form_title">
   			<h3 id="title">Borrowing Form </h3>	
   		</div>
		<form action="<?php get_admin_url(); ?>?page=bodino_book_borrowing_plugin_borrowing_form" method="post">
			<input type="hidden" name="book_id" value="<?php echo $_SESSION['book_id']; ?>">
			<input type="hidden" name="student_id" value="<?php echo $_SESSION['student_id']; ?>">
		 <div class="book" title="Book Title">
			<input type="text" name="book_title"  placeholder="Book Title" autocomplete="off"   style="float:right;" value="<?php echo $_SESSION['title']; ?>">
		</div>
		<div class="book" title="Student's Name">
			<input type="text" name="student_name" placeholder="Student's Name" autocomplete="off"  style="float: right;" value="<?php echo $_SESSION['student_name']; ?>">
		</div>
		<div class="book" title="Date Borrowed" >
			<input type="text" name="date_borrowed" placeholder="Date Borrowed" autocomplete="off"  style="float: right;" value="<?php echo date('m/d/Y l') ;?>">
		</div>
		<div class="book" title="Date Returned" style="margin-top: 5px;">
			<input type="date"   name="date_returned" placeholder="Date Returned" autocomplete="off"  style="float: right;">
		</div>
		<div class="book">
			<textarea  placeholder="Write your notes" name="notes" style="width: 100%;height: 80px;margin-bottom: 50px;"></textarea>
		</div><br><br>
		<input type="submit" name="book_now" id="save_btn"  class="" value="Book Now" style="float: right;">
	</div>
	</form>
	<div class="right_div"> <!-- Div for search books and students -->
		<h4 style="text-align: left;color:#eee;margin-top: -10px;">Search Students</h4>
		<form action="" method="POST"> <!-- Search Student Form -->
			<div class="book">
			<input type="text" name="student_name" placeholder="Student's Name" autocomplete="off"  style="margin-right: -2px;margin-bottom: 3px;">
			</div>
			<input type="submit" name="search_student_btn" class="delete" value="Search Students"  id="search_form">
		</form>	<!--End of Search Student -->
		<div class="student_result">
<?php
 // search student
if (isset($_POST['search_student_btn'])) {
		$query=$_POST['student_name'];
		global $wpdb; // query database
	    $results=$wpdb->get_results("select * from wp_bodino_student Where name like '%".$query."%'");
	    $row3=$wpdb->get_var("SELECT COUNT(*)FROM wp_bodino_student Where name like '%".$query."%'");
		foreach ($results as $row ){ //start of foreach
?>
		<a id='stud' href="<?php get_admin_url();?>?page=bodino_book_borrowing_plugin_borrowing_form&name=<?php echo $row->name;?>&student_id=<?php echo $row->id;?>&title=<?php echo $_SESSION['title'];?>&book_id=<?php echo $_SESSION['book_id'];?>"><i class="far fa-user" style="margin-right: 10px;"></i><?php echo $row->name;?></a><br><br>
<?php
} // end of foreach
if($row3<=0){
	echo "<h4 id='total' style='margin-top:13px;'>".$row3." result found </h4>";
}
}	// end of search student
?>	
</div>
	<form action="" method="POST">	<!-- Search Book Form -->
			<h4 style="text-align: left;color:#eee;margin-top: -30px;">Search Books</h4>
			<div class="book">
			
			<input type="text" name="book_title" placeholder="Book Title" autocomplete="off"  style="margin-right: -2px;margin-bottom: 3px;" >
			</div>
			<input type="submit" name="search_book_btn"  class="delete" id='search_form'  value="Search Books" style="">
		</form>	<!--End of Search Book Form -->
<div class="book_result">
<?php
if (isset($_POST['search_book_btn'])) { // search book
		$query=$_POST['book_title'];
		global $wpdb; // query database
	    //$results=$wpdb->get_results("select * from wp_bodino_books Where title like '%".$query."%'");
	    $results=$wpdb->get_results("SELECT  * From wp_bodino_books  WHERE title like '%".$query."%'");
	     $row3=$wpdb->get_var("SELECT COUNT(*)FROM wp_bodino_books Where title like '%".$query."%'");
	   //	 var_dump($results);
		foreach ($results as $row ){ //start of foreach		
?>
	<a id="book_url" href='<?php get_admin_url();?>?page=bodino_book_borrowing_plugin_borrowing_form&name=<?php echo $_SESSION['student_name'];?>&student_id=<?php echo $_SESSION['student_id'];?>&title=<?php echo $row->title;?>&status=<?php if (empty($row->status)){echo 'available';}else{echo $row->status;}?>&book_id=<?php echo $row->id;?>' id="student_list_result" >
		<div id="found_book" >
			<div style="width: 100%;margin-top: -5px;word-wrap: break-word;">
			<i class="fas fa-bookmark" style="color: rgb(225, 48, 78);font-size: 28px;margin-top: -1px;margin-right: 6px;"></i><h4 id="found_title" title="Book Title"><?php echo $row->title;?></h4>
			</div><br>
			<i class="fas fa-pencil-alt" style="margin-left: 4px;font-size: 17px;margin-right: 5px;color:rgb(35, 40, 45);"></i> 
			<h4 id="found_author">Author : 
			<?php echo $row->book_author;?>
			</h4>
		</div>	
	</a>
<?php
} // end of foreach
if($row3<=0){
	echo "<h4 id='total' style='margin-top:13px;'>".$row3." result found </h4>";
}
}	// end of search book
?>
</div>
</div>
<?php
}
?>
