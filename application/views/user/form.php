<div class="col-md-5">
    <div class="box box-primary">
        <div class="box-header ui-sortable-handle">
            <h3><?php echo $title; ?></h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div>
        <div class="box-body">
            <?php if($this->session->flashdata('message')) { ?>
            <div class="alert <?php echo $this->session->flashdata('message')['type'] ? " alert-success" : "alert-danger"; ?>">
                <?php echo $this->session->flashdata('message')['msg']?>
            </div>
            <?php }
                echo form_open(base_url($url), array("class" => "form-block"));
                foreach($form as $key => $input) {
            ?>
            <div class="form-group col-sm-12">
                <?php echo form_label($input['label']); ?>
                <div class="">
                    <?php
                        switch($input['form_type']) {
                            case "text": echo form_input($input['form'], isset($profile->$key) ? $profile->$key : null );
                                break;
                            case "password": echo form_password($input['form']);
                                break;
                            case "select": echo form_dropdown($input['form'], $input['values'], isset($profile->$key) ? $profile->$key : null );
                                break;
                        }
                    ?>
                </div>
            </div>
            <?php } ?>
            <div class="col-md-3">
                <?php
                    $data = array(
                          'name'    => 'submit',
                          'class'   => 'btn btn-default',
                          'value'   => 'Submit',
                        );

                    echo form_submit($data);
                    echo form_close();
                ?>
            </div>
        </div>
        <div class="box-footer clearfix no-border"></div>
    </div>
</div>
