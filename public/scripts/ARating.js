
  function ARatingUpdateProgress (rate, px = 0) {
    if (px == 0) {
      rate =  (((100 / 10)*rate)<<0) + '%';
    }else {
      rate = px + "px";
    }
    $(".ARating-container > .ARatingBackGround").width(rate);
  }
  function ARatingfindRealLeft(obj) {
    if (!obj) return 0;
    return obj.offsetLeft + ARatingfindRealLeft(obj.offsetParent);
  };
  ARatingUpdateProgress ($(".ARating-container > .ARatingBackGround").attr("data-total-value"));
 
  $( ".ARatingStar" ).mousemove(function( e ) {
    realOffsetLeft = ARatingfindRealLeft(this);
    var relativeX = e.pageX - realOffsetLeft;
    globalWidth = Math.floor(relativeX / 23) * 23 + 23;
    ARatingUpdateProgress (88, globalWidth);
  });

  $( ".ARatingStar" ).mouseout (function() {
    ARatingUpdateProgress($(".ARating-container > .ARatingBackGround").attr("data-total-value"));
  });

  $( ".ARatingStar" ).click(function (e) {
    realOffsetLeft = ARatingfindRealLeft(this);
    var relativeX = e.pageX - realOffsetLeft;
    globalWidth = Math.floor(relativeX / 23) * 23 + 23;

    if (globalWidth == 23) {my_vote = 1}
    else if (globalWidth == 46) {my_vote = 2}
    else if (globalWidth == 69) {my_vote = 3}
    else if (globalWidth == 92) {my_vote = 4}
    else if (globalWidth == 115) {my_vote = 5}
    else if (globalWidth == 138) {my_vote = 6}
    else if (globalWidth == 161) {my_vote = 7}
    else if (globalWidth == 184) {my_vote = 8}
    else if (globalWidth == 207) {my_vote = 9}
    else if (globalWidth == 230) {my_vote = 10}

    $.ajax({
      url: '/account/ratePost',
      method: 'post',
      data: {
        "item": $("#itemID").val(),
        "vote": my_vote
      },
      success: function (result) {
        // $("body").append(result);
        json = jQuery.parseJSON(result);
        if (json.url) {
          window.location.href = json.url;
        } else {
          alert(json.status + ' - ' + json.message);
        }
      }
    });
    console.log('id - ' + + ' rate -' + my_vote);
  });
