<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>webllywood-backend : Dashboard</title>
<?php echo $header_scripts;?>
</head>
<?php //print_r($this->data);die; ?>
<!-- onLoad="setheight('fade1');openFancyDiv1('login-popup','fade1');"-->
<body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">            
            <!-- sidebar menu -->
			        <?php echo $sidebar;?>
            <!-- /sidebar menu -->           
          </div>
        </div>
        <!-- top navigation -->
        <?php echo $topmenu;?>
        <!-- /top navigation -->
       
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="row top_tiles">
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
              <div class="tile-stats">
                <div class="icon"><img src="<?PHP echo DEFAULT_ASSETS_URL;?>images/active-user.png"/></div>
                <div class="count"><?php echo $this->data['activeUsers'];?></div>
                <h3>Active Users</h3>
                <p><a href='<?php echo base_url("user/active");?>'>Click here to redirect on active user list.</a></p>
              </div>
            </div>
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
              <div class="tile-stats">
                <div class="icon"><img src="<?PHP echo DEFAULT_ASSETS_URL;?>images/ban-user.png"/></div>
                <div class="count"><?php echo $this->data['deActiveUsers']; ?></div>
                <h3>Deactive Users</h3>
                <p><a href='<?php echo base_url("user/block");?>'>Click here to redirect on deactive user list.</a></p>
              </div>
            </div>
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
              <div class="tile-stats">
                <div class="icon"><img src="<?PHP echo DEFAULT_ASSETS_URL;?>images/user-online.png"/></div>
                <div class="count"><?php echo $this->data['login_user'];?></div>
                <h3>Login User</h3>
                <p>Curently logged in users in site/app.</p>
              </div>
            </div>
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
              <div class="tile-stats">
                <div class="icon"><img src="<?PHP echo DEFAULT_ASSETS_URL;?>images/globe.png"/></div>
                <div class="count"><?php echo $this->data['countries'];?></div>
                <h3>Total Countries</h3>
                <p>All country in site.</p>
              </div>
            </div>

            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
              <div class="tile-stats">
                <div class="icon"><img src="<?PHP echo DEFAULT_ASSETS_URL;?>images/active-article.png"/></div>
                <div class="count"><?php echo $this->data['activeProjects'];?></div>
                <h3>Active Projects</h3>
                <p><a href='<?php echo base_url("project/index");?>'>Click here to redirect on Active Projects.</a></p>
              </div>
            </div>
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
              <div class="tile-stats">
                <div class="icon"><img src="<?PHP echo DEFAULT_ASSETS_URL;?>images/block-article.png"/></div>
                <div class="count"><?php echo $this->data['deActiveProjects'];?></div>
                <h3>Blocked Projects</h3>
                <p><a href='<?php echo base_url("project/block-projects");?>'>Click here to redirect on Blocked Projects.</a></p>
              </div>
            </div>
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
              <div class="tile-stats">
                <div class="icon"><img src="<?PHP echo DEFAULT_ASSETS_URL;?>images/pending-article.png"/></div>
                <div class="count"><?php echo $this->data['pendingProjects'];?></div>
                <h3>Pending Projects</h3>
                <p><a href='<?php echo base_url("project/awaiting-projects");?>'>Click here to redirect on Pending Projects.</a></p>
              </div>
            </div>
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
              <div class="tile-stats">
                <div class="icon"><img src="<?PHP echo DEFAULT_ASSETS_URL;?>images/adv.png"/></div>
                <div class="count"><?php echo $this->data['count_adv'];?></div>
                <h3>Total Advs</h3>
                <p>Total advertisement of admin and user.</p>
              </div>
            </div>
          </div>
       
          <!-- /page content -->
          <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Newely Added Active Projects</h2>
                  <ul class="nav set-min-w0 panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <ul class="to_do">
                      <?php if(!empty($this->data['projects'])) {
                        foreach($this->data['projects'] as $k=>$project){?>
                          <li>
                          <p><a href="<?php echo base_url('project/project-view/id/'.$project['id']); ?>"><?php echo $project['title']; ?></a></p>
                          </li>
                      <?php
                        if($k == 5){
                          echo '</ul></div><div class="col-md-6 col-sm-6 col-xs-12"><ul class="to_do">';
                        } else if($k > 10) break; }
                        }?>
                    </ul>
                  </div>
                </div>
              </div>
            </div> 
            <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Newely Added Active Users</h2>
                  <ul class="nav set-min-w0 panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <ul class="to_do">
                      <?php if(!empty($this->data['users'])) {
                        foreach($this->data['users'] as $k=>$user){?>
                        <li>
                          <p><a href="<?php echo base_url('user-edit/id/'.$user['id']); ?>"><?php echo $user['firstName'].' '.$user['lastName']; ?></a></p>
                        </li>
                        <?php if($k == 5){
                        echo '</ul></div><div class="col-md-6 col-sm-6 col-xs-12"><ul class="to_do">';
                        } else if($k > 10) break; }
                      }?>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-8 col-sm-12 col-xs-12">
              <div class="x_panel fixed_height_320">
                <div class="x_title">
                  <h2>Users Count</h2>
                  <ul class="nav panel_toolbox set-min-w0">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class="x_content">
                    <h4>Total Users <small>(<?PHP echo $total_user_count;?>)</small></h4>
                    </br>
                    <div class="widget_summary">
                      <div class="w_left w_25">
                        <span>Facebook</span>
                      </div>
                      <div class="w_center w_55">
                        <div class="progress">
                        <?php if($total_user_count != 0){ ?>
                          <div class="progress-bar bg-green" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?PHP echo ($fb_user_count*100/$total_user_count);?>%;">
                            <span class="sr-only">60% Complete</span>
                          </div>
                       <?php  } ?>
                          
                        </div>
                      </div>
                      <div class="w_right w_20">
                        <span><?PHP echo $fb_user_count;?></span>
                      </div>
                      <div class="clearfix"></div>
                    </div>

                    <div class="widget_summary">
                      <div class="w_left w_25">
                        <span>Linkedin</span>
                      </div>
                      <div class="w_center w_55">
                        <div class="progress">
                        <?php if($total_user_count != 0){ ?>
                          <div class="progress-bar bg-green" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?PHP echo ($li_user_count*100/$total_user_count);?>%;">
                            <span class="sr-only">60% Complete</span>
                          </div>
                        <?php } ?>
                        </div>
                      </div>
                      <div class="w_right w_20">
                        <span><?PHP echo $li_user_count;?></span>
                      </div>
                      <div class="clearfix"></div>
                    </div>
                    <div class="widget_summary">
                      <div class="w_left w_25">
                        <span>Google+</span>
                      </div>
                      <div class="w_center w_55">
                        <div class="progress">
                        <?php if($total_user_count != 0){ ?>
                          <div class="progress-bar bg-green" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?PHP echo ($gp_user_count*100/$total_user_count);?>%;">
                            <span class="sr-only">60% Complete</span>
                          </div>
                        <?php } ?>
                        </div>
                      </div>
                      <div class="w_right w_20">
                        <span><?PHP echo $gp_user_count;?></span>
                      </div>
                      <div class="clearfix"></div>
                    </div>
                    <div class="widget_summary">
                      <div class="w_left w_25">
                        <span>Web</span>
                      </div>
                      <div class="w_center w_55">
                        <div class="progress">
                        <?php if($total_user_count != 0){ ?>
                          <div class="progress-bar bg-green" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?PHP echo ($web_user_count*100/$total_user_count);?>%;">
                            <span class="sr-only">60% Complete</span>
                          </div>
                        <?php }?>
                        </div>
                      </div>
                      <div class="w_right w_20">
                        <span><?PHP echo $web_user_count;?></span>
                      </div>
                      <div class="clearfix"></div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class="x_content">
                      <h4>Man (<?PHP echo $male_user;?>) / Woman (<?PHP echo $female_user;?>)</h4>
                      <div id="manWomanChart" style="height: 210px; width: 100%;"></div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-4 col-sm-4 col-xs-12">
              <div class="x_panel fixed_height_320">
                <div class="x_title">
                  <h2>Man/Woman counts per skill</h2>
                  <ul class="nav panel_toolbox set-min-w0">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content" style="margin-top:-10px;">
                  <?PHP if(!empty($skill_data)){?>
                  <table class="tile_info">
                    <tr>
                      <th width="60%">Skill</th>
                      <th width="20%">Man</th>
                      <th width="20%">Woman</th>
                    </tr>
                    <?PHP foreach($skill_data as $data){?>
                    <tr>
                      <td><?PHP echo $data[0];?></td>
                      <td><?PHP echo $data[1];?></td>
                      <td><?PHP echo $data[2];?></td>
                    </tr>
                    <?PHP }?>
                  </table>
                  <?PHP } else {?>
                    <div class="x_content text-center no-data-color">
                      <span class="glyphicon glyphicon-warning-sign worning-class" aria-hidden="true"></span>
                      </br><span class="worning-class-small">No data found !</span>
                    </div>
                  <?PHP }?>
                </div>
              </div>
            </div>
          </div>

          <?PHP if(!empty($user_article_category_data)){?>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div id="catActiveDeactiveChart" style="height: 400px;width: 100%;"></div>
              </div>
              <div class='overflow-div'></div>
            </div>
          </div>          
          <?PHP }?>
        </div>
        <footer>
			  <?php echo $footer;?>         
        </footer>
        <!-- /footer content -->
      </div>
    </div>
    <?php echo $footer_scripts;?>
    <script>
      window.onload = function () {
        var chartmanWomanChart = new CanvasJS.Chart("manWomanChart", {
          animationEnabled: true,
          data: [{
            type: "pie",
            startAngle: 240,
            yValueFormatString: "##0.00\"%\"",
            indexLabel: "{label} {y}",
            dataPoints: [
              {y: <?PHP echo ($male_user*100 / $total_user_count);?>, label: "Man"},
              {y: <?PHP echo ($female_user*100 / $total_user_count);?>, label: "Woman"},
            ]
          }]
        });
        chartmanWomanChart.render();
        chartmanWomanChart = {};
      // }
      
      <?PHP if(!empty($user_article_category_data)){?>
        var chartcatActiveDeactiveChart = new CanvasJS.Chart("catActiveDeactiveChart", {
          exportEnabled: true,
          animationEnabled: true,
          title:{
            text: "Data chart based on Categories"
          },
          subtitles: [{
            text: "Click Legend to Hide or Unhide Data Series"
          }], 
          axisX: {
            title: "States"
          },
          toolTip: {
            shared: true
          },
          legend: {
            cursor: "pointer",
            itemclick: toggleDataSeries
          },
          data: [{
            type: "column",
            name: "Active",
            showInLegend: true,      
            yValueFormatString: "#,##0",
            dataPoints: [
              <?PHP foreach($user_article_category_data as $k=>$cat){ ?>
                { label: "<?PHP echo $k;?>",  y: <?PHP echo $cat[0];?> },
              <?PHP }?>
            ]
          },
          {
            type: "column",
            name: "Deactive",
            axisYType: "secondary",
            showInLegend: true,
            yValueFormatString: "#,##0",
            dataPoints: [
              <?PHP foreach($user_article_category_data as $k=>$cat){ ?>
                { label: "<?PHP echo $k;?>",  y: <?PHP echo $cat[1];?> },
              <?PHP }?>
            ]
          },
          {
            type: "column",
            name: "Pending",
            axisYType: "secondary",
            showInLegend: true,
            yValueFormatString: "#,##0",
            dataPoints: [
              <?PHP foreach($user_article_category_data as $k=>$cat){ ?>
                { label: "<?PHP echo $k;?>",  y: <?PHP echo $cat[2];?> },
              <?PHP }?>
            ]
          }]
        });
        chartcatActiveDeactiveChart.render();
        function toggleDataSeries(e) {
          if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
            e.dataSeries.visible = false;
          } else {
            e.dataSeries.visible = true;
          }
          e.chart.render();
        }
      
      <?PHP }?>
    }
    </script>
  </body>
</html>