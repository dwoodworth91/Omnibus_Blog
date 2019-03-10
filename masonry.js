/*Masonary*/
$(document).ready(function(){
  var cards = $(".cards");
  var initialized = false;
  $.subscribe(events.CARDS_LOADED, function(e, newCards){
    if(!initialized){
      cards.masonry({
        itemSelector: '.card-faux-margin-wrapper',
        percentPosition: true
      });
    } else {
      cards.masonry('appended', newCards);
    }
    initialized = true;
  });

  function refresh(){
    if(initialized) cards.masonry('layout');
  }
  $.subscribe(events.CARDS_LOADED_IMAGES, refresh);
  $(window).resize(_.debounce(refresh, 1000));
});
