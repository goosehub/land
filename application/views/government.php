<?php if ($account) { ?>
<div id="government_block" class="center_block">
    <strong>Government Overview</strong>
    <button type="button" class="exit_center_block btn btn-default btn-sm pull-right">
      <span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>
    </button>
    <hr>
    <div class="row">
        <div class="col-md-4 government_card">
            <h3 class="text-center text-primary">Laws</h3>
            <!-- Form -->
            <?php echo form_open('laws_form', array('id' => 'laws_form', 'method' => 'post')); ?>
                <input type="hidden" name="world_key" value="<?php echo $world['id']; ?>">
                <div class="row">
                    <div class="col-md-4">
                        <?= generate_popover('Power Structure', 'Democracy: 10% corruption, 1 support per/min, socialism max support: 200 | Oligarchy: 20% corruption, 2 support per/min, socialism max support: 150 | Autocracy: 30% corruption, 3 support per/min, socialism max: 100 support', 'right', 'pull-right'); ?>
                        <label for="input_government" class="pull-right">Power Structure: </label>
                    </div>
                    <div class="col-md-8">
                        <select class="form-control" id="input_government" name="input_government" value="<?php echo $account['government']; ?>">
                            <option value="1" title="Difficult Support, Ordinary Corruption" <?php if ((int)$account['government'] === DEMOCRACY_KEY) { echo 'selected'; } ?>>Democracy</option>
                            <!-- Great for economic growth -->
                            <option value="2" title="Moderate Support, Harsh Corruption" <?php if ((int)$account['government'] === OLIGARCHY_KEY) { echo 'selected'; } ?>>Oligarchy</option>
                            <!-- Great for flexibility -->
                            <option value="3" title="Easy Support, Severe Corruption" <?php if ((int)$account['government'] === AUTOCRACY_KEY) { echo 'selected'; } ?>>Autocracy</option>
                            <!-- Great for early expansion and war -->
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-4">
                        <?= generate_popover('Taxes', 'Each 1% of taxes reduces your maximum support by 2', 'right', 'pull-right'); ?>
                        <label for="input_tax_rate" class="pull-right">Tax Rate: (%)</label>
                    </div>
                    <div class="col-md-8">
                        <input type="number" min="0" max="100" required class="form-control" id="input_tax_rate" name="input_tax_rate" value="<?php echo $account['tax_rate']; ?>">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-4">
                        <?= generate_popover('Economic Ideology', 'Marxist Socialism removes all income, but allows you to increase your max support and use support to create troops.', 'right', 'pull-right'); ?>
                        <label for="input_government" class="pull-right">Economic Ideology: </label>
                    </div>
                    <div class="col-md-8">
                        <div class="col-md-3">
                            <label for="input_ideology" class="pull-right text-success">Free Market</label>
                        </div>
                        <div class="col-md-3">
                            <input type="radio" class="form-control" id="free_market" name="input_ideology" value="<?php echo FREE_MARKET_KEY; ?>" <?php echo $account['ideology'] == FREE_MARKET_KEY ? 'checked' : ''; ?> >
                        </div>
                        <div class="col-md-3">
                            <label for="input_ideology" class="pull-right text-danger">Marxist Socialism</label>
                        </div>
                        <!-- Socialism eliminates all profit, units cost support instead of money -->
                        <div class="col-md-3">
                            <input type="radio" class="form-control" id="socialism" name="input_ideology" value="<?php echo SOCIALISM_KEY; ?>" <?php echo $account['ideology'] == SOCIALISM_KEY ? 'checked' : ''; ?> >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 col-md-push-4">
                        <hr>
                        <div id="pass_new_laws_button" class="btn btn-action form-control text-is-bold">Sign Into Law</div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-4 government_card">
            <h3 class="text-center text-primary">Budget</h3>
            <span class="budget_info_item_parent">
                <strong class="budget_info_item_label">
                    GDP:
                    <?php echo generate_popover('GDP', 'The sum of the GDP from from every territory', 'right'); ?>
                </strong>
                <strong class="budget_info_value text-action">
                    $<span id="budget_gdp"></span> Million
                </strong><br>
            </span>
            <span class="budget_info_item_parent">
                <strong class="budget_info_item_label">
                    Tax Income:
                    <?php echo generate_popover('Tax Income', 'The percentage of GDP captured by the Tax Rate', 'right'); ?>
                </strong>
                <strong class="budget_info_value text-success">
                    $<span id="budget_tax_income"></span> Million
                </strong><br>
            </span>
            <span class="budget_info_item_parent">
                <strong class="budget_info_item_label">
                    Corruption From Power:
                    <?php echo generate_popover('Corruption', 'Corruption From Power is caused by the power structure of your government', 'right'); ?>
                </strong>
                <strong class="budget_info_value text-red">
                    -$<span id="budget_power_corruption"></span> Million
                </strong><br>
            </span>
            <span class="budget_info_item_parent">
                <strong class="budget_info_item_label">
                    Corruption From Size:
                    <?php echo generate_popover('Corruption', 'Corruption From Size is increased by 1 percent for every ' . TILES_PER_CORRUPTION_PERCENT . ' territories you control', 'right'); ?>
                </strong>
                <strong class="budget_info_value text-red">
                    -$<span id="budget_size_corruption"></span> Million
                </strong><br>
            </span>
            <span class="budget_info_item_parent">
                <strong class="budget_info_item_label">
                    Federal Administrative:
                    <?php echo generate_popover('Federal Administrative', 'Costs for Federal Industry', 'right'); ?>
                </strong>
                <strong class="budget_info_value text-red">
                    -$<span id="budget_federal"></span> Million
                </strong><br>
            </span>
            <span class="budget_info_item_parent">
                <strong class="budget_info_item_label">
                    Military Bases:
                    <?php echo generate_popover('Military Bases', 'Costs for Military Bases', 'right'); ?>
                </strong>
                <strong class="budget_info_value text-red">
                    -$<span id="budget_bases"></span> Million
                </strong><br>
            </span>
            <span class="budget_info_item_parent">
                <strong class="budget_info_item_label">
                    University Education:
                    <?php echo generate_popover('University Education', 'Costs for Universities', 'right'); ?>
                </strong>
                <strong class="budget_info_value text-red">
                    -$<span id="budget_education"></span> Million
                </strong><br>
            </span>
            <span class="budget_info_item_parent">
                <strong class="budget_info_item_label">
                    Subsidized Healthcare:
                    <?php echo generate_popover('University Education', 'Costs for Healthcare Industry', 'right'); ?>
                </strong>
                <strong class="budget_info_value text-red">
                    -$<span id="budget_healthcare"></span> Million
                </strong><br>
            </span>
            <span class="budget_info_item_parent" style="display: none">
                <strong class="budget_info_item_label">
                    Socialism:
                    <?php echo generate_popover('Soclaism', 'Socialism consumes all remaining income. But the upside is Socialism allows you to enlist using support instead of cash', 'right'); ?>
                </strong>
                <strong class="budget_info_value text-red">
                    -$<span id="budget_socialism"></span> Million
                </strong><br>
            </span>
            <span class="budget_info_item_parent">
                <strong class="budget_info_item_label">
                    Projected Hourly Earnings:
                    <?php echo generate_popover('Hourly Earnings', 'Income remaining after accounting for all fixed costs. This number is an estimate based on current factors.', 'right'); ?>
                </strong>
                <strong class="budget_info_value text-success">
                    ~ $<span id="budget_earnings"></span> Million
                </strong><br>
            </span>
        </div>
        <div class="col-md-4 government_card">
            <!-- <h3 class="text-center text-primary">Supplies</h3> -->
            <div class="government_supplies_parent row">
            <?php $current_category_id = 0; ?>
            <?php $category_counter = 0; ?>
            <?php foreach ($this->supplies as $key => $supply) { ?>
                <?php
                if ($category_counter % 2 == 0) { ?>
                    <div class="col-xs-12"></div>
                <?php }
                if ($current_category_id !== $supply['category_id']) {
                    $category_counter++;
                    $current_category_id = $supply['category_id'];
                    ?>
                    <div class="col-md-6">
                        <h3 class="text-purple"><?php echo $this->supplies_category_labels[$supply['category_id']]; ?></h3>
                    <?php 
                } ?>

                <div class="government_supply parent">
                    <label class="text-primary" title="<?php echo $supply['meta']; ?>">
                        <?php echo $supply['label']; ?>
                        <?php if ($supply['can_sell']) { ?>
                        <span class="text-success">
                            ($<?php echo mt_rand(1, 6); ?>M)
                        </span>
                        <?php } ?>
                    </label>
                    <span class="pull-right">
                        <?php if ($supply['can_sell']) { ?>
                        <a href="" class="sell_resource">
                            (Sell)
                        </a>
                        <?php } ?>
                        <span id="government_supply_<?php echo $supply['slug']; ?>"></span>
                        <?php echo $supply['suffix']; ?>
                    </span>
                    <br>
                </div>

                <?php if (!isset($this->supplies[$key + 1]) || $current_category_id !== $this->supplies[$key + 1]['category_id']) {
                    ?>
                    </div>
                    <?php 
                    }
                ?>
            <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php } ?>