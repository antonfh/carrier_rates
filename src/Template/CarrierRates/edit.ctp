<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $carrierRate->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $carrierRate->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Carrier Rates'), ['action' => 'index']) ?></li>
    </ul>
</div>
<div class="carrierRates form large-10 medium-9 columns">
    <?= $this->Form->create($carrierRate); ?>
    <fieldset>
        <legend><?= __('Edit Carrier Rate') ?></legend>
        <?php
            echo $this->Form->input('service_name');
            echo $this->Form->input('service_code');
            echo $this->Form->input('total_price');
            echo $this->Form->input('currency');
            echo $this->Form->input('postal_code');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
