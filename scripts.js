$(document).ready(function(){
	/*Menu Affixer*/
	var body = $('body');
	var banner = $('#banner-background');
	var archive = $("#archive");
	var archiveLoaded = false;
	var archiveLoadingIndicator = $(".archive .spinner");
	var archiveDropdownToggle = $(".archive .dropdown-toggle");
	
	$('#main-menu').affix({
		offset:{
			top: function(){
				return banner.outerHeight();
			}
		}
	}).on('affixed.bs.affix',function(){
		body.addClass('nav-is-affixed');
	}).on('affixed-top.bs.affix',function(){
		body.removeClass('nav-is-affixed');
	});
	
	/*Archive*/
	archive.click(function(){
		fetchArchive();
	});
	
	$('a', archive).on('click', function (event) {
		$(this).parent().toggleClass('open');
	});
	
	function fetchArchive(){
		if(!archiveLoaded){
			setArchiveDisplayLoading(true);
			$.ajax({
				url: model.admin_ajax_url,
				type:'POST',
				data: {action: model.archive_action},
				success: function(resp){
					archive.append($(resp));
					initArchive();
					archiveLoaded = true;
				},
				error: function(){
					archiveLoaded = false;
				},
				complete: function(){
					setArchiveDisplayLoading(false);
				}
			});
		}
	}
	
	function setArchiveDisplayLoading(aBool){
		archiveLoadingIndicator.toggle(aBool);
		archiveDropdownToggle.toggle(!aBool);
	}
	
	function initArchive(){
		body.on('click', function (e) {
			if (!$('li.dropdown.archive').is(e.target) 
				&& $('li.dropdown.archive').has(e.target).length === 0 
				&& $('.open').has(e.target).length === 0
			){
				$('li.dropdown.archive').removeClass('open');
			}
		});
		
		$('.expand-archive').on('click', function(event){
			$(this).find(".expand-icon").toggleClass('glyphicon-minus-sign glyphicon-plus-sign');
		});
	
		/*Make the archive list never be taller than the window height and scroll any overflow.*/
		var archiveList = $("#archive-list");
		
		function updateArchiveMaxHeight(){
			var distToBottom = window.innerHeight - (archiveList.offset().top - $(window).scrollTop());
			archiveList.css({"max-height":distToBottom+"px"});
		}
		
		$(".dropdown-toggle").click(updateArchiveMaxHeight);
		$(window).scroll(updateArchiveMaxHeight);
		$(window).resize(updateArchiveMaxHeight);
	}
})