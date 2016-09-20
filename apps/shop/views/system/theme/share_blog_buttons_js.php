<script>
	$('.btn-share').click(function(evt) 
	{
		var url = $(this).attr('href');
		var elem = $(this);
		elem.html('Loading...');
		$.get("/blog/post_share_tracker?csrf_token=<?= $post_csrf_token ?>&post_id=<?= $post['post_id'] ?>" , function(data)
		{
			elem.html('Sharing....');
			window.location = url;
		});
		evt.preventDefault();
	});
</script>