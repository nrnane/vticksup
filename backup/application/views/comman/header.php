<!DOCTYPE html>
<?php $version = 3; ?>
<html lang="en">
  <head>
    <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width,initial-scale=1">
          <title>Vaazu Tickets System</title>
          <!-- Bootstrap -->
          <link href="<?=base_url()?>assets2/css/bootstrap.min.css?v=<?php echo $version?>" rel="stylesheet">
            <link href="<?=base_url()?>assets2/css/custom.css?v=<?php echo $version?>" rel="stylesheet">
            <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>

              <!--<link rel='stylesheet prefetch' href='http://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.min.css'>-->
                <style>
                  a {
                  color: orange;
                  }
                </style>
                <script src="<?=base_url()?>assets2/js/jquery-1.11.3.min.js" ></script>
                <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
                <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
                <!--[if lt IE 9]><link href= "css/bootstrap-theme.css"rel= "stylesheet" >

<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
              </head>

  <body ng-cloak="">
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="row" >
          <div class="col-md-4">
            <a href="<?=site_url()?>" class="pull-left">
            <img src="<?=base_url('assets2/img/vaazu_logo.png')?>" height="50" alt="" />
            </a>
              <?php if($this->session->userdata('loginuser')!=NULL){ ?>
            <ul  class="nav navbar-nav " ng-show="login">
                <li><a href="<?=site_url('home/profile/'.UID)?>" class="capitalize"><?=USERNAME?></a></li>
            </ul>
<?php } ?>
          </div>


                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>

                    </div>

                    <?php if ($this->session->userdata('loginuser') != NULL) { ?>
                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">


                            <ul class="nav navbar-nav navbar-right">

                                <li class="dev"><a href="#"><?= USERTYPE ?></a></li>



                                <?php if (USERTYPE == 2) { ?>
                                    <li><a href="<?=site_url('home/tickets') ?>"><i class="glyphicon glyphicon-user"></i> Support Team</a></li>
                                <?php } elseif (USERTYPE == 1) { ?>
                                    <li><a href="<?=site_url('home/tickets') ?>"><i class="glyphicon glyphicon-user"></i> Manager</a></li>
                                <?php } elseif (USERTYPE == 0) { ?>
                                   <!-- <li>
                                        <a href="<?=site_url('admin') ?>">Admin Manage</a>
                                    </li>-->
                                    <?php
                                      if($this->session->userdata('superadmin')==1){
                                    ?>
                                    <li class="dropdown" ><a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-user"></i> Admin <b class="caret"></b> </a>
                                        <ul class="dropdown-menu">
                                            <li><a href="<?=site_url('admin/all_users')?>">View All Users</a></li>
                                            <li><a href="<?=site_url('admin/all_projects')?>">View All Projects</a></li>
                                            <li><a href="<?=site_url('admin/edit_tickets')?>">Edit Tickets</a></li>

                                          </ul>
                                    </li>
                                    <?php } ?>
                                <?php } ?>
                                <li><a href="<?=site_url('home/tickets') ?>"><i class="glyphicon glyphicon-tags"></i> Tickets</a></li>
                                <li><a href="<?=site_url('home/questions') ?>"><i class="glyphicon glyphicon-question-sign"></i> Questions</a></li>
                                <?php if(isset($_SESSION['roadmap']) && $_SESSION['roadmap']!=0){ ?>
                                <li><a href="<?=site_url('home/roadmap') ?>">
                               <i class="glyphicon glyphicon-road"></i>
                               Road Map</a></li>
                               <?php } ?>

                                <?php if(USERTYPE==0 || USERTYPE==1){ ?>
                                <li class="dropdown" >
                                    <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-plus"></i> Create <b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?=site_url('home/create_ticket/0') ?>">Create a Ticket</a></li>

                                        <li ><a href="<?=site_url('home/create_question/0') ?>">Ask a Question</a></li>
                                    </ul>
                                </li>
                                <?php } ?>

                                <li><a href="<?=site_url('home/logout') ?>"  ng-hide="!usertype"> <i class="glyphicon glyphicon-log-out"></i> Logout</a></li>
                            </ul>
                        </div>
                        <!-- /.navbar-collapse -->
                    <?php } ?>
                </div><!-- /.container-fluid -->

        </div>
      </div>
    </div>
      <div class="clearfix"></div>
      <div class="container page_container" style="margin-top:20px;">
