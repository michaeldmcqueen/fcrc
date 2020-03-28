<?php include("inc/plugin_header.php");?>

<?php 
if(empty($_GET['tab'])){
	include("inc/rearrange_all.php");
}
if(!empty($_GET['tab']) && $_GET['tab']=='groupby-categories'){
	include("inc/rearrange_by_categories.php");
}
?>

<?php include("inc/plugin_footer.php");?>