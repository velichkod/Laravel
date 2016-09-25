<?php
/**
 * Created by PhpStorm.
 * User: optima
 * Date: 9/8/16
 * Time: 2:35 PM
 */

namespace Optimait\Laravel\Traits;


trait MetasTrait
{

    private $metasContainer = null;

    public function getMeta($key)
    {
        if (is_null($this->metasContainer)) {
            if ($this->metas):
                foreach ($this->metas as $meta) {
                    $this->metasContainer[$meta->meta_key] = $meta->meta_value;
                }
            endif;
        }
        if (isset($this->metasContainer[$key])) {
            return $this->metasContainer[$key];
        }

        return null;

    }


    public function saveMetas($metaAr)
    {
        if (!empty($metaAr)) {
            $insAr = [];
            foreach ($metaAr as $k => $v) {
                $insAr[] = $this->getMetaModel([
                    'meta_key' => $k,
                    'meta_value' => is_array($v) ? serialize($v) : $v
                ]);
            }
            $this->metas()->saveMany($insAr);
        }
    }


    public function renewMetas($metaAr){
        if(!empty($metaAr)){
            $this->metas()->delete();
            $this->saveMetas($metaAr);
        }
    }
}