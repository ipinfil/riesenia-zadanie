<?php

declare(strict_types=1);
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Images Model.
 *
 * @method \App\Model\Entity\Image                                             newEmptyEntity()
 * @method \App\Model\Entity\Image                                             newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Image[]                                           newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Image                                             get($primaryKey, $options = [])
 * @method \App\Model\Entity\Image                                             findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Image                                             patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Image[]                                           patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Image|false                                       save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Image                                             saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Image[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Image[]|\Cake\Datasource\ResultSetInterface       saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Image[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Image[]|\Cake\Datasource\ResultSetInterface       deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ImagesTable extends Table
{
    /**
     * Initialize method.
     *
     * @param array $config the configuration for the Table
     *
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('images');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator validator instance
     *
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->integer('height')
            ->requirePresence('height', 'create')
            ->notEmptyString('height');

        $validator
            ->integer('width')
            ->requirePresence('width', 'create')
            ->notEmptyString('width');

        $validator
            ->add('image', [
                'mimeType' => [
                    'rule' => ['mimeType', ['image/jpg', 'image/png', 'image/jpeg']],
                    'message' => 'JPG and PNG are the only allowed formats.',
                ],
            ]);

        $validator
            ->requirePresence('top')
            ->integer('top')
            ->greaterThanOrEqual('top', 0)
            ->lessThanField('top', 'height');

        $validator
            ->requirePresence('left')
            ->integer('left')
            ->greaterThanOrEqual('left', 0)
            ->lessThanField('left', 'width');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules the rules object to be modified
     *
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['path']), ['errorField' => 'path']);

        return $rules;
    }

    public function findSized(Query $query, array $options)
    {
        $columns = [
            'Images.id',
            'Images.path',
            'Images.width',
            'Images.height',
            'Images.created',
            'Images.modified',
        ];

        $query = $query
            ->select($columns)
            ->distinct($columns)
            ->where(['width' => $options['width']])
            ->andWhere(['height' => $options['height']]);

        return $query->group(['id']);
    }
}
