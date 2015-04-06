<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('Edit Carrier Rate'), ['action' => 'edit', $carrierRate->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Carrier Rate'), ['action' => 'delete', $carrierRate->id], ['confirm' => __('Are you sure you want to delete # {0}?', $carrierRate->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Carrier Rates'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Carrier Rate'), ['action' => 'add']) ?> </li>
    </ul>
</div>
<div class="carrierRates view large-10 medium-9 columns">
    <h2><?= h($carrierRate->id) ?></h2>
    <div class="row">
        <div class="large-5 columns strings">
            <h6 class="subheader"><?= __('Service Name') ?></h6>
            <p><?= h($carrierRate->service_name) ?></p>
            <h6 class="subheader"><?= __('Service Code') ?></h6>
            <p><?= h($carrierRate->service_code) ?></p>
            <h6 class="subheader"><?= __('Currency') ?></h6>
            <p><?= h($carrierRate->currency) ?></p>
        </div>
        <div class="large-2 columns numbers end">
            <h6 class="subheader"><?= __('Id') ?></h6>
            <p><?= $this->Number->format($carrierRate->id) ?></p>
            <h6 class="subheader"><?= __('Total Price') ?></h6>
            <p><?= $this->Number->format($carrierRate->total_price) ?></p>
            <h6 class="subheader"><?= __('Postal Code') ?></h6>
            <p><?= $this->Number->format($carrierRate->postal_code) ?></p>
        </div>
        <div class="large-2 columns dates end">
            <h6 class="subheader"><?= __('Created') ?></h6>
            <p><?= h($carrierRate->created) ?></p>
            <h6 class="subheader"><?= __('Updated') ?></h6>
            <p><?= h($carrierRate->updated) ?></p>
        </div>
    </div>
</div>
