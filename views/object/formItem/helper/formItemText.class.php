<?php
include_once (dirname(__FILE__) . '/../input/text.class.php');
include_once (dirname(__FILE__) . '/../input/date.class.php');
include_once (dirname(__FILE__) . '/../input/textarea.class.php');
include_once (dirname(__FILE__) . '/../input/numeric.class.php');
include_once (dirname(__FILE__) . '/../input/autoincrement.class.php');

class FormItemText extends FormItem {

    public $textClass;
    public $dateClass;
    public $textareaClass;
    public $numericClass;
    public $autoincrementClass;
    public $class;

    public function widget($property, $item_id) {
        $this->textClass = new TextClass();
        $this->dateClass = new DateClass();
        $this->textareaClass = new TextAreaClass();
        $this->numericClass = new NumericClass();
        $this->autoincrementClass = new AutoIncrementClass();
        $values = $this->getValuePropertyHelper($item_id, $property_id);
        $isMultiple = ($property['metas']['socialdb_property_data_cardinality'] == 'n') ? true : false;
        $filledValues = ($values) ? count($values) : 1;
        ?>
        <div id="meta-item-<?php echo $property['id']; ?>" class="form-group" >
            <h2>
                <?php echo $property['name']; ?>
                <?php
                if (has_action('modificate_label_insert_item_properties')):
                    do_action('modificate_label_insert_item_properties', $property);
                endif;
                ?>
            </h2>
            <div>
                <?php for ($index = 0; $index < $filledValues; $index++): ?>
                    <div id="container-field" 
                         class="row" style="padding-bottom: 10px;margin-bottom: 10px;">
                        <div class="col-md-11">
                            <?php if ($property['type'] == 'text'): ?>
                                <?php $this->textClass->generate($property,0, $item_id, $index) ?>
                            <?php elseif ($property['type'] == 'date'): ?>
                                <?php $this->dateClass->generate($property,0, $item_id, $index) ?>
                            <?php elseif ($property['type'] == 'textarea'): ?>
                                <?php $this->textareaClass->generate($property,0, $item_id, $index) ?>
                            <?php elseif ($property['type'] == 'numeric' || $property['type'] == 'number'): ?>
                                <?php $this->numericClass->generate($property,0, $item_id, $index) ?>
                            <?php elseif ($property['type'] == 'autoincrement'): ?>
                                <?php $this->autoincrementClass->generate($property,0, $item_id, $index) ?>
                            <?php endif; ?>
                        </div>
                        <?php if ($index > 0): ?>
                            <div class="col-md-1">
                                <a style="cursor: pointer;" onclick="remove_container(<?php echo $property['id'] ?>,<?php echo $$index ?>)" class="pull-right">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </a>
                            </div> 
                        <?php endif; ?>
                    </div>    
                <?php endfor; ?>
                    <div id="appendTextContainer"></div>
                <?php if ($isMultiple): ?>
                    <button type="button" 
                            class="btn btn-primary btn-lg btn-xs btn-block js-append-property-<?php echo $property['id'] ?>">
                        <span class="glyphicon glyphicon-plus"></span><?php _e('Add field', 'tainacan') ?>
                    </button>
                <?php endif; ?>
            </div>
        </div>
        <?php
        $this->initScriptsTextContainer($property, $item_id, $index);
    }

    public function appendContainerText($property,$item_id,$index) {
        $this->textClass = new TextClass();
        $this->dateClass = new DateClass();
        $this->textareaClass = new TextAreaClass();
        $this->numericClass = new NumericClass();
        $this->autoincrementClass = new AutoIncrementClass();
        $values = $this->getValuePropertyHelper($item_id, $property_id)
        ?>
        <div id="container-field" 
             class="row" style="padding-bottom: 10px;margin-bottom: 10px;">
            <div class="col-md-11">
                <?php if ($property['type'] == 'text'): ?>
                    <?php $this->class = $this->textClass ?>
                    <?php $this->textClass->generate($property,0, $item_id, $index) ?>
                <?php elseif ($property['type'] == 'date'): ?>
                    <?php $this->class = $this->dateClass ?>
                    <?php $this->dateClass->generate($property,0, $item_id, $index) ?>
                <?php elseif ($property['type'] == 'textarea'): ?>
                    <?php $this->class = $this->textareaClass ?>
                    <?php $this->textareaClass->generate($property,0, $item_id, $index) ?>
                <?php elseif ($property['type'] == 'numeric' || $property['type'] == 'number'): ?>
                    <?php $this->class = $this->numericClass ?>
                    <?php $this->numericClass->generate($property,0, $item_id, $index) ?>
                <?php elseif ($property['type'] == 'autoincrement'): ?>
                    <?php $this->class = $this->autoincrementClass ?>
                    <?php $this->autoincrementClass->generate($property,0, $item_id, $index) ?>
                <?php endif; ?>
            </div>
            <div class="col-md-1">
                <a style="cursor: pointer;" onclick="remove_container(<?php echo $property['id'] ?>,<?php echo $index ?>)" class="pull-right">
                    <span class="glyphicon glyphicon-remove"></span>
                </a>
            </div> 
        </div>    
        <?php
    }

    /**
     * 
     * @param type $property
     * @param type $item_id
     * @param type $index
     */
    public function initScriptsTextContainer($property, $item_id, $index) {
        ?>
        <script>
            var index = <?php echo $index; ?>;

            $('.js-append-property-<?php echo $property['id'] ?>').click(function(){
                console.log(<?php echo $item_id ?>,<?php echo $index ?>);
                $.ajax({
                    url: $('#src').val() + '/controllers/object/form_item_controller.php',
                    type: 'POST',
                    data: {
                        collection_id: $("#collection_id").val(),
                        operation: 'appendContainerText',
                        item_id:'<?php echo $item_id ?>',
                        property_details: '<?php echo serialize($property) ?>',
                        index: <?php echo $index ?>
                    }
                }).done(function (result) {
                    $('#meta-item-<?php echo $property['id']; ?> #appendTextContainer').append(result);
                });
            });
        </script> 
        <?php
    }

}
