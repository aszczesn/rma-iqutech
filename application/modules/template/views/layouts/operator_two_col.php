    <!-- Page Content -->
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <?php
                    $this->load->view('sidebar');
                ?>
            </div>
            <div class="col-md-9">
        <?php
        if(!isset($module)){
            $module = $this->uri->segment(1);
        }
        if(!isset($view_file)){
            $view_file = $this->uri->segment(2);
        }
        if(($module != "") && ($view_file !="")){
            $path = $module."/".$view_file;
            $this->load->view($path);
        }
        ?>
                </div>
        </div>
        </div>