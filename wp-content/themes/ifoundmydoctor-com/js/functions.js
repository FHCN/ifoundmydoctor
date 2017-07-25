function countWords(content){
	s = content;
	s = s.replace(/(^\s*)|(\s*$)/gi,"");
	s = s.replace(/[ ]{2,}/gi," ");
	s = s.replace(/\n /,"\n");
	return s.split(' ').length;
}

(function($){

	//document ready
	$(document).on('ready', function(){
		$('.widget-news h4').each(function () {
			var h4 = $(this);
			var h4_text = h4.text();
			var cut_at_idx = 27;
			if (h4_text.length > 27) {
				if (h4_text.charAt(27) != ' ') {
					for (var i = 26; i >= 0; i--) {
						if (h4_text.charAt(i) == ' ') {
							cut_at_idx = i;
							break;
						}
					};
				}
				cut_at_idx = (cut_at_idx == 0) ? 25 : cut_at_idx;
				h4.text(h4_text.substring(0, cut_at_idx));
				
				$(this).next('p').prepend(h4_text.substring(cut_at_idx, h4_text.length) + '. ');
			}
		});

		
		$('.widget-own-articles').removeClass('widget-blog');
		$('div.menu-find-us-on-container ul li a').attr('target', '_blank');
		$('.editions div.section ul:first').css('padding-left', '0px');
		$('.page-template-default .gform_wrapper input[type=submit]').removeClass('button');
		var prev_link = '';
		var next_link = '';
		$('div.article-control ul.pagination li').each(function() {
			var th = $(this);
			if (!th.parent('a').length) {	//it is the current element
				
				if (th.prev('a').length) {
					prev_link  = th.prev('a').attr('href');
				}

				var next_attr_href = th.next().attr('href');
				if (next_attr_href != '') {
					next_link = next_attr_href;
				}
				$('div.article-control ul.pagination').prepend('<li><a href="' + prev_link + '">< Previous</a></li>');
				$('div.article-control ul.pagination').append('<li class="last"><a href="' + next_link + '">Next ></a></li>');
				return;
			}
		});


		$('ol.commentlist').prev('h3').css('padding', '0px 0px 15px 15px');
		//blink fields
		$(document).on('focusin', '.blink-cnt input', function() {
			if(this.title==this.value) {
				this.value = '';
			}
		}).on('focusout', '.blink-cnt input', function(){
			if(this.value=='') {
				this.value = this.title;
			}
		});

		//sticky footer
		$('.shell').css('padding-bottom', $('#footer').height())

		//carousel
		$('.slider li').each(function(){
			$(this).attr('data-defidx', $(this).index());
		});

		$('.slide-thumbs li:first .outer-mask').css('display', 'block');
		$('.slider').carouFredSel({
			items: 1,
			auto: ifd_show_speed,
			scroll:{
				pauseOnHover:true,
				fx:'crossfade',
				duration:500,
				onBefore:function(oldItems, newItems){
					var curidx = newItems.attr('data-defidx');
					$('.slide-thumbs li')
						.eq(curidx).find('.outer-mask')
						.css('display', 'block')

						.closest('li').siblings('li').find('.outer-mask')
						.css('display', 'none');
					var position = $('.slide-thumbs li').eq(curidx).position();
					
					arrowPos = position.left + 38; 
					$('.slider-arrow').animate({left: arrowPos+'px'}, 500);
				}
			}
		});

		$('.slide-thumbs a').click(function(){
			var idx = $(this).closest('li').index();
			$('.slider').trigger('slideTo', 'li[data-defidx="'+idx+'"]');
			return false;
		});

		//home widgets height
		var hwHeight = 0;
		$('.home-top-widgets .widgets > li').each(function(){
			if($(this).height() > hwHeight) hwHeight = $(this).height();
		}).css('min-height', hwHeight);

		//content height
		var csHeight = 0;
		$('#content, #sidebar').each(function(){
			if($(this).height() > csHeight) csHeight = $(this).height();
		}).css('min-height', csHeight);

		//tabs
		$('.tabs-nav li:first').addClass('active');
		$('.tabs-content li:first').show();

		$('.tabs-nav li a').click(function(){
			var tabidx = $(this).closest('li').index();
			$(this).closest('li').addClass('active').siblings('li').removeClass('active');
			$('.tabs-content li').eq(tabidx).show().siblings('li').hide();
			return false;
		});
		$('.tabs-nav li:last').addClass('last');
		//custom select
		$('.search-form select').c2Selectbox();

		var  last_li = $('ul.breadcrumbs li:last');
		var last_breadcrumb =  last_li.find('a').text();
		last_li.find('a').remove();
		last_li.text(last_breadcrumb);
		last_li.addClass('active');

		$('#search-form, #search-doctors-form').submit(function() { 
			var form = $(this);

			if (form.find('#ifd_keywords').val() == form.find('#ifd_keywords').attr('title')) {
				form.find('#ifd_keywords').val('');
			}

			if (form.find('#ifd_zip').val()) {
				if (form.find('#ifd_zip').val() == form.find('#ifd_zip').attr('title')) {
					form.find('#ifd_zip').val('');
				}
				if (form.find('#ifd_keywords').val() == form.find('#ifd_keywords').attr('title')) {
					form.find('#ifd_keywords').val('');	
				}
			}

			if (form.find('#ifd_custom_radius').val()) {
				if (form.find('#ifd_custom_radius').val() == form.find('#ifd_custom_radius').attr('title')) {
					form.find('#ifd_custom_radius').val('');
				}
			}
		});

		$('.article .wp-caption, .article img.alignleft, .article img.alignright').each(function() {
			var to_wrap = $(this);
			var float_value = $(this).css('float');
			if ($(this).find('img').length) {
				return true;
			}
			to_wrap.wrap('<span class="image-frame" style="float: ' + float_value + '"></span>');
			$('<span class="image-actual-frame"></span>').insertAfter(to_wrap);
			to_wrap.parent().find('.image-actual-frame').css({
				top: to_wrap.css('margin-top'),
				left: to_wrap.css('margin-left'),
				width: to_wrap.attr('width'),
				height: to_wrap.attr('height')
			})
		});
	});
})(jQuery)