<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>webllywood-backend : Login.</title>
    <?php echo $header_scripts;?>
  </head>
  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
            <form novalidate action="<?php echo base_url('login-process') ?>" method="POST">
              <h1>Login Form</h1>
              <?php if($this->session->userdata('login_error')) { ?>
              <div class="x_content bs-example-popovers">
                <div class="alert alert-danger alert-dismissible fade in" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                  </button>
                  <strong>Error!</strong> 
                  <?php echo $this->session->userdata('login_error');?>
                  <?php $this->session->unset_userdata('login_error');?>
                </div>
              </div>
              <?PHP } ?>
              
              <div>
                <input type="text" class="form-control" name="admin_userName" placeholder="admin_userName" required="" />
              </div>
              <div>
                <input type="password" class="form-control" name="admin_password" placeholder="admin_password" required="" />
              </div>
              <div>
                <button class="btn btn-default submit">Log in</button>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <div class="clearfix"></div>
                <br />
                <div>
                  <h1><i class="fa fa-paw"></i> webllywood.com</h1>
                  <p>©<?php echo date('Y'); ?> All Rights Reserved. Privacy and Terms.</p>
                </div>
              </div>
            </form>
          </section>
        </div>
      </div>
    </div>
  </body>
</html>