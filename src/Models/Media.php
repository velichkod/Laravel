<?php

namespace Optimait\Laravel\Models;


class Media extends \Eloquent
{
    protected $table = 'media';
    protected $fillable = array('filename', 'original_name', 'mime_type', 'filesize', 'folder');


    public static $sizes = array(
        array(100, 100, 'height'), array(250, 150, 'height')/*, array(270, 150), array(600,350,'height')*/
    );

    public $timestamps = false;


    /**
     * Has one relations
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function attachments()
    {
        return $this->hasOne('Optimait\Laravel\Models', 'media_id');
    }



    /**
     * one to one relationship with the users
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo('User', 'created_by');
    }

    public function selfDestruct()
    {
        foreach(self::$sizes as $size){
            @unlink($this->folder .$size[0].'X'.$size[1]. $this->filename);
        }

        @unlink($this->folder. $this->filename);
        //@unlink($this->folder. $this->filename);
        return $this->delete();
    }

}