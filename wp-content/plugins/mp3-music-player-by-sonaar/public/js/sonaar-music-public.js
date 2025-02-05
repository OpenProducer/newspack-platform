IRON = window.IRON || {};
(function ($) {
  /*===============================================================================
  ##### This file is available in both MP3 Audio Player Settings and Frontend #####
  ===============================================================================*/

  IRON.peaksCache = {}; // Cache peaks to avoid fetching them multiple times
  var loadFakeWaveOnly;

  IRON.init_generatePeaks = function (player = false){
    if (loadFakeWaveOnly) return;

    if(player && player.stickyPlayer){
      return;
    }

    var peakFile = false;

    if( player && typeof player.currentTrack != 'undefined'){
      peakFile = player.list.tracks[player.currentTrack].peakFile;
    }else{
      peakFile = IRON.sonaar.player.list.tracks[IRON.sonaar.player.currentTrack].peakFile;
    }
    
    if (!peakFile) {
      let audioSource = player ? player : IRON.sonaar.player;

      if (audioSource) {
        let currentTrack = audioSource.list.tracks[audioSource.currentTrack];
        if (currentTrack) {
            if (currentTrack.peak_allow_frontend) {
              IRON.generatePeaks([{
                "file": currentTrack.mp3,
                "post_id": currentTrack.sourcePostID,
                "media_id": currentTrack.id,
                "index": currentTrack.track_pos,
                "is_preview": currentTrack.isPreview,
                "peak_file_type": currentTrack.peak_allow_frontend,
              }]);
            }
        }
      }
    }
  }

  IRON.generatePeaks = async function(files) {    
    // Base condition to stop recursion
    audioContext = new (window.AudioContext || window.webkitAudioContext)();

    console.log('We are generating the soundwave ...');

    const file = files[0];

    try {
      const MAX_SIZE    = 200 * 1024 * 1024; // 10MB in bytes
      const response 		= await fetch(file.file);
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      const contentLength = response.headers.get('Content-Length');
      const fileSizeMB    = parseInt(contentLength, 10) / (1024 * 1024);

      if (!isFirefox() && contentLength && parseInt(contentLength, 10) > MAX_SIZE) {
          // This will return false if contentLength is NaN
          console.log(`File is too large ( > 200MB) to generate waveform in this browser. Use Firefox to generate it for the first time. (${fileSizeMB.toFixed(2)} MB). Skipping: ${file.file}`);
          return; // Skip this file
      }

      console.log(`File is ${fileSizeMB.toFixed(2)} MB. Analyzing waveform of: ${file.file}`);
      const arrayBuffer 	= await response.arrayBuffer();
      try {

        var audioBuffer = await audioContext.decodeAudioData(arrayBuffer);
        let peaks 		  = IRON.extractPeaks(audioBuffer);
        IRON.updatePeaksOnServer(file.post_id, file.media_id, file.index, peaks, file.file, file.is_temp, file.is_preview, file.peak_file_type);
        // Attempt to release the audioBuffer memory
        audioBuffer = null;

      } catch (decodeError) {
        console.error('Error decoding file:', file.file, decodeError);
      }
    } catch (fetchError) {
      console.error('Error fetching file:', file.file, fetchError);
    }

    function isFirefox() {
      return navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
    }

  }

  IRON.extractPeaks = function(audioBuffer, peakLength = 1920) {
    const channels    = audioBuffer.numberOfChannels;
    const sampleSize  = audioBuffer.length / peakLength;
    const sampleStep  = ~~(sampleSize / 10) || 1;
    const peaks       = [];

    for (let c = 0; c < channels; c++) {
      const chan = audioBuffer.getChannelData(c);

      for (let i = 0; i < peakLength; i++) {
        let start   = ~~(i * sampleSize);
        let end     = ~~(start + sampleSize);
        let min     = chan[0];
        let max     = chan[0];

        for (let j = start; j < end; j += sampleStep) {
          const value = chan[j];
          if (value > max) max = value;
          if (value < min) min = value;
        }
        
        if (c === 0 || max > peaks[2 * i]) {
          peaks[2 * i] = Math.abs(max.toFixed(2));
        }
        if (c === 0 || min < peaks[2 * i + 1]) {
          peaks[2 * i + 1] = Math.abs(min.toFixed(2));
        }
      }
    }
    return peaks;
  }
  
  IRON.updatePeaksOnServer = function(postId, media_id, index, peaks, file = null, is_temp = null, is_preview = null, peak_file_type = null) {
    peaks = peaks.join(',').replace(/0\./gi,'.')
    $.ajax({
      url: sonaar_music.ajax.ajax_url,
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'update_audio_peaks',
        nonce: sonaar_music.ajax.ajax_nonce_peaks,
        post_id: postId,
        media_id: media_id,
        index: index,
        file: file,
        peaks: peaks,
        is_temp: is_temp,
        is_preview: is_preview,
        peak_file_type: peak_file_type,
      },
      success: function(response) {
        console.log('Peaks updated for media_id:', media_id, ' post_id:', postId, 'index:', index, response);
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.error("Error updating peaks: ", textStatus, errorThrown);
      }
    });
  }
  IRON.addDownloadSVGButton = function() {
    IRON.addDownloadSVGButton.saveSVG = true;

    var btn = document.createElement("button");
    btn.innerHTML = "Download SVG";
    btn.style.position = "absolute";
    btn.style.top = "50px";
    btn.style.left = "50px";
    btn.style.zIndex = "9999";

    btn.onclick = function() {
        downloadSVG(IRON.addDownloadSVGButton.svgString);
    };
    document.body.prepend(btn);

    function downloadSVG(svgData) {
      var blob = new Blob([svgData], { type: 'image/svg+xml' });
      var url = URL.createObjectURL(blob);
      var a = document.createElement('a');
      a.href = url;
      a.download = 'soundwave.svg';
      document.body.appendChild(a);
      a.click();
      setTimeout(function() {
          document.body.removeChild(a);
          window.URL.revokeObjectURL(url);  
      }, 0);
    }

  }
  createFakeWave = function (audioPlayer = true, imSticky = false, singleTrack = false) {
    if(!imSticky && !singleTrack && audioPlayer.remove_wave) return;

    if(sonaar_music.option.music_player_load_fakewave_only == 'on') loadFakeWaveOnly = true;
    
    if (imSticky && sonaar_music.option.sticky_spectro_container == 'inside' && sonaar_music.option.sticky_spectro_style != 'none') {
      return; // Early return if specific conditions are met
    }

    var waveId;
    if(singleTrack && singleTrack.index() < 20){ // for better performance, only manage the first 20 tracks.
      waveId = getWaveIdentifier(audioPlayer, imSticky, singleTrack);
    }else if(imSticky || audioPlayer && !singleTrack){
      waveId = getWaveIdentifier(audioPlayer, imSticky, singleTrack);
    }
  
    if (!waveResizeHandlers.has(waveId)){
      // Debounced resize handler for this specific wave
     // Store the initial width
    let windowWidth = window.innerWidth;

    const debouncedResizeHandler = debounce(() => {
      // Check if the width has changed
      if (window.innerWidth !== windowWidth) {
        createFakeWave(audioPlayer, imSticky, singleTrack);
        // Update the stored width
        windowWidth = window.innerWidth;
      }
    }, 250);

      // Add the resize listener and store the handler in the map
      window.addEventListener('resize', debouncedResizeHandler);
      waveResizeHandlers.set(waveId, debouncedResizeHandler);
    }

   
    var peaks = [];
    var barGap;
    var barWidth;
    var lineCap;
    var fadeDuration;
    var totalBarWidth;
    var waveBaseDiv;
    var waveBaseWidth;
    var canvasWidth;
    var canvasHeight;
    var desiredNumBars;
    var downsamplingFactor;
    var numBars;
    
    var peakFile = singleTrack && singleTrack.attr('data-peakFile') != '' ? singleTrack.attr('data-peakFile') :
      // If 'singleTrack' condition was false, check if 'imSticky' is truthy
      imSticky ? IRON.sonaar.player.list.tracks[IRON.sonaar.player.currentTrack].peakFile :
        // If 'imSticky' condition was false, check if 'audioPlayer' exists, 'audioPlayer.currentTrack' is defined, and the current track has a non-empty 'data-peakFile' attribute
        !singleTrack && audioPlayer && typeof audioPlayer.currentTrack != 'undefined' && audioPlayer.find('sr-playlist-item').eq(audioPlayer.currentTrack).attr('data-peakFile') != '' ? audioPlayer.find('.sr-playlist-item').eq(audioPlayer.currentTrack).attr('data-peakFile') :
          // If none of the above conditions were met, set 'peakFile' to false
          false;

    var defaultPeaks = "000010101110101110101011111010111011111111111011111111211111111111112121112121111121212121211121112232111121313121112222122221512132213121313121214242313222312132322131423131413132313122223132323222212221214222@=IGHJJ<C?==9=:5:96455568357;5543;62;5377058974285788228628436714217747328825538633625626746736578643454635555460353434678442662533491561562745573344453444575335442434553:7@HCLB9=C;<9<95455774235666473782354464453372546671634354443673>G:NCCBA=;843352463453386757465563784252443563533372332551223354F;DE?89?=:A;:<@=98554475367:66636575444573D=CMDA@C:9;<==;8<97762345353444553333432453241655463644443533222433334446452323443223344565775523345425232333233312232223332122222324123233222232322322222311222111131129B:?CCLN@F9;;:668334222223334526255445322232523153338232324323329AA?G@7EB><;:645235142524956:27:56434343324332335192514224234322;>F<@HC?>=87833432323260596659445456542332BGN<IDIBGABCA>C65@?<<<<7743445455476534333415663834744544432323222323334326644222232478595<7@<;=><>?;><54;:6575241322232222222213131434433222122212121113122222311211121C2NLFQHA:G@DE>=>C@??;;:82873346643354535233253433341354333422222J2=>FMC@BE=9<:44332231:6:<9>;???>><??6<@>;>;:8563383343333363222E5=@AG?>?:<=?8=<=><96832553545554334445442J2GALPD<:KBJBG?A=C=:7><867744434556432233443254444735254444454244422423434435543322432;988@<=<>?>:=A??<9;;;638524233322132232233423252333434224232224232232333322121222332?HK9BBGQC?B?=EBB;K==>C;;74336865763532534334547354435442245443:3?HF<>A?A85:;;?646453<4;889C;?@:??>@>9>@77;@89:;24164736442534289FIC=A@<>?<:97856846756489:9:86663567579544E??GFBNGA?<8E<HA??9AB8=99878753854<48:9269:69496>:5348766355505416857769645655764537@::?@CFHF<A?HA?@ABA;;=57742574766584553662713593551573432573452582646472336574546354AKJRPGIJHDG?BCIA:@A5<<?7875464657:673572546681555472542374356463ILI>FL=9;;663474525596?=8=EB?A@@G:AA@@@<9>:4988295<3554454455363>A>?FI?B?;?:>9>=:874549373:872568634648662IJDBH<FEH;>OFF?F=CAB?=5884955586567444959345767469859665647874557564635375636264856377>?::E?F<?HB@?;6@@8<A@;8:34537362567364335686665543545353586444535682345552745443847?BOQHEIDCFH@7?CC<D66>;55:7553544788765766;355587473559675467677O;O:EJBA?CA;8676737464::@::=@NA?JD=BB@AC<>>98;928665839656;47585;C;CMC<B=A=;84;776946566436927464465453543WJNVMCXQTJLF8GB8;9>7<<@5@DH?BEI9BC=;6;8;:8>989;:CC@CE>?B<A@<<CC6>;?>EB:>>9=B;A=?9??9;BSCENQ?HCIFRG=465;998==D<E?FB>F@=ADA;:76:<8;=B@HEIKM;DBCG?=EBD@BD@?>>BBC9>AAD<D@=?<9?IIHHPO_QNGWCDC=:=D=77=D7?DJ55HF94?@39;95>:9<C?E:C@?@@@=?@<>>=:9IB2SJFJEMCBG@<=FJMA?@JIFMHCBKMBZI9D=59:B3<;=66CE>F@A<<@F:;886;9:>IIM>NLNDDLH>EDID@FABD<@DB>A>?A@=>>@9;>:B>??SXKXVPSGKUFE?DH87><<8A<CDDI@ACA;<;8962695;@=>A<?D?5@<FG<;BAG@7C?;IDD;>@E=;<<AG<@C>:MBE=@KTLMHM?IA9<86:07:E44;I85HE97EB:6=<6:8<;D=?<8IEEB;=BED<@9;BB@<;<GC=B:=D<?@<=D=ANY?KOVPRTWRS@?A<@B@@<<@>1>?F5CGG<9E<168:597>4@8;79:;=G==995C>?::5KCMFJAEGEQ>E?IA7<3:C?NE<QEKKPMIGPBE97D8673;9A;EFIGG@A;8;>35:95:8L@GQE@@BFCKFJ==4@AHB;5?6GA>8<;@=<:@AD89;<MbQO[PKPRKGI?FC9=;@?9C=>77BH99CJ<5A<936;9965=99<?C:ED>?;BL;B>CF;=A>MF?=C@>@;>>4:?><>=?PWBOTJWNAOC9?67:7:88<E54GJB;GB9:DF=6;;9@9?I8DD8B?EJ@CFG@>;:D?=?GIA??9?9C=@@?:@9BD;AA<QHMAZJWMONQLI<G8@;:;7<B:D<C@J@NE?@<9=:C9C@J>CJRIC7FDDCC?D=D<6D>C@EMHFANFDFFCG@H8>DXEQK??F?EGNEUIB;=6<?=47;=>8?JC<@@57EC=7:=2:7LHHDADPABKJQQ?E@CADFC:EA?EJ@NFB>@>EB?97A<<CPHQQPPLRJNQW<98=?F87CH8<@ED3BN9<HD<ED@=;=B@IF=DC===B:::F6<@8:>=@9>B?8D>BA@A>?>>?<=7JKOPTZGGGGN>MJIBKB=5F>CB=HOJ?I?@AHB;ECB=H<=DNCOMNBEFE@=7C9<<<;7:;9?6@<@;:9=7=54586:75@SOLKYSFFCFUPEBOB=KNF;CD6<JRD<IC=IGOE=?=85;CA?<=A?<:;8<D<>5@;A49:8;=;I?8@<4<=9:<67784946:8:;274839667:77487967657638367938756748445735635:36474645496845346824556533446747546513331254673444233325443432341221252533342323331232232313243323221222231313332113221222121222112212211212111212111111111111111112111111111111111101111111110111110111111101000100000000000000000000000000000000000000000000000000000000000000000";

    async function getPeaksFromJSON(peakFile) {
      if (IRON.peaksCache[peakFile]) {
          //console.log('get file from cache : ', peakFile);
          return IRON.peaksCache[peakFile];
      } else if (IRON.peaksCache[peakFile] === undefined) {
          // Store a promise in the cache for the ongoing fetch
          //console.log('load the peak! : ', peakFile);
          IRON.peaksCache[peakFile] = fetch(peakFile)
              .then(response => response.text())
              .then(peaksData => {
                  const peaks = peaksData.split('').map(c => (c.charCodeAt() - 48) / 100);
                  return peaks;
              });
  
          return IRON.peaksCache[peakFile];
      } else {
          // Wait for the ongoing fetch to complete
          return await IRON.peaksCache[peakFile];
      }
  }
  

    function shuffleArray(array) {
      for (let i = array.length - 1; i > 0; i--) {
          const j = Math.floor(Math.random() * (i + 1));
          [array[i], array[j]] = [array[j], array[i]]; // Swap elements
      }
    }

    (async () => {

      if (loadFakeWaveOnly || !peakFile) { //If we force fakewave from plugin option or peakfile does not exist
        peaks = defaultPeaks.split('').map(c => (c.charCodeAt() - 48) / 100);
        shuffleArray(peaks);
      } else {
        peaks = await getPeaksFromJSON(peakFile);
      }
      const progressType = ( singleTrack && typeof audioPlayer.data('tracklist-soundwave-style') !== 'undefined' ) ? audioPlayer.data('tracklist-soundwave-style') : audioPlayer.progressType;

      function initCreateWaves(){
        if (!imSticky && progressType == "simplebar" || imSticky && sonaar_music.option.waveformType == "simplebar") return;

        const container = sr_canvas_container;
        const $waveCut = $(container).parents('.sonaar_fake_wave').find('.sonaar_wave_cut');
        $waveCut.css('display', 'none');
        if( !$waveCut.attr('style').includes('width:') ){
          $waveCut.css('width', '0px');
        }
        waveBaseDiv         = container.closest(".sonaar_fake_wave");
        waveBaseWidth       = waveBaseDiv.clientWidth;
        canvasWidth         = (waveBaseWidth != 0) ? waveBaseWidth : 1000; //Set the width to 1000 when `waveBaseWidth` is 0 to prevent players from tabs, accordions, and other hidden elements from having a canvas width of 0.
        canvasHeight        = container.height;
       
        barWidth            = parseInt($(container).parents('[data-wave-bar-width]').attr('data-wave-bar-width'),10) || parseInt(sonaar_music.option.music_player_barwidth, 10) || 2; // Adjust as needed
        lineCap             = $(container).parents('[data-wave-line-cap]').attr('data-wave-line-cap') || sonaar_music.option.music_player_linecap  || 'square'; // Can be 'butt', 'round', or 'square'

        if($(container).parents('[data-wave-bar-gap]').length){//if shortcode parameters is set
          barGap              = parseInt($(container).parents('[data-wave-bar-gap]').attr('data-wave-bar-gap'),10);
        }else if(typeof sonaar_music.option.music_player_bargap !== 'undefined'){ // plugin option
          barGap              = parseInt(sonaar_music.option.music_player_bargap, 10); 
        }else{
          barGap              = 0;
        }

        if($(container).parents('[data-wave-fadein]').length){ //if shortcode parameters is set
          fadeDuration        = ($(container).parents('[data-wave-fadein]').attr('data-wave-fadein')==='false')? 1 : 350;
        }else if(typeof sonaar_music.option.music_player_wave_disable_fadein !== 'undefined'){ // plugin option
          fadeDuration        = ( sonaar_music.option.music_player_wave_disable_fadein !== 'on')? 350 : 1;
        }else{
          fadeDuration        = 350; // Duration in milliseconds for each bar to fully appear
        }
        if(singleTrack){
          fadeDuration = 1;
        }

        totalBarWidth       = barWidth + barGap;
        desiredNumBars      = canvasWidth / totalBarWidth;
        downsamplingFactor  = peaks.length / desiredNumBars; // Calculate the downsampling factor dynamically based on desired number of bars
        numBars              = Math.ceil(peaks.length / downsamplingFactor);
        if(typeof waveColor === 'undefined'){
          waveColor = (imSticky) ? '#606060' : '#000000';
         
        };
        if(typeof waveProgressColor === 'undefined'){
          waveProgressColor = (imSticky) ? '#FFF' : '#202020';
        };

        createWaves(sr_canvas_container, waveColor, lineCap);
        createWaves(sr_canvas_progress, waveProgressColor, lineCap);

      }

      function createWaves(container, waveColor, lineCap) {
        const canvas    = container;
          const ctx       = canvas.getContext("2d");
          canvas.height   = canvasHeight;
          ctx.lineCap     = lineCap;
          ctx.lineWidth   = Math.floor(barWidth); // Ensure integer value
          var imProgressBar   = false;
          if($(container).parents('.sonaar_wave_cut').length){
            imProgressBar = true;
          }
          //canvas.width        = waveBaseWidth;
          /*console.log('------------------');
          console.log(audioPlayer[0]);
          console.log("Peaks Length Total: ", peaks.length);
          console.log("Canvas Width:", canvasWidth);
          console.log("totalBarWidth:", totalBarWidth);
          console.log("desiredNumBars:", desiredNumBars);
          console.log("downsamplingFactor:", downsamplingFactor);
          console.log("numBars:", numBars);*/
          var barColor;
          if (waveColor.substring(0, 3) === "rgb") {
            barColor = reformatRgb(waveColor);
          }else{
            barColor = hexToRgb(waveColor);
          }

          const startTime = Date.now(); // Start time of the animation

         

          function drawBars() {
            if(!imSticky && !singleTrack && IRON.addDownloadSVGButton.saveSVG){
              IRON.addDownloadSVGButton.svgElements = [];
            }
            ctx.clearRect(0, 0, canvasWidth, canvasHeight); // Clear the canvas

            const currentTime         = Date.now();
            let anyBarNotFullyOpaque  = false;
            const modifHeight         = (lineCap === 'butt') ? 0 : barWidth;
            const maxHeight           = Math.floor((canvasHeight - modifHeight) * 2);
            const maxPeak             = Math.max(...peaks);
            const scalingFactor       = maxHeight / (maxPeak * 2);
        
            for (let i = 0; i < numBars; i++) {
                const index       = Math.floor(i * downsamplingFactor);
                let barHeight     = peaks[index] * scalingFactor;
                if(imProgressBar){
                  barHeight = barHeight + 2.5;
                }
                barHeight         = Math.floor(barHeight);
                barHeight         = barHeight === 1 ? 2 : barHeight;
                const x           = Math.floor(i * (barWidth + barGap));
                const y           = Math.round((canvasHeight - barHeight)/2);
                const timeElapsed = currentTime - startTime;
                let opacity       = (timeElapsed - i * (fadeDuration / numBars)) / fadeDuration;
                opacity           = Math.min(Math.max(opacity, 0), barColor.a); // Ensure opacity doesn't exceed initial alpha value
                ctx.strokeStyle   = `rgba(${barColor.r}, ${barColor.g}, ${barColor.b}, ${opacity})`;
                
                ctx.beginPath();
                ctx.moveTo(x + barWidth / 2, y);
                ctx.lineTo(x + barWidth / 2, y + barHeight);
                ctx.stroke();

                if(!imSticky && !singleTrack && IRON.addDownloadSVGButton.saveSVG){
                  IRON.addDownloadSVGButton.svgElements.push(`<rect x="${x}" y="${y}" width="${barWidth}" height="${barHeight+1}" fill="#000000" rx="roundingValue" ry="roundingValue"/>`);

                  //square IRON.addDownloadSVGButton.svgElements.push(`<rect x="${x}" y="${y}" width="${barWidth}" height="${barHeight+1}" fill="#000000"/>`);
                }

                if (opacity < barColor.a) {
                  anyBarNotFullyOpaque = true;
                }

            }

            if(!imSticky && !singleTrack && IRON.addDownloadSVGButton.saveSVG){
              IRON.addDownloadSVGButton.svgString = `<svg width="${canvasWidth}" height="${canvasHeight}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ${canvasWidth} ${canvasHeight}">${IRON.addDownloadSVGButton.svgElements.join('')}</svg>`;
            }
            if (anyBarNotFullyOpaque) {
              requestAnimationFrame(drawBars);
            }
        }
        
        drawBars(); // Start the animation
        $(container).parents('.sonaar_fake_wave').find('.sonaar_wave_cut').css('display', 'inherit');


    };

    function hexToRgb(hex) {
      let r = 0, g = 0, b = 0, a = 1;
  
      // Remove the hash at the start if it's there
      hex = hex.replace(/^#/, '');
  
      if (hex.length === 3) { // Convert #RGB to #RRGGBB
          hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
      }
  
      if (hex.length === 6) { // Process it as #RRGGBB
          r = parseInt(hex.slice(0, 2), 16);
          g = parseInt(hex.slice(2, 4), 16);
          b = parseInt(hex.slice(4, 6), 16);
      } else if (hex.length === 8) { // Process it as #RRGGBBAA
          r = parseInt(hex.slice(0, 2), 16);
          g = parseInt(hex.slice(2, 4), 16);
          b = parseInt(hex.slice(4, 6), 16);
          a = parseInt(hex.slice(6, 8), 16) / 255;
      }
      return { r, g, b, a };
    }

    function reformatRgb(color) {
      const colorValues = color.match(/(\d+(\.\d+)?)%?/g).map(Number);
      const isRGBA = color.includes("rgba");
      const alpha = isRGBA ? colorValues[3] : 1;
      return {
        r: colorValues[0],
        g: colorValues[1],
        b: colorValues[2],
        a: alpha
      };
    }
  
    var waveColor         = $(audioPlayer).attr('data-wave-color') || audioPlayer.soundwaveColorBG || sonaar_music.option.music_player_timeline_color;
    var waveProgressColor = $(audioPlayer).attr('data-wave-progress-color') || audioPlayer.soundwaveProgressColor || sonaar_music.option.music_player_progress_color;
    
    if(singleTrack){
      if(typeof audioPlayer.data('tracklist-wave-color') != 'undedined') {
        waveColor = audioPlayer.data('tracklist-wave-color') || sonaar_music.option.music_player_timeline_color;
      }
      if(typeof audioPlayer.data('tracklist-wave-progress-color') != 'undedined') {
        waveProgressColor = audioPlayer.data('tracklist-wave-progress-color') || sonaar_music.option.music_player_progress_color;
      }
    }


    /*if (IRON.isSonaarTheme && srp_pluginEnable) {
      IRON.audioPlayer.stickyEnable = true;
    }*/

    if (imSticky && sonaar_music.option.waveformType == "simplebar" ) {
      // set the heights of the bars
      let barHeight     = (typeof sonaar_music.option.sr_soundwave_height_simplebar !== 'undefined') ? sonaar_music.option.sr_soundwave_height_simplebar + "px" : "";
      let cssCode       = '.sonaar_fake_wave .sonaar_wave_base, .sonaar_fake_wave .sonaar_wave_cut, div#sonaar-player .srp_extendedPlayer_container .sr_progressbar_sticky .wave{ height: ' + barHeight +' !important;}';
      let $inlineStyle  = $("#sonaar-music-inline-css");

      if ($inlineStyle.length === 0 || !$inlineStyle.text().includes(cssCode)) {
        $inlineStyle.append(cssCode);
      }

    }

    // RETURN IF SIMPLE BAR IS USED - NO NEED TO CREATE FAKEWAVES. This is 100% sur that sticky and player are simplebar.
    if (sonaar_music.option.waveformType == "simplebar" && progressType !== "mediaElement") {
      return;
    }

    if( progressType == "simplebar" ){
      audioPlayer = false; // set audio player to false because we wont use it anymore
    }


    if(singleTrack){

      var soundwaveWrapper    = $(singleTrack).find('.srp_soundwave_wrapper');
      //soundwaveWrapper.css('width', '100%');
      soundwaveWrapper.find('.sonaar_fake_wave').css('height', '40px').css('margin-top', '0px').css('margin-bottom', '0px');

      var sr_canvas_container     = $(singleTrack).find('.sonaar_wave_base canvas')[0];
      var sr_canvas_progress      = $(singleTrack).find('.sonaar_wave_cut canvas')[0];
      containerHeight             = $(singleTrack).find(".sonaar_fake_wave").css("height");
      sr_canvas_container.height  = parseInt(containerHeight, 10);
      sr_canvas_progress.height   = parseInt(containerHeight, 10);    

    }else if ( audioPlayer ) {

      var sr_canvas_container   = $(audioPlayer).find('.album-player .sonaar_wave_base canvas')[0];
      var sr_canvas_progress    = $(audioPlayer).find('.album-player .sonaar_wave_cut canvas')[0];

      if( $(audioPlayer).find(".album-player .sonaar_fake_wave").css("height") === "0px" ){
        $(audioPlayer).find(".album-player .sonaar_fake_wave").css("height", "70px");
      };
      
      containerHeight             = $(audioPlayer).find(".album-player .sonaar_fake_wave").css("height");
      

      sr_canvas_container.height  = parseInt(containerHeight, 10);
      sr_canvas_progress.height   = parseInt(containerHeight, 10);     


      if(!imSticky){
        audioPlayer.find('.album-player .sonaar_wave_base').css('background-color', 'unset');
        audioPlayer.find('.album-player .sonaar_wave_cut').css('background-color', 'unset');
      }

    }
    //CREATE THE RANDOM PEAK BARS with conditions to prevent to many if. numBars is pretty high
    if( !imSticky && typeof sr_canvas_container !== 'undefined' && sr_canvas_container !== null ){
      // PLAYER
      initCreateWaves();
      return;
    }


    if(imSticky ){
      // STICKY PLAYER
      var sr_canvas_container = document.getElementById('splayer-wave-container');
      var sr_canvas_progress  = document.getElementById('splayer-wave-progress');
      
      //set soundwave colors
      if (typeof IRON.audioPlayer.activePlayer !== 'undefined' && IRON.audioPlayer.activePlayer.adaptiveColors && sonaar_music.option.sticky_player_disable_adaptive_colors != 'true'){
        waveColor           = (typeof IRON.audioPlayer !== 'undefined' && typeof IRON.audioPlayer.activePlayer.adaptiveColors !== 'undefined') ? IRON.audioPlayer.activePlayer.paletteColorsHex[2] : sonaar_music.option.sticky_player_soundwave_bars;
        waveProgressColor   = (typeof IRON.audioPlayer !== 'undefined' && typeof IRON.audioPlayer.activePlayer.adaptiveColors !== 'undefined') ? IRON.audioPlayer.activePlayer.paletteColorsHex[1] : sonaar_music.option.sticky_player_soundwave_progress_bars;
      }else{
        waveColor           = sonaar_music.option.sticky_player_soundwave_bars;
        waveProgressColor   = sonaar_music.option.sticky_player_soundwave_progress_bars;
      }

      initCreateWaves();
      return;
    }
  })();
  };
  
  // Debounce function
  function debounce(func, wait) {
    let timeout;
    return function(...args) {
      const context = this;
      clearTimeout(timeout);
      timeout = setTimeout(() => func.apply(context, args), wait);
    };
  }
  
  // Function to generate a unique identifier for each wave
  function getWaveIdentifier(audioPlayer, imSticky, singleTrack) {
    let identifier = '';

    if (audioPlayer) {
      if(!singleTrack){
        identifier += 'player_' + audioPlayer.id;
      }
    }

    if (imSticky) {
      identifier += 'sticky';
    }

    if (singleTrack && singleTrack.length > 0) {
      const trackIndex = singleTrack.index();
      identifier += 'player_' + audioPlayer.id + '_trackIndex_' + trackIndex; 
    }

    return identifier || 'default';
  }

  removePlayerResizeListeners = function(playerId) {
    waveResizeHandlers.forEach((handler, waveId) => {
        if (waveId && waveId.includes("trackIndex_")) {
          if(waveId.includes(playerId)){
            window.removeEventListener('resize', handler);
            waveResizeHandlers.delete(waveId); // Remove the item from the map
          }
        }
    });
  }

  const waveResizeHandlers = new Map();
  // Assign the function to the IRON object
  IRON.createFakeWave = createFakeWave; // IRON.createFakeWave introduced in v5, but MP3 Audio Player Pro v4 supports only createFakeWave(). So we need to keep both for backward compatibility
  IRON.removePlayerResizeListeners = removePlayerResizeListeners; 


  //Replace CTA button by ellipsis on small device
  sr_playerCTAresponsive = function () {
    $('.iron-audioplayer:not(.srp_has_customfields):not(.srp_tracklist_grid)').each(function () {
      const selector = $(this).parents('[class*="sr_track_inline_cta_bt"]:not(.srp_track_cta_fixed)');
      if( selector.length ){
        let sr_ctaEnable = true;
        $(this).find('.sr-playlist-item:not([data-relatedtrack="1"])').each(function () {
          const min_breakpoint = ($(this).find('.audio-track').width() < 200 )? 200: $(this).find('.audio-track').width();
          if( min_breakpoint + $(this).find('.song-store-list-container').width() >= $(this).width()){
            sr_ctaEnable = false;
          }
        })
        if (sr_ctaEnable ) {
          selector.removeClass('sr_track_inline_cta_bt__no')
          selector.addClass('sr_track_inline_cta_bt__yes')
        } else {
          selector.removeClass('sr_track_inline_cta_bt__yes')
          selector.addClass('sr_track_inline_cta_bt__no')
        }
      }
    })
  }

  //Close And Open Player widget Store List container
  $(document).on('click', function () {
    closeStoreListContainer('.srp_cta_opened');
  });

  $('.store-list').on('click', function () {
    if (!$(this).find('.srp_cta_opened').length) {
      openStoreListContainer($(this).find('.song-store-list-menu')[0]);
    }
  });

  openStoreListContainer = function (el) {
    if ($(el).parents('.sr_track_inline_cta_bt__yes').length) {
      return;
    }
    closeStoreListContainer('.srp_cta_opened');
    var theyShouldBeClosed = setInterval(function () { // Wait until all other store list container is closed
      if ($('.srp_cta_opened').length == 0) {
        $(el).find('.song-store-list-container').show(0, function () {
        }).animate({ opacity: 1 }, 150, function () {
          $(el).find('.song-store-list-container').addClass('srp_cta_opened');
        });
        $(el).find('.song-store-list-container').addClass('srp_cta_ready');
        clearInterval(theyShouldBeClosed);
      }
    }, 100);
  }

  closeStoreListContainer = function (el) {
    if ($(el).parents('.sr_track_inline_cta_bt__yes').length) {
      return;
    }
    $(el).animate({ opacity: 0 }, 150, 'swing', function () {
      $(el).hide(0);
    });
    $(el).removeClass('srp_cta_opened');
  }

  //Call Function on window Resize
  let resizeTimer;
  $(window).resize(function () {
    if (resizeTimer != null) window.clearTimeout(resizeTimer);
    resizeTimer = window.setTimeout(function () {

      //Call function here...
      sr_playerCTAresponsive();

    }, 200);
  });

  //Take "01:05:25" and return "3925" sec.
  convertTime = function (time) {
    time = time.toString().split(':').reverse();
    let newTime = 0;
    $(time).each(function (index) {
      newTime = newTime + parseInt(this) * (60 ** (index));
    })
    return (newTime);
  }
  
})(jQuery);

//Check if the we are int the guttenberg editor
function isGutenbergActive() {
  return document.body.classList.contains('block-editor-page');
}
