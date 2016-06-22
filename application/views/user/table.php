<div class="col-sm-7">
    <div class="box box-primary">
        <div class="box-header ui-sortable-handle">
            <h3>Users</h3>
            <?php
                echo form_open(base_url('user/search/'));
                $search = array(
                          'name'        => 'search',
                          'id'          => 'search-user',
                          'maxlength'   => '100',
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
        </div>
        <div class="box-body">
            <?php if($this->session->flashdata('message')) { ?>
            <div class="alert <?php echo $this->session->flashdata('message')['type'] ? " alert-success" : "alert-danger"; ?>">
                <?php echo $this->session->flashdata('message')['msg']?>
            </div>
            <?php
                }
                if(count($table) > 0) {
            ?>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($table as $row) { ?>
                    <tr<?php echo (!$row->status) ? ' class="inactive"' : ''; ?>>
                        <td><?php echo $row->id; ?></td>
                        <td><?php echo $row->name; ?></td>
                        <td><?php echo $row->email; ?></td>
                        <td>
                            <a href="<?php echo base_url('user/profile/'.$row->id); ?>"><i class="fa fa-pencil-square-o edit-btn" aria-hidden="true"></i></a>
                            <?php if($row->status) { ?>
                            <a href="<?php echo base_url('user/delete/'.$row->id); ?>"><i class="fa fa-times delete-btn" aria-hidden="true"></i></a>
                            <?php } else { ?>
                            <a href="<?php echo base_url('user/activate/'.$row->id); ?>"><i class="fa fa-check activate-btn" aria-hidden="true"></i></a>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php  } else { ?>
                <div class="alert alert-danger">
                    Search has no Result.
                </div>
        <?php } ?>
        </div>
        <div class="box-footer clearfix no-border">
                <?php echo isset($pagination) ? $pagination : ''; ?>
        </div>
    </div>
</div>

<?php //include('form.php'); ?>
