const player = new Plyr('#player_container > video')

jQuery(document).ready(function($) {
	if ($(".target_serie.active").attr("data-is-frame") == "1") {
		$("#player_container iframe").attr("src", $(".target_serie.active").attr("data-serie-url"));
		$("#player_container iframe").show();
		$("#player_container .plyr").hide();
	}else {
		$("#player_container video").attr("src", $(".target_serie.active").attr("data-serie-url"));
		$("#player_container iframe").hide();
		$("#player_container .plyr").show();
	}

	sendAjaxToPlusVIew($(".target_serie.active").attr("data-serie-id"));


	$(".target_serie").on("click", function () {
		var id = $(this).attr("data-serie-id"),
			urls = $(this).attr("data-serie-url"),
			isframe = $(this).attr("data-is-frame");
		if (isframe == "1") {
			$("#player_container iframe").attr("src", urls);
			$("#player_container iframe").show();
			$("#player_container .plyr").hide();
		}else {
			$("#player_container video").attr("src", urls);
			$("#player_container iframe").hide();
			$("#player_container .plyr").show();
		}
		
		sendAjaxToPlusVIew(id);
		$(".target_serie").removeClass('active');
		$(this).addClass('active');
	});

	$("#aside_search .input-group-append").click(function() {
		var text = $("#aside_search input.form-control").val();

		if (text.length < 4) {
			alert('Ошибка - минимальное кол-во знаков в строке 4');
		}else {
			location.href= "/catalog/search/query/" + text;
		}
	});
});

function sendAjaxToPlusVIew(serie_id) {
	$.ajax({
		url: '/catalog/plusviewtoserie',
		type: 'post',
		data: {"serie_id": serie_id},
	})
	.done(function(data) {
		console.log(data);
	})
	.fail(function() {
		
	})
	.always(function() {
		
	});
	
}

function handleFileSelect(evt) {
    var file = evt.target.files;
    var f = file[0];
    if (!f.type.match('image.*')) {
        alert("Выберите изображение");
    }
    else {
    	$("#avatarFORM").submit();
	    var reader = new FileReader();
	    reader.onload = (function(theFile) {
	        return function(e) {
	        	$("#avatarIMG").attr("src", e.target.result);
	        };
	    })(f);
	    reader.readAsDataURL(f);
	}
}

function preLoadImage(){
  $("#avatar_input").change(function(e){handleFileSelect(e)});
}

total_inputs = 0;

$("#add_input").click(function() {
	total_inputs++;
	$("#newInputs").append('<div class="col-4"><input type="text" name="newinput['+ total_inputs +'][key]" class="form-control" placeholder="Название поля"></div>');

	$("#newInputs").append('<div class="col-4"><input type="text" name="newinput['+ total_inputs +'][value]" class="form-control" placeholder="Значение поля"></div>');

	$("#newInputs").append('<div class="col-4"><div class="form-check"><label><input name="newinput['+ total_inputs +'][ishidden]" class="form-check-input" type="checkbox">Выводить на главной</label></div></div>');

	$("#newInputs").append('<div class="col-12" style="height:20px"></div>');
});

$("#buttonTorrentContainer").click(function() {
	var
		count = Number($(this).attr("data-all-count")),
		container = $("#torrentContainer");
	
	container.append('<tr><th scope="row">'+ count +'</th><th scope="row"><div class="form-group"><label><input type="text" name="resolution['+ count +']" placeholder="Разрешение" class="form-control"><br></label></div></th><th scope="row"><div class="form-group"><input type="text" name="url['+ count +']" placeholder="Ссылка на файл" class="form-control"><br></div></th></tr>');
	$(this).attr("data-all-count", count + 1);
});