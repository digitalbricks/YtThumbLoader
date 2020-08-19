<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>yt-thumb-loader DEMO</title>

    <!-- this styles are just for demo purposes -->
    <style>
        html{
            font-family: Helvetica, Arial, sans-serif;
        }
        a{
            color: #0080c5;
        }
        .container{
            width: 95%;
            margin: 0 auto;
            max-width: 960px;
        }
        .yt-video {
            margin: 2em 0;
        }
        .yt-video .yt-video__video-wrapper{
            position: relative;
        }
        .yt-video .yt-video__preview {
            display: block;
            width: 100%;
        }
        .yt-video iframe{
            visibility: hidden;
        }
        .yt-video iframe.active{
            visibility: visible;
        }
        .yt-video iframe,
        .yt-video .yt-video__overlay{
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        .yt-video .yt-video__overlay {
            z-index: 50;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .yt-video .yt-video__info{
            font-size: 0.9em;
            box-sizing: border-box;
            text-align: center;
            width: 90%;
            margin: 0 auto;
            max-width: 500px;
        }
        .yt-video .yt-video__text{
            background-color: rgba(0, 0, 0, 0.5);
            padding: 1em;
            margin-top: 1em;
            color: #fff;
        }
        .yt-video .yt-video__playbutton{
            cursor: pointer;
        }
    </style>

</head>
<body>

<?php
// load class
require '../YtThumbLoader.php';

// instantiate object
$ytvideo = new YtThumbLoader();

// set cache directory
// in this example we use /cache below the current folder
$ytvideo->setCacheDirectory(dirname(__FILE__)."/cache");

// set cache base url (needed for return of full qualified thumbnail image url)
// in this example we use the request uri to the current demo php file, suffixed with cache
$ytvideo->setCacheBaseUrl("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"."cache");

// set the ID of the youtube video you want
// for https://www.youtube.com/watch?v=dQw4w9WgXcQ the ID would be dQw4w9WgXcQ
$ytvideo->setVideoID("dQw4w9WgXcQ");

// finally we start the download-and-cache process, wich will return the URL of the cache image (or false if failed)
$thumburl =  $ytvideo->getThumbnailUrl();
$videourl = $ytvideo->getVideoUrl();

?>

<!-- Finally we can build our frontend markup for a 2-click-solution. -->
<div class="container">
<?php if($thumburl):?>
    <h1>First video</h1>
    <div class="yt-video">
        <div class="yt-video__video-wrapper">

            <img class="yt-video__preview" src="<?=$thumburl?>" alt="Youtube Preview Image">

            <iframe class="yt-video__iframe"  id="yt-iframe-<?=$ytvideo->getVideoID()?>" data-src="<?=$videourl?>" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" style="border:0;" allowfullscreen="" width="560" height="315"></iframe>


            <div class="yt-video__overlay">
                <div class="yt-video__info">

                    <div class="yt-video__playbutton" data-target='yt-iframe-<?=$ytvideo->getVideoID()?>' width="60" height="60">
                        <svg height="60" viewBox="0 0 60 60" width="60" xmlns="http://www.w3.org/2000/svg"><circle cx="30" cy="30" fill="#0080c5" r="30"/><path d="m46.63 30-26.45-15.27v30.54z" fill="#fff"/></svg>
                    </div>
                    <div class="yt-video__text">
                        By playing the video, data is retrieved from and transmitted to YouTube (Google).
                        Please see our <a href="/datenschutz/" target="_blank">privacy police</a>.
                    </div>

                </div>

            </div>

        </div>

    </div>

<?php endif; ?>
</div>




<?php
// lets get another video, just fore demo
$ytvideo->setVideoID("buCD-_1UPn4");
$thumburl2 =  $ytvideo->getThumbnailUrl();
$videourl2 = $ytvideo->getVideoUrl();
?>

<div class="container">
    <?php if($thumburl2):?>
        <h1>Second video</h1>
        <div class="yt-video">
            <div class="yt-video__video-wrapper">

                <img class="yt-video__preview" src="<?=$thumburl2?>" alt="Youtube Preview Image">

                <iframe class="yt-video__iframe"  id="yt-iframe-<?=$ytvideo->getVideoID()?>" data-src="<?=$videourl2?>" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" style="border:0;" allowfullscreen="" width="560" height="315"></iframe>


                <div class="yt-video__overlay">
                    <div class="yt-video__info">

                        <div class="yt-video__playbutton" data-target='yt-iframe-<?=$ytvideo->getVideoID()?>' width="60" height="60">
                            <svg height="60" viewBox="0 0 60 60" width="60" xmlns="http://www.w3.org/2000/svg"><circle cx="30" cy="30" fill="#0080c5" r="30"/><path d="m46.63 30-26.45-15.27v30.54z" fill="#fff"/></svg>
                        </div>
                        <div class="yt-video__text">
                            By playing the video, data is retrieved from and transmitted to YouTube (Google).
                            Please see our <a href="/datenschutz/" target="_blank">privacy police</a>.
                        </div>

                    </div>

                </div>

            </div>

        </div>

    <?php endif; ?>
</div>


<!-- In this example we use jquery, but feel free to modify markup and script to fit your needs! -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>

    // 2-Click Youtube video
    $('.yt-video .yt-video__playbutton').click(function (el) {
        // create selector for target iframe
        $target_iframe = "#" + $(this).data('target');

        // get data-src from iframe
        $video_url = $($target_iframe).data('src');

        // make iframe visible by adding .active class and add src attribute
        $($target_iframe).addClass('active').attr("src", $video_url);

        // hide overlay
        $(this).closest('.yt-video__overlay').fadeOut();
    });
</script>

</body>
</html>


