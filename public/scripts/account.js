jQuery(document).ready(function($) {
	$(".target_serie").on("click", function () {
		var postId = $("#itemID").val(),
			number = $(this).attr("data-serie-number");
		
		$.ajax({
			url: '/account/favorites',
			type: 'post',
			data: {
				"item": postId,
				"put-last-serie": number,
			},
		})
		.done(function(data) {
			console.log(data);
		})
		.fail(function() {
		})
		.always(function() {
		});
		
	});
});