var $ = jQuery;
$(function(){
	var selector = {
		'share_in_wrap': '.share-in',
		'share_in_group': '.share-in-group',
		'share_in_toggle': '.share-in-toggle',
		'share_in_ul': 'ul.share-in-ul'
	}

	$(selector.share_in_toggle).on("click", function(){
		var $this = $(this);
		if(!$this.hasClass('active')) {
			show_share_in($this);
		}else{
			close_share_in($this);
		}
		return false;
	})

	var show_share_in = function($this) {
			$this.parent().find(selector.share_in_ul).show();
			$this.addClass("active");
	}

	var close_share_in = function($this) {
			$this.parent().find(selector.share_in_ul).hide();
			$this.removeClass("active");			
	}

	$(document).click(function(e){
		if(!$(e.target).hasClass(selector.share_in_toggle)) {
			$(selector.share_in_toggle).removeClass("active");			
			$(selector.share_in_ul).hide();
		}
	})

	$(selector.share_in_ul).find("li").each(function() {
		var $this = $(this),
				$tooltip = $("<div/>", {
					class: "share-in-tooltip"
				})
				.html($this.find('a').attr("title"));

		$this.find("a").mouseover(function(){
			$this.append($tooltip);
		})
		$this.mouseout(function(){
			$(this).find(".share-in-tooltip").remove();
		});
		$this.find("a").attr("title","");
	})

	$(selector.share_in_ul).find("li a").on("click", function() {
		window.open($(this).attr('href'), 'Share-in: '+$(this).attr('title'), 'scrollbars=no, width=500, height=500');
		return false;
	})
});