<?php

declare(strict_types=1);
namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class SearchForm extends Form
{
    public function validationDefault(Validator $validator): Validator
    {
        $validator->regex('search', '/((\s*)([0-9]+)(\s*)(x)(\s*)([0-9]+)(\s*))/');

        return $validator;
    }

    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema->addField('search', 'string');
    }
}
