<?php

namespace App;

trait HasCodeTrait
{
    public static function bootHasCodeTrait()
    {
        static::creating(function ($model) {
            $model->code = $model->generateCode();
        });
    }
}
