var SCROLL_THRESHOLD = 200;

var posts;
var postsLoadingIndicator;
var noMorePostsMsg;
var fetching = false;

function fetchNewPosts(){
	setDisplayLoading(true);
	$.ajax({
		url: model.admin_ajax_url,
		type:'POST',
		data: formQueryString(),
		success: function(respObj){
			if(respObj) model.pageNumber++;
		},
		complete: function(resp){
			setDisplayLoading(false);
			posts.append(resp.responseJSON);
			if(!gethasMorePosts()){
				noMorePostsMsg.css("display", "");
			}
		}
	});
}

function formQueryString(){
	if(model.queryString) model.queryString = "&" + model.queryString;
	return "action=" + model.infinite_tiles_action + "&paged="+ model.pageNumber + model.queryString;
};

function fetchNewPostsIfAllowed(ignoreScrollPosition){
	var hasMorePosts = gethasMorePosts();
	var isScrolledLowEnough = ignoreScrollPosition || $(window).scrollTop() + SCROLL_THRESHOLD >= $(document).height() - $(window).height();
	if(!fetching && isScrolledLowEnough && hasMorePosts){
		fetchNewPosts();
	}
}

function gethasMorePosts(){
	return parseInt(model.pageNumber, 10) <= (parseInt(model.pageCount, 10) +  1);
}

function setDisplayLoading(isLoading){
	fetching = isLoading;
	if(isLoading){
		postsLoadingIndicator.show();
	} else{
		postsLoadingIndicator.hide(0);
	}
}

$(document).ready(function(){
	posts = $("#posts");
	postsLoadingIndicator = $("#postsLoadingIndicator").hide(0);
	postsLoadingIndicator.show = function(){postsLoadingIndicator.css("display", "")}
	noMorePostsMsg = $("#noMorePostsMsg");

	$(window).scroll(function(){
		fetchNewPostsIfAllowed();
	});
	fetchNewPostsIfAllowed(true);
});
