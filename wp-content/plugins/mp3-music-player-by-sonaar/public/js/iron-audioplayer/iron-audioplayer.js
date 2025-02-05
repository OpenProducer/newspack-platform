IRON.previousTrackThreshold = 2; //The number of seconds a track must play before the "Previous" button resets the track to the beginning.

IRON.audioPlayer = (function ($) {
  "use strict";
  var autoplayEnable;

  function initPlayer(player) {
    var audioPlayer = player;
    audioPlayer.id = audioPlayer.data("id");
    audioPlayer.hide_progressbar = audioPlayer.data("hide-progressbar") ? true : false;
    this.audioPlayer = player;
    var waveContainer = this.audioPlayer.find(".player .wave").attr("id");
    var playlist = audioPlayer.find(".playlist");
    this.playlist = playlist;
    this.autoplayEnable = audioPlayer.data("autoplay");
    audioPlayer.progressType = audioPlayer.data("progress-bar-style");

    audioPlayer.list = {};
    audioPlayer.list.tracks = [];

    playlist.find(".sr-playlist-item").each(function () {
        var $track = $(this);
        var trackData = {
            peakFile:             $track.data("peakfile"),
            mp3:                  $track.data("audiopath"),
            sourcePostID:         $track.data("post-id"),
            id:                   $track.data("trackid"), // Its the media attachment ID if set.
            track_pos:            $track.data("track-pos"),
            isPreview:            $track.data("is-preview"),
            peak_allow_frontend:  $track.data("peakfile-allow"),
        };
        audioPlayer.list.tracks.push(trackData);
    });

    $(audioPlayer).css("opacity", 1);

    fetch(this.audioPlayer.attr('data-url-playlist')) // Call the fetch function passing the playlist.json as audioplayer.list parameters
    .then(response => {
      if (!response.ok) {
        throw new Error('Network error: ' + response.status);
      }
      return response.json();
    })
    .then(data => {
      this.audioPlayer.list = data;
    })
    .catch(error => {
      console.error('There was a problem retrieving the data: ', error);
    });
    
    this.$audio_el = $("#" + waveContainer).find(".sonaar_media_element")[0];
    
   
    fakeWaveUpdate(this.$audio_el, audioPlayer, playlist);
    $(audioPlayer).find(".wave").css("opacity", "1");
    
    setPlaylist(playlist, this.$audio_el, audioPlayer);

    var trackNumber = playlist.find("li").index();
    var track = playlist.find("li").eq(trackNumber);
    if(track.data('tracktime')){
      audioPlayer.find('.totalTime').text('-' + track.data('tracktime'));
    }

    setCurrentTrack(playlist.find("li").eq(0), playlist.find("li").index(), audioPlayer, this.$audio_el);
    setControl(this.$audio_el, audioPlayer, playlist);
    if(track.data('tracktime')){
      audioPlayer.find('.totalTime').text('-' + track.data('tracktime'));
    }
    sr_playerCTAresponsive();
  }

  var triggerPlay = function ($audio_el, audioPlayer) {
    $audio_el.play();
    togglePlaying(audioPlayer, $audio_el);
  };

  function setCurrentTrack(track, index, audioPlayer, $audio_el) {
    audioPlayer.currentTrack = index; 
    var albumArt = audioPlayer.find(".album .album-art");
    var album = audioPlayer.find(".album");
    var trackTitle = audioPlayer.find(".track-title");
    var trackTime = audioPlayer.find(".track-time");
    var trackArtist = audioPlayer.find(".sr_it-artists-value");
    var albumTitle = audioPlayer.find(".sr_it-playlist-title, .album-title");
    var albumReleaseDate = audioPlayer.find(".srp_subtitle");

    if (audioPlayer.data('hide-artwork') != '1' && audioPlayer.data('hide-artwork') != 'true') {
      if (track.data("albumart")) {
        audioPlayer.removeClass('sonaar-no-artwork');
        if (albumArt.find("img").length) {
          albumArt.find("img").attr("src", track.data("albumart"));
        } else {
          albumArt.css("background-image", "url(" + track.data("albumart") + ")");
        }
      } else {
        audioPlayer.addClass('sonaar-no-artwork');
      }
    }

    audioPlayer.data("currentTrack", index);
    trackTitle.html(track.data("tracktitle"));
    trackTime.text(track.data("tracktime"));
    trackArtist.text(track.data("trackartists"));
    albumReleaseDate.text(track.data("releasedate"));
    if (audioPlayer.data("playlist_title").length) {
      albumTitle.text(audioPlayer.data("playlist_title"));
    } else {
      albumTitle.text(track.data("albumtitle"));
    }

    if (audioPlayer.data('playertemplate') == 'skin_boxed_tracklist' && audioPlayer.find('.srp_track_cta').length) {
      audioPlayer.find('.srp_track_cta .song-store-list-container').remove();
      audioPlayer.find('.srp_track_cta').append(track.find('.song-store-list-container').clone());
    }

    audioPlayer.find(".player").removeClass("hide");

    audioPlayer.find(".wave").removeClass("reveal");

    if (!track.data("showloading")) {
      audioPlayer.find(".player").addClass("hide");
    } else {
      audioPlayer.find(".progressLoading").css("opacity", "0.75");
    }

    IRON.createFakeWave(audioPlayer);
    
    setTime(audioPlayer, $audio_el);

    hideEmptyAttribut(track.data("releasedate"), audioPlayer.find(".srp_subtitle"));
  }

  function setPlaylist(playlist, $audio_el, audioPlayer) {
    let playlistTimeDuration = 0;
    playlist.find("li").each(function () {

      setSingleTrack($(this), $(this).index(), $audio_el, audioPlayer);

      if ($(this).data('relatedtrack') != '1') { //Count playlist time duration
        if ($(this).data('tracktime')) {
          convertTime($(this).data('tracktime'));
          playlistTimeDuration = playlistTimeDuration + convertTime($(this).data('tracktime'));
        } else { // Hide playlist time duration if one track doesnt have time (streaming)
          audioPlayer.find('.srp_playlist_duration').hide();
        }
      }

    });
    //Output playlist time duration
    playlistTimeDuration = Math.round(playlistTimeDuration / 60) * 60; //Round to minutes
    playlistTimeDuration = moment.duration(playlistTimeDuration, "seconds");
    let durationOutput = (playlistTimeDuration.hours() > 0) ? playlistTimeDuration.hours() + ' ' + audioPlayer.find('.srp_playlist_duration').data('hours-label') + ' ' : '';
    durationOutput = durationOutput + playlistTimeDuration.minutes() + ' ' + audioPlayer.find('.srp_playlist_duration').data('minutes-label');
    audioPlayer.find('.srp_playlist_duration').html(durationOutput);
  }

  function setTime(audioPlayer, $audio_el) {
    $($audio_el).on("timeupdate", function () {
      var currentTime = $audio_el.currentTime;
      var time = moment.duration(currentTime, "seconds");
      if (time.hours() >= 12 || time.hours() <= 0) {
        audioPlayer.find(".currentTime").html(moment(time.minutes() + ":" + time.seconds(), "m:s").format("mm:ss"));
      } else {
        audioPlayer.find(".currentTime").html(moment(time.hours() + ":" + time.minutes() + ":" + time.seconds(), "h:m:s").format("h:mm:ss"));
      }
      if ($audio_el.duration !== Infinity) {
        var timeLeft = moment.duration($audio_el.duration - $audio_el.currentTime, "seconds");
        if(timeLeft.milliseconds() > 0){
          if (timeLeft.hours() >= 12 || timeLeft.hours() <= 0) {
            audioPlayer.find(".totalTime").html("-" + moment(timeLeft.minutes() + ":" + timeLeft.seconds(), "m:s").format("mm:ss"));
          } else {
            audioPlayer.find(".totalTime").html("-" + moment(timeLeft.hours() + ":" + timeLeft.minutes() + ":" + timeLeft.seconds(), "h:m:s").format("h:mm:ss"));
          }
        }else{
          audioPlayer.find(".totalTime").html("");
        }
      } else {
        audioPlayer.find(".totalTime").html("");
      }
    });
    
  }

  function setControl($audio_el, audioPlayer, playlist) {
    // var ctrl = audioPlayer.find('.control');
    audioPlayer.unbind('click');
    audioPlayer.on("click", ".play, .album .album-art", function (event) {
      togglePause();

      if (!audioPlayer.hasClass("audio-playing")) {
        if($($audio_el).attr('src') != ''){
          play(audioPlayer, $audio_el);
          triggerPlay($audio_el, audioPlayer);
        } else {
          playlist.find("li").eq(0).find("a.audio-track").click();
        }
      } else {
        togglePause();
      }
      togglePlaying(audioPlayer, $audio_el);
      event.preventDefault();
    });
    audioPlayer.on("click", ".previous", function (event) {
      previous(audioPlayer, $audio_el, playlist);
      event.preventDefault();
    });
    audioPlayer.on("click", ".next", function (event) {
      next(audioPlayer, $audio_el, playlist);
      event.preventDefault();
    });
    audioPlayer.on('mouseenter', '.sr-playlist-item .song-store-list-menu', function () {
      openStoreListContainer(this);
    });
    audioPlayer.on('mouseleave', '.sr-playlist-item .song-store-list-container', function () {
      closeStoreListContainer(this);
    });
  }

  function setSingleTrack(singleTrack, eq, $audio_el, audioPlayer) {
    singleTrack.find(".audio-track").remove();
    var tracknumber = eq + 1;
    var trackplay = $("<span/>", {
      class: "track-number",
      html:
        '<span class="number">' +
        tracknumber +
        '</span><i class="sricon-play"></i>',
    });
    $("");
    $("<a/>", {
      class: "audio-track",
      click: function (event) {
        if ($(this).parents('.sr-playlist-item').attr("data-audiopath").length == 0) {
          return;
        }

        if (ifTrackIsPlaying($audio_el) && singleTrack.hasClass("current")) {
          togglePause();
          togglePlaying(audioPlayer, $audio_el);
        } else if (singleTrack.hasClass("current")) {
          play(audioPlayer, $audio_el);
        } else {
          togglePause();
          setCurrentTrack(singleTrack, eq, audioPlayer, $audio_el);
          setAudio(singleTrack.data("audiopath"), $audio_el, audioPlayer);
          
          audioPlayer.find(".playlist li").removeClass("current");
          singleTrack.addClass("current");
          triggerPlay($audio_el, audioPlayer);
          togglePlaying(audioPlayer, $audio_el);
        }
        IRON.init_generatePeaks(audioPlayer);
        event.preventDefault();
      },
    })
      .appendTo(singleTrack)
      .prepend(trackplay)
      .append('<div class="tracklist-item-title">' + singleTrack.data("tracktitle") + ' </div><span class="tracklist-item-time">' + singleTrack.data("tracktime") + "</span>");
    singleTrack.find('.store-list').before(singleTrack.find(".audio-track"));
  }

  var setAudio = function (audio, $audio_el) {
    $($audio_el).attr("src", audio);
    $audio_el.load();

    $(".sonaar_fake_wave").on("click", function (event) {
      var currentAudio = $(this).find(".sonaar_media_element")[0];
      var progressedAudio = $(this).width() / event.offsetX;
      const duration = (currentAudio.duration == 'Infinity')? currentAudio.buffered.end(currentAudio.buffered.length-1) : currentAudio.duration;
      currentAudio.currentTime = duration / progressedAudio;
      event.preventDefault();
    });
  };

  function togglePlaying(audioPlayer, $audio_el) {
    $.each(IRON.players, function () {
      this.audioPlayer.removeClass("audio-playing");
    });

    if (ifTrackIsPlaying($audio_el)) {
      audioPlayer.addClass("audio-playing");
      audioPlayer.find('.currentTime, .totalTime').show();
      return;
    }

    audioPlayer.removeClass("audio-playing");
  }

  function togglePause() {
    $.each(IRON.players, function () {
      if (ifTrackIsPlaying(this.$audio_el)) {
        this.$audio_el.pause();
      }
    });
  }

  function play(audioPlayer, $audio_el) {
    IRON.init_generatePeaks(audioPlayer);
    if (!audioPlayer.find(".playlist li").hasClass("current")) {
      audioPlayer.find("li:first-of-type").addClass("current");
    }
    if (ifTrackIsPlaying($audio_el)) {
      $audio_el.pause();
    } else {
      $audio_el.play();
    }
    togglePlaying(audioPlayer, $audio_el);
  }

  function previous(audioPlayer, $audio_el, playlist) {
    if( $audio_el.currentTime > IRON.previousTrackThreshold ){ //Resets the track to the beginning Or go to the previous track.
        $audio_el.currentTime = 0;
        return;
    }

    var currentTrack = audioPlayer.data("currentTrack");
    var nextTrack = currentTrack - 1;
    playlist.find("li").eq(nextTrack).find("a.audio-track").click();
  }

  function next(audioPlayer, $audio_el, playlist) {
    var currentTrack = audioPlayer.data("currentTrack");
    var nextTrack = currentTrack + 1;

    if (!playlist.find("li").eq(nextTrack).length) {
      nextTrack = 0;
    }
    $audio_el.pause();
    playlist.find("li").eq(nextTrack).find("a.audio-track").click();
  }

  function getPlayer() {
    return this;
  }

  function getplay() {
    play(this.audioPlayer, this.$audio_el);
  }

  function ifTrackIsPlaying($audio_el) {
      return !$audio_el.paused;
  }

  var fakeWaveUpdate = function ($audio_el, audioPlayer, playlist) {
    $($audio_el).on("timeupdate", function () {
      const duration = (this.duration == 'Infinity')? this.buffered.end(this.buffered.length-1) : this.duration;
      $(audioPlayer)
        .find(".sonaar_wave_cut")
        .width(((this.currentTime + 0.35) / duration) * 100 + "%");
      if ($audio_el.ended) {
        //When track ended
        next(audioPlayer, $audio_el, playlist);
      }
    });
  };

  return {
    init: initPlayer,
    getPlayer: getPlayer,
    play: getplay,
    autoplayEnable: autoplayEnable,
    triggerPlay: triggerPlay,
  };
})(jQuery);

