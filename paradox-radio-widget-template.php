<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
$artist = '';
$title = '';
?>
<script type="text/javascript">
    var webplayer = new WebPlayer('http://server30379.streamplus.de/;stream.mp3', '', '/wp-content/plugins/paradox-radio-widget/swf/', '');
    var volume = webplayer.getVolume();
    jQuery(function($) {
        $( 'a').focus(function() {
            $(this).blur();
        });
        $( ".paradox-shoutcast-volume-slider" ).slider({
            range: "min",
            min: 0,
            max: 100,
            value: (volume >= 0 && volume <= 100 ? volume : 60),
            slide: function( event, ui ) {
                webplayer.setVolume(ui.value);
            }
        });
        var click_play = function(e) {
            e.preventDefault();

            var icon = $(this).find('.icon');

            if ($(this).hasClass('playing')) {
                webplayer.stop();
                $(this).removeClass('playing');
                icon.removeClass('icon-pause');
                icon.addClass('icon-play');
            }
            else {
                webplayer.play();
                $(this).addClass('playing');
                icon.removeClass('icon-play');
                icon.addClass('icon-pause');
            }
        };
        $('.paradox-shoutcast-play-button-shortcode, .paradox-shoutcast-play-button-widget').unbind('click').click(click_play);
        requestTitle = function() {
            var xmlRequest = $.ajax({
                method: "GET",
                url: "<?php echo site_url('/wp-content/plugins/paradox-radio-widget/paradox-radio-reloader.php'); ?>",
            });
            xmlRequest.done(function (response) {
//                console.log(response);
                var song = response.split(' - ');
//                console.log(song);
                $('.paradox-shoutcast-current-song-artist').html(song[0]);
                $('.paradox-shoutcast-current-song-title').html(song[1]);
            });
        };
        requestTitle();
        setInterval(requestTitle, 5000);
    });
</script>
<div class="paradox-radio-widget<?php echo $shortcode ? ' paradox-radio-widget-shortcode' : ' paradox-radio-widget'; ?>">
    <div class="paradox-shoutcast-background">
        <div class="paradox-shoutcast-background-image"></div>
            <div class="paradox-shoutcast-inner">
                <div class="paradox-shoutcast-logo-wrapper">
                    <img src="/wp-content/plugins/paradox-radio-widget/images/logo.png">
                </div>

                <?php if ($shortcode): ?>
                <div class="paradox-shoutcast-play-volume-wrapper">
                <?php endif; ?>

                <div class="paradox-shoutcast-play-button-wrapper">
                    <a href="" id="paradox-shoutcast-play-button" class="paradox-shoutcast-play-button <?php echo $shortcode ? ' paradox-shoutcast-play-button-shortcode' : ' paradox-shoutcast-play-button-widget'; ?>">
                        <i class="icon icon-play"></i>
                    </a>
                </div>

                <?php if ($shortcode): ?>
                <div class="paradox-shoutcast-current-song-volume">
                <?php endif; ?>

                <div class="paradox-shoutcast-current-song">
                    <p class="paradox-shoutcast-current-song-artist"><?php echo $artist ? $artist : ''; ?></p>
                    <p class="paradox-shoutcast-current-song-title"><?php echo $title ? $title : ''; ?></p>
                </div>
                <div class="paradox-shoutcast-volume-wrapper">
                    <i class="icon icon-volume-off"></i>
                    <div class="paradox-shoutcast-volume">
                        <div id="paradox-shoutcast-volume-slider" class="paradox-shoutcast-volume-slider"></div>
                    </div>
                    <i class="icon icon-volume-up"></i>
                </div>

                <?php if ($shortcode): ?>
                </div>
                </div>
                <div class="clearfix"></div>
                <?php endif; ?>

                <a class="paradox-shoutcast-link" href="http://www.paradox-radio.com" target="_blank">www.paradox-radio.com</a>
        </div>
    </div>
    <div class="paradox-shoutcast-social-media">
        <p class="paradox-shoutcast-staytuned">Stay tuned</p>
        <div class="paradox-shoutcast-social-media-wrapper">
            <a href="https://www.facebook.com/paradox.radio" target="_blank">
                <i class="icon icon-facebook"></i>
            </a>
            <a href="https://twitter.com/RadioParadox" target="_blank">
                <i class="icon icon-twitter"></i>
            </a>
            <a href="https://plus.google.com/+Paradox-radio/" target="_blank">
                <i class="icon icon-gplus"></i>
            </a>
            <a href="https://www.youtube.com/channel/UCrnFophVbdamzmqFyQ6vQ_g" target="_blank">
                <i class="icon icon-youtube"></i>
            </a>
        </div>
    </div>
</div>