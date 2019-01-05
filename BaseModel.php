<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;

class   BaseModel extends Model
{

    public $errors;

    protected static $rules = array();

    protected $validator;


    public function __construct(array $attributes = array(), Validator $validator = null)
    {
        parent::__construct($attributes);
        $this->validator = $validator ?: App::make('validator');
    }


    protected static function boot()
    {
        parent::boot();

        static::saving(function($model)
        {
            return $model->validate();
        });
    }

    public function validate()
    {
        $v = $this->validator->make($this->attributes, static::$rules);

        if ($v->passes())
        {
            return true;
        }
        $this->setErrors($v->messages());
        return false;
    }

    protected function setErrors($errors)
    {

        $this->errors = $errors;

    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function hasErrors()
    {
        return ! empty($this->errors);
    }

}
