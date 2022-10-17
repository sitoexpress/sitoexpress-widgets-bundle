$(document).ready(function(){
  $('.se-video-parent.background').each(function(){
    var row_style = $(this).closest('.panel-row-style');
    var grid_cell = $(this).closest('.panel-grid-cell');
    if(row_style.length) {
      row_style.addClass('video-bg-row')
    } else {
      grid_cell.addClass('video-bg-row')
    }
  })

  const players = Plyr.setup('.simple-plyr .se-plyr')
  const autoplayers = Plyr.setup('.autoplay .se-plyr')

  if(players) {
    players.forEach((element) => {
      element.on('ready', (event) => {
        if($(event.detail.plyr.elements.container).attr('data-poster')) {
          event.detail.plyr.poster = $(event.detail.plyr.elements.container).attr('data-poster')
        }
      });
      element.on('ended', (event) => {
        $(event.detail.plyr.elements.container).addClass('plyr--stopped').removeClass('plyr--paused')
      });
    });
  }
  
})
