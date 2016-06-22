<div class="col-md-6">
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
                echo form_open(base_url($url), $form_attr);
                foreach($form as $key => $input) {
            ?>
            <div class="row">
                <div class="col-md-2 col-md-offset-1">
                    <?php echo form_label($input['label']); ?>
                </div>
                <div class="col-md-8">
                        <?php
                            switch($input['form_type']) {
                                case "text":
                                    echo '<div class="input-group col-xs-12">';
                                    echo form_input($input['form'], isset($portfolio->$key) ? $portfolio->$key : null );
                                    break;
                                case "textarea":
                                    echo '<div class="input-group col-xs-12">';
                                    echo form_textarea($input['form'], isset($portfolio->$key) ? $portfolio->$key: null );
                                    break;
                                case "password":
                                    echo '<div class="input-group col-xs-12">';
                                    echo form_password($input['form']);
                                    break;
                                case "select":
                                    echo '<div class="input-group col-xs-12">';
                                    echo form_dropdown($input['form'], $input['values'], isset($portfolio->$key) ? $portfolio->$key : null );
                                    break;
                                case "date":
                                    echo '<div class="input-append date form_datetime" data-date="2013-02-21T15:25:00Z">';
                                    echo form_input($input['form'], isset($profile->$key) ? $service->$key : null );
                                    echo '<span class="add-on"><i class="icon-remove"></i></span><span class="add-on"><i class="icon-calendar"></i></span>';
                                    echo '<script type="text/javascript"> $(".form_datetime").datetimepicker({format: "dd MM yyyy - hh:ii", autoclose: true, todayBtn: true, startDate: "2013-02-14 10:00", minuteStep: 10 }); </script>';
                                    break;
                            }
                        ?>
                    </div>
                </div>
            </div>
            <?php } ?>
            <div class="row">
                <div class="col-md-2 col-md-offset-1">
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
        </div>
        <div class="box-footer clearfix no-border"></div>
    </div>
</div>
