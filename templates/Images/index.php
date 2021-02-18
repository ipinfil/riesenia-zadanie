<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Image[]|\Cake\Collection\CollectionInterface $images
 */
?>
<div class="images index content">
    <?= $this->Html->link(__('New Image'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Images') ?></h3>
    <div class="images form">
            <?= $this->Form->create($search) ?>
            <fieldset>
                
                <?php
                    echo $this->Form->control('search', ['type' => 'text']);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('path') ?></th>
                    <th><?= $this->Paginator->sort('height') ?></th>
                    <th><?= $this->Paginator->sort('width') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($images as $image): ?>
                <tr>
                    <td><?= $this->Number->format($image->id) ?></td>
                    <td><img src="<?= '/' . h($image->path) ?>"></td>
                    <td><?= $this->Number->format($image->height) ?></td>
                    <td><?= $this->Number->format($image->width) ?></td>
                    <td><?= h($image->created) ?></td>
                    <td><?= h($image->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $image->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $image->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $image->id], ['confirm' => __('Are you sure you want to delete # {0}?', $image->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
