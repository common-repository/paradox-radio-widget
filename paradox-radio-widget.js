
var WebPlayer = function(sUrl, RMSinugg, sSmSwfUrl) {

    var defaults = {
        onPlaying:  function() {},
        onStopping: function() {},
        autoplay: false,
    };

    var settings = defaults;

    var that = this;
    var bMuted = false;
    var volume = 60;
    var sDecoratedUrl = sUrl;
    var streamSound = null;
    var bPlayerReady = false;

    this.adStarts = function() {
        //console.log('adStarts');
        if (settings.autoplay) {
            this.mute();
            this.play();
        }
    };

    this.adEnds = function() {
        //console.log('adEnds');
        if (settings.autoplay) {
            this.unmute();
            if (streamSound == null || streamSound.playState == 1) {
                this.play();
            }
        }
    };

    this.stop = function() {
        if (streamSound != null) {
            settings.onStopping();
            streamSound.stop();
            streamSound.destruct();
            streamSound = null;
        }
    };

    this.play = function() {
        //console.log('play called');
        if (streamSound != null) {
            //console.log('playing existing sound');
            if (streamSound.playState == 0) {
                streamSound.play();
            }
        } else {
            //console.log('creating new sound if player is ready?');
            if (bPlayerReady) {
                //console.log('player is ready. Creating new sound.');
                settings.onPlaying();
                streamSound = soundManager.createSound({
                    id: 'stream',
                    url: sDecoratedUrl,
                    autoLoad: false,
                    autoPlay: true,
                    multiShot: false,
                    volume: volume,
                });
                if (bMuted) {
                    streamSound.mute();
                }
            }
        }
    };

    this.setVolume = function(vol) {
        volume = vol;
        if (streamSound != null) {
            streamSound.setVolume(volume);
        }
        if (localStorage) {
            localStorage.setItem('radio.webplayer.volume', volume);
        }
    };

    this.getVolume = function() {
        return volume;
    };

    this.mute = function() {
        bMuted = true;
        if (streamSound != null) {
            streamSound.mute();
        }
    };

    this.unmute = function(vol) {
        bMuted = false;
        if (streamSound != null) {
            streamSound.unmute();
        }
    };

    function init() {
        // Prepare the decorated stream URL with ad stuff
        try {
            this.sDecoratedUrl = com_adswizz_synchro_decorateUrl(sUrl);
            if (RMSinugg != "") {
                this.sDecoratedUrl = this.sDecoratedUrl+"%3B"+RMSinugg;
            }
        } catch (e) {
            // Fallback: If anything's wrong with the ad stuff (e.g. an adblocker), fall back to not using it.
            this.sDecoratedUrl = sUrl;
        }

        // Load initial volume from local storage if set
        if (localStorage && localStorage.getItem("radio.webplayer.volume") !== null) {
            volume = Number(localStorage.getItem('radio.webplayer.volume'));
        }

        // initialize SoundManager
        // This must be called before DOMReady because SM2 will start up 
        // automatically on DOMReady, with or without our config!
        console.log('Setting up soundmanager...');
        soundManager.setup({
            url: sSmSwfUrl,
            flashVersion: 9, // optional: shiny features (default = 8)
            preferFlash: false, // ignore Flash where possible, use 100% HTML5 mode
            //useHTML5Audio: false,
            onready: function() {
                console.log('Player IS READY!');
                bPlayerReady = true;
                //that.play();
            },
        });

        // If the Ad doesn't load or if the user uses an AdBlocker, load our player after a timeout
        if (settings.autoplay) {
            setTimeout(that.play, 8000);
        }
    }

    init();
};