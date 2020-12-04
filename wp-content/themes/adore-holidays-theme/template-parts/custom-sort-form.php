<?php if ( is_archive() ) { ?>
	<select name="sort-posts" id="sortbox" onchange="document.location.href='?'+this.option[this.selectedIndex].value;">
<?php } else { ?>

    <select name="sort-posts" id="sortbox" onchange="document.location.href='?s=<?php the_search_query(); ?>&'+this.option[this.selectedIndex].value;">

<?php } ?>

<option value="" disabled>Sort Bedrooms</option>
<option value="orderby=bedrooms&order=asc">Bedrooms</option>

</select>




<script type="text/javascript">

	<?php if (( $_GET['orderby'] == 'bedrooms') && ( $_GET['order'] == 'asc')) { ?>
	  document.getElementId('sortbox').value'orderby=bedrooms&order=asc';
	  
	<?php } ?>

</script>	


