<div class="col-sm-12">
    <div class="box box-primary">
        <div class="box-header ui-sortable-handle">
            <h3>Porfolio</h3>
            <?php
                echo form_open(base_url('portfolio/search/'));
                $search = array(
                          'name'        => 'search',
                          'id'          => 'search-user',
                          'class'		=> 'form-control',
                          'placeholder'	=> 'Search',
                          'value'       => (isset($search)) ? $search : null,
                        );
            ?>
            <div class="input-group">
                <?php echo form_input($search); ?>
                <span class="input-group-addon" id="basic-addon1"><i class="fa fa-search" aria-hidden="true"></i></span>
            </div>
            <?php echo form_close()?>

            <?php echo isset($pagination) ? $pagination : ''; ?>
        </div>
        <?php if($this->session->flashdata('message')) { ?>
        <div class="box-body">
            <div class="alert <?php echo $this->session->flashdata('message')['type'] ? " alert-success" : "alert-danger"; ?>">
                <?php echo $this->session->flashdata('message')['msg']?>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<?php foreach($table as $row){ ?>
    <div class="col-sm-4">
        <div class="box <?php echo ($row->status) ? "box-info" : "box-danger"; ?> service-box">
            <div class="box-header ui-sortable-handle">
                <h4><?php echo $row->port_address; ?></h4>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <p>by: <?php echo $row->name; ?></p>
                <?php
                    echo $row->port_desc;
                ?>
            </div>
            <div class="box-footer clearfix no-border">
                <?php
                    if(($row->user_id === $user->id) OR ($user->role_name === 'admin')) {
                ?>
                    <a href="<?php echo base_url('portfolio/edit/'.$row->port_id); ?>" class="edit-btn action-btn"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a>
                <?php if($row->status) { ?>
                    <a href="<?php echo base_url('portfolio/delete/'.$row->port_id); ?>" class="delete-btn action-btn"><i class="fa fa-times" aria-hidden="true"></i> Delete</a>
                <?php } else { ?>
                    <a href="<?php echo base_url('portfolio/activate/'.$row->port_id); ?>" class="activate-btn action-btn"><i class="fa fa-check" aria-hidden="true"></i> Activate</a>
                <?php }
                    }
                 ?>
            </div>
        </div>
    </div>
<?php } ?>