function hideEmptyAttribut(string, selector) {
  if (string == "") {
    selector.css("display", "none");
  } else {
    selector.css("display", "block");
  }
}


//Load Music player Content
function setIronAudioplayers(specificParentSelector) {
  if (typeof specificParentSelector !== "undefined") {
    // set all audioplayers or only players inide a specific selector
    if (!specificParentSelector.includes('"') && !specificParentSelector.includes("'") && jQuery('[data-id="' + specificParentSelector + '"]').hasClass('iron-audioplayer')) { //if specificParentSelector is the iron-audioplayer element (not parent)
      var playerSelector = jQuery('[data-id="' + specificParentSelector + '"]');
    } else {
      var playerSelector = jQuery(specificParentSelector + " .iron-audioplayer");
    }

    if (IRON.players == "undefined") {
      IRON.players = []; //dont reset the IRON.players if they already exist and the setIronAudioplayers function is executed from sr-scripts.js
    }
  } else {
    var playerSelector = jQuery(".iron-audioplayer");
    IRON.players = [];
  }
  playerSelector.each(function () {

    if (typeof specificParentSelector == "undefined" && jQuery(this).parents(".elementor-widget-woocommerce-products").length) return;

    if (typeof specificParentSelector == "undefined" && jQuery(this).parents(".elementor-widget-music-player").length) return;
    var player = Object.create(IRON.audioPlayer);
    player.init(jQuery(this));
    IRON.players.push(player);
  });
}
setIronAudioplayers();
