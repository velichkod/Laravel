<?php
/**
 * Created by PhpStorm.
 * User: optima
 * Date: 8/15/16
 * Time: 11:08 AM
 */

namespace Optimait\Laravel\Services;


class UrlDownload
{
    private $isSecure = false;
    private $folder = './uploads/';
    private $filename;
    private $url;
    private $username;
    private $password;

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     * @return UrlDownload
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     * @return UrlDownload
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isSecure()
    {
        return $this->isSecure;
    }

    /**
     * @param boolean $isSecure
     * @return UrlDownload
     */
    public function setSecure($isSecure)
    {
        $this->isSecure = $isSecure;
        return $this;
    }

    /**
     * @return string
     */
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * @param string $folder
     * @return UrlDownload
     */
    public function setFolder($folder)
    {
        $this->folder = $folder;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param mixed $filename
     * @return UrlDownload
     */
    public function saveAs($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    public function getFullPath()
    {
        return $this->folder . $this->filename;
    }

    /**
     * @param mixed $url
     * @return UrlDownload
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function __construct($url = '', $filename = '')
    {
        $this->url = $url;
        $this->filename = $filename;
    }

    public function download()
    {
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_HEADER, 0);
        curl_setopt($curl_handle, CURLOPT_URL, $this->getUrl());
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);

        if ($this->isSecure()):
            curl_setopt($curl_handle, CURLOPT_USERPWD, $this->getUsername() . ":" . $this->getPassword());
        endif;

        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
        $buffer = curl_exec($curl_handle);
        //$httpcode = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);
        //echo $httpcode;
        curl_close($curl_handle);


        file_put_contents($this->getFullPath(), $buffer);
    }


    public static function From($url){
        $urlDownload = new UrlDownload($url);
        return $urlDownload;
    }
}