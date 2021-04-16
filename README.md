# YtThumbLoader
A simple PHP class for downloading the thumbnail of a Youtube video to a local directory – which than can be used for creating a **privacy friendly 2-click-solution** as demonstrated in `/demo/index.php`.

## Basic Usage
```php
// load class
require 'YtThumbLoader.php';
$ytvideo = new YtThumbLoader();

// set cache directory
$ytvideo->setCacheDirectory("Path/to/your/cache-directory");

// set cache base url (needed for return of full qualified thumbnail image url)
$ytvideo->setCacheBaseUrl("https://example.com/ytcache");

// set the ID of the youtube video you want
// for https://www.youtube.com/watch?v=dQw4w9WgXcQ the ID would be dQw4w9WgXcQ
$ytvideo->setVideoID("dQw4w9WgXcQ");

// get (local) URL for the cache thumbnail
$thumburl =  $ytvideo->getThumbnailUrl();

// get (remote) URL of the YouTube video
$videourl = $ytvideo->getVideoUrl();
```

## Most important setter methods
* `setCacheDirectory(string)`**required**, sets the path to your local cache directory
* `setCacheBaseUrl(string)` **required**, sets the URL for the cache directory in your project, used for the (local) thumbnail url
* `setVideoID(string)`**required**, sets the
* `setCacheDuration(int)` optional, sets the cache duration for the thumbnail (default is 10800 wich is one week)
* `setYtBaseUrl(string)`optional, sets the base video url (default is _https://www.youtube-nocookie.com/embed/_ for YouTube in privacy mode – may be altered to _https://www.youtube.com/embed/_ if you want)

## Most important getter methods
* `getThumbnailUrl()` gets the URL of the locally stored thumbnail
* `getVideoUrl()`gets the embed video URL (for iframe usage)






