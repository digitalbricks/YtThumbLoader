<?php
class YtThumbLoader{
    private $videoID = "";
    private $cacheDirectory = "";
    private $cacheDuration = 10080; // one week
    private $cacheBaseUrl = "";
    private $ytBaseUrl = "https://www.youtube-nocookie.com/embed/";

    /**
     * @param string $cacheBaseUrl
     */
    public function setCacheBaseUrl($cacheBaseUrl)
    {
        $this->cacheBaseUrl = trim($cacheBaseUrl);
    }

    /**
     * @return string
     */
    public function getCacheBaseUrl()
    {
        return trim($this->cacheBaseUrl);
    }


    /**
     * @param string $id
     */
    public function setVideoID(string $id){
        $this->videoID = trim($id);
    }

    /**
     * @return string
     */
    public function getVideoID(){
        return $this->videoID;
    }

    /**
     * @param string $cacheDirectory
     */
    public function setCacheDirectory(string $cacheDirectory)
    {
        $this->cacheDirectory = trim($cacheDirectory);
    }

    /**
     * @return string
     */
    public function getCacheDirectory()
    {
        return trim($this->cacheDirectory);
    }

    /**
     * @param int $cacheDuration
     */
    public function setCacheDuration($cacheDuration)
    {
        $this->cacheDuration = intval($cacheDuration);
    }

    /**
     * @return int
     */
    public function getCacheDuration()
    {
        return $this->cacheDuration;
    }

    /**
     * @param string $ytBaseUrl
     */
    public function setYtBaseUrl($ytBaseUrl)
    {
        $this->ytBaseUrl = trim($ytBaseUrl);
    }

    /**
     * @return string
     */
    public function getYtBaseUrl()
    {
        return $this->ytBaseUrl;
    }

    /**
     * @return string
     */
    public function getVideoUrl(){
        return $this->getYtBaseUrl().$this->getVideoID();
    }

    /**
     * @return string
     */
    public function getThumbnailUrl(){
        // check prerequisites
        $errors = array();
        if($this->getCacheDirectory() == ""){
            $errors[] = "Cache directory not set! Use set <code>setCachdDrectory()</code>";
        }
        if($this->getVideoID() == ""){
            $errors[] = "Youtube video ID not set! Use set <code>setVideoID()</code>";
        }
        if($this->getCacheBaseUrl() == ""){
            $errors[] = "Cache base url not set! Use set <code>setCacheBaseUrl()</code>";
        }

        // output error message and die
        if(count($errors)){
            $err_msg = "<strong>One or more errors occured:</strong><br>";
            foreach ($errors as $error){
                $err_msg.= $error."<br />";
            }
            die($err_msg);
        }

        // run garbage collector
        $this->garbageCollector();

        // creating youtube url from ID, such as
        // https://img.youtube.com/vi/buCD-_1UPn4/maxresdefault.jpg
        $yt_img_url = "https://img.youtube.com/vi/{$this->getVideoID()}/maxresdefault.jpg";
        // -- fallback – as not all videos have maxresdefault.jpg availabletube.com/vi/{$this->getVideoID()}/mqdefault.jpg";
        $yt_img_url_fallback = "https://img.youtube.com/vi/{$this->getVideoID()}/mqdefault.jpg";

        // try to get the thumbnail image, starting with maxresdefault
        if($this->downloadThumbnail($yt_img_url)){
            $success = true;
        }elseif($this->downloadThumbnail($yt_img_url_fallback)){
            $success = true;
        }else{
            $success = false;
        }

        // return url of the downloaded thumbnail
        if($success){
            return $this->getCacheBaseUrl()."/".$this->getVideoID().".jpg";
        }
    }

    /**
     * Get JPEG image from URL and store it localy
     *
     * @param string $url the URL of the image
     *
     * @return bool true if succeeded, false if not
     */
    private function downloadThumbnail($url){

        // check if cache directory exists and create it if not
        if(!file_exists($this->cacheDirectory) OR !is_dir($this->cacheDirectory)){
            mkdir($this->cacheDirectory, 0755);
        }

        // check if thumbnail already exists
        $saveto = $this->cacheDirectory.DIRECTORY_SEPARATOR.$this->videoID.'.jpg';
        if(file_exists($saveto)){
            // if the thumbnail is NOT expired just return true
            if(time()-filemtime($saveto) < $this->getCacheDuration()){
                return true;
            } else {
                // if file thumbnail EXPIRED, delete file
                unlink($saveto);
            }
        }

        $ch = curl_init ($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
        curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        $raw=curl_exec($ch);

        // get the content type
        $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

        // get the HTTP status code
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // close cURL session
        curl_close ($ch);


        // check if we got a JPEG image
        if($status != 200 OR $content_type!="image/jpeg"){
            return false;
        } else {
            // yeah, we got an image - now lets save it
            $fp = fopen($saveto,'x');
            fwrite($fp, $raw);
            fclose($fp);

            return true;
        }

    }

    /**
     * return void
     */
    private function garbageCollector(){
        // get all .jpg files in cache directory
        $cachedir = $this->getCacheDirectory();
        $files = glob($cachedir."/*.jpg");

        // check all .jpg files if they have expired
        foreach($files as $file){
            if(time()-filemtime($file) >= $this->getCacheDuration()) {
                // if file thumbnail EXPIRED, delete file
                unlink($file);
            }
        }
    }
}