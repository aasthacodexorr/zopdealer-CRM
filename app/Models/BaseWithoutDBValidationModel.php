<?php

namespace App\Models;

use CodeIgniter\Model;

class BaseWithoutDBValidationModel extends Model
{
    public function errors(bool $forceDB = false)
    {
        if ($forceDB === false && $this->skipValidation === false) {
            $errors = $this->validation->getErrors();

            if (! empty($errors)) {
                return $errors;
            }
        }

        return NULL;
    }
}
