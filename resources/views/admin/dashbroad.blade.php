<!DOCTYPE html>
<html>
  <head>
    <title>Dai Cha Fan Admin v1</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- jQuery UI -->
    <link href="https://code.jquery.com/ui/1.10.3/themes/redmond/jquery-ui.css" rel="stylesheet" media="screen">

    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- styles -->
    <link href="css/styles.css" rel="stylesheet">

    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
    <link href="vendors/form-helpers/css/bootstrap-formhelpers.min.css" rel="stylesheet">
    <link href="vendors/select/bootstrap-select.min.css" rel="stylesheet">
    <link href="vendors/tags/css/bootstrap-tags.css" rel="stylesheet">

    <link href="css/forms.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="header">
       <div class="container">
          <div class="row">
             <div class="col-md-5">
                <!-- Logo -->
                <div class="logo">
                   <h1><a href=""></a></h1>
                </div>
             </div>
             <div class="col-md-5">
                <div class="row">
                  <div class="col-lg-12">

                  </div>
                </div>
             </div>
             <div class="col-md-2">
                <div class="navbar navbar-inverse" role="banner">
                    <nav class="collapse navbar-collapse bs-navbar-collapse navbar-right" role="navigation">
                      <ul class="nav navbar-nav">
                        <li class="dropdown">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown">My Account <b class="caret"></b></a>
                          <ul class="dropdown-menu animated fadeInUp">
                            <li><a href="login">Logout</a></li>
                          </ul>
                        </li>
                      </ul>
                    </nav>
                </div>
             </div>
          </div>
       </div>
  </div>

    <div class="page-content">
      <div class="row">
      <div class="col-md-2">
        <div class="sidebar content-box" style="display: block;">
                <ul class="nav">
                    <!-- Main menu -->
                    <li><a href="table"><i class="glyphicon glyphicon-list"></i> Tables</a></li>
                    <li class="current"><a href="forms.html"><i class="glyphicon glyphicon-tasks"></i> Forms</a></li>
                </ul>
             </div>
      </div>
      <div class="col-md-10">

          <div class="row">

            <div class="col-md-6">
              <div class="content-box-large">
                <div class="panel-heading">
                      <div class="panel-title">Advertisement Form</div>
                  </div>
                <div class="panel-body">
                  <form action="">
                  <fieldset>
                    <div class="form-group">
                      <label>Title</label>
                      <input class="form-control" id="title" name="title" placeholder="Text field" type="text">
                    </div>
                    <div class="form-group">
                      <label>Description</label>
                      <textarea class="form-control" id="desc" name="desc" placeholder="Textarea" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                      <label for="h-input">Thumbnail</label>
                      <div class="input-group">
                       
                        <input type="file" class="form-control btn btn-default"  name="filefield" id="exampleInputFile1">
                        
                      </div>
                      <p class="help-block">
                        Please resize before upload photo as server still haven't optimize yet.
                      </p>
                    </div>


                    <div class="form-group">
                        <h4>Expired Date</h4>
                        <p>
                            <div class="bfh-datepicker" data-name="dateFrom" data-format="y-m-d" data-date="today"></div>
                        </p>
                    </div>

                  </fieldset>
                  <div>
                    <div class="btn btn-primary" id="submit">
                      <i class="fa fa-save"></i>
                      Submit
                    </div>
                  </div>
                </form>
                </div>
              </div>
            </div>
          </div>

          


        <!--  Page content -->
      </div>
    </div>
    </div>

    <footer>
         <div class="container">
         
            <div class="copy text-center">
               Copyright 2015 <a href='#'>Dai Cha Fan</a>
            </div>
            
         </div>
      </footer>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery.js"></script>
    <!-- jQuery UI -->
    <script src="https://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap/js/bootstrap.min.js"></script>

    <script src="vendors/form-helpers/js/bootstrap-formhelpers.min.js"></script>

    <script src="vendors/select/bootstrap-select.min.js"></script>

    <script src="vendors/tags/js/bootstrap-tags.min.js"></script>

    <script src="vendors/mask/jquery.maskedinput.min.js"></script>

    <script src="vendors/moment/moment.min.js"></script>

    <script src="vendors/wizard/jquery.bootstrap.wizard.min.js"></script>

     <!-- bootstrap-datetimepicker -->
     <link href="vendors/bootstrap-datetimepicker/datetimepicker.css" rel="stylesheet">
     <script src="vendors/bootstrap-datetimepicker/bootstrap-datetimepicker.js"></script> 


    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
  <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>

    <script src="js/custom.js"></script>
    <script src="js/forms.js"></script>
    <script type="text/javascript">
        $( "#submit" ).click(function() {
                  var file = document.getElementById("exampleInputFile1");
     
      /* Create a FormData instance */
      var formData = new FormData();
      /* Add the file */ 
      $("input,textarea").each(function(){
        formData.append($(this).attr("name") ,  $(this).val());
      });

      formData.append("filefield", file.files[0]);

      console.log(formData);
         $.ajax({
            type: "POST",
            url: "api/v1.0/ads/post",
            data: formData,
            // contentType: "application/json; charset=utf-8",
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            success: function (msg) {
               //do something
               console.log(msg);
               //window.location.href = "/dashbroad";
            },
            error: function (errormessage) {
                console.log(errormessage.statusText);
                console.log(errormessage.responseText);
                //do something else

            }
        });
    });

    </script>
  </body>
</html>