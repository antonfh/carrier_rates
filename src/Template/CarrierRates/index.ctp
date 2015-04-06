<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('New Carrier Rate'), ['action' => 'add']) ?></li>
    </ul>
</div>
<div class="carrierRates index large-10 medium-9 columns">
    <table cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th><?= $this->Paginator->sort('service_name') ?></th>
            <th><?= $this->Paginator->sort('service_code') ?></th>
            <th><?= $this->Paginator->sort('total_price') ?></th>
            <th><?= $this->Paginator->sort('currency') ?></th>
            <th><?= $this->Paginator->sort('postal_code') ?></th>
            <th><?= $this->Paginator->sort('created') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($carrierRates as $carrierRate): ?>
        <tr>
            <td><?= $this->Number->format($carrierRate->id) ?></td>
            <td><?= h($carrierRate->service_name) ?></td>
            <td><?= h($carrierRate->service_code) ?></td>
            <td><?= $this->Number->format($carrierRate->total_price) ?></td>
            <td><?= h($carrierRate->currency) ?></td>
            <td><?= $this->Number->format($carrierRate->postal_code) ?></td>
            <td><?= h($carrierRate->created) ?></td>
            <td class="actions">
                <?= $this->Html->link(__('View'), ['action' => 'view', $carrierRate->id]) ?>
                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $carrierRate->id]) ?>
                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $carrierRate->id], ['confirm' => __('Are you sure you want to delete # {0}?', $carrierRate->id)]) ?>
            </td>
        </tr>

    <?php endforeach; ?>
    </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
