<!DOCTYPE html>
<html>
  <head>
    <title>Dai Cha Fan v1</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- styles -->
    <link href="css/styles.css?v=1.0" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="login-bg">
    <div class="header">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <!-- Logo -->
                  <div class="logo">
                     <h1><a href="index.html">Dai Cha Fan Admin</a></h1>
                  </div>
               </div>
            </div>
         </div>
    </div>

    <div class="page-content container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-wrapper">
                    <div class="box">
                        <div class="content-wrap">
                            <form id="admin_login">
                              <h6>Sign In</h6>
                              <input class="form-control" id="adminname" type="text" name="name" placeholder="username">
                              <input class="form-control" id="adminpass" type="password" name="password" placeholder="Password">
                              <div class="action">
                                  <a class="btn btn-primary signup" id="login">Login</a>
                              </div>   
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    
    <script src="js/custom.js"></script>
    <script type="text/javascript">
        $( "#login" ).click(function() {

         var data = {
                name: $("#adminname").val(),
                password:$("#adminpass").val() 
            };

         $.ajax({
            type: "POST",
            url: "api/v1.0/login",
            data: data,
            // contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (msg) {
               //do something
               console.log(msg);
               window.location.href = "/dashbroad";
            },
            error: function (errormessage) {

                console.log(errormessage.responseText);
                //do something else

            }
        });
        });

    </script>
  </body>
</html>