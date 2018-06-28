var clapping = function(id) {
  jQuery.ajax({
    type: "post",
    url: pt_ajax_obj.ajax_url,
    dataType: "json",
    data: {
      action: "pt_clap_increase",
      post_id: id
    },
    success: function(msg) {
      jQuery(".pt-claping span").text(msg.claps);
    }
  });
};
