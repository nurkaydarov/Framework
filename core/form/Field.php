<?php

namespace app\core\form;

use app\core\Model;

class Field
{
    const TYPE_TEXT = 'text';
    const TYPE_PASSWORD = 'password';
    const TYPE_EMAIL = 'email';
    public string $type;
    public Model $model;
    public string $attribute;

    public function __construct(Model $model, string $attribute)
    {
        $this->type = self::TYPE_TEXT;
        $this->model = $model;
        $this->attribute = $attribute;
    }
    public function __toString()
    {
        return sprintf('
                 <div class="form-group">
                    <div class="mb-3">
                        <label for="inputLastname" class="form-label">%s</label>
                        <input type="%s" id="inputLastname" name="%s" value="%s" class="form-control %s">
                        <div class="invalid-feedback">
                        %s
                        </div>
                    </div>

                </div>
        ',
            ucfirst($this->attribute),
            $this->type,
            $this->attribute,
            $this->model->{$this->attribute},
            $this->model->hasError($this->attribute) ? "is-invalid" : "",
            $this->model->getFirstError($this->attribute)
        );
    }

    public function passwordField()
    {
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }

    public  function emailField()
    {
        $this->type = self::TYPE_EMAIL;
        return $this;
    }
}