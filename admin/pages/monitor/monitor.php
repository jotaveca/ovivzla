<?php

   $foto_usuario = 'tesla';
   $nb_usuario = 'Nikola Tesla';  
   $perfil_usuario = 'Inventor';
   $usuario_fecha = 'Miembro desde Septiembre 2016';

   include_once("../../../src/class.ControlWeb.php");
   $web = new ControlWeb();   
              

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Observatorio Vial Inteligente (OVI)</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../../dist/css/skins/_all-skins.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
   <a href="../../admin.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>OVI</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>OVI</b>Beta</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
        
          <!-- Botones de la barra de superior -->
          <?php //include_once("notificaciones.php"); ?>
          <!-- User Account: style can be found in dropdown.less -->
             <?php include_once("../../cuenta.php"); ?>

          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
<div class="user-panel">
        <div class="pull-left image">
          <img src="../../dist/img/<?php echo $foto_usuario ?>-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $nb_usuario ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
           <input type="text" name="q" class="form-control" placeholder="Búsqueda...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      
      <?php 
          $m_monitor = true;
          
          ?>

      <!-- menu lateral -->
    <?php include_once("../../menu.php"); ?>

    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Monitor vial
        <small>En vivo</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../admin.php"><i class="fa fa-dashboard"></i> Panel de control</a></li>
        <li class="active"><a >Monitor vial</a></li>        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
        
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Monitor vial</h3>
            <div class="btn-group">                  
                  
                   <select class="form-control" name="tx_preferencia" id="tx_preferencia" required>
                    <option value="77">Zona Vial</option>         
                    <?php 
                        $vias = $web->listarVias();
                        for ($i=0;$i<count($vias);$i++) {

                        if ($zonaVial == $vias[$i]['tx_siglas']) $selected = 'selected';
                        else $selected = '';

                        $vias[$i]['tx_siglas'] = substr($vias[$i]['tx_siglas'], 1);
                        echo "<option value='".$vias[$i]['tx_siglas']."' $selected > ".$vias[$i]['tx_nombre']." </option>";
                    } ?>      
          
               </select> 
                  
                </div>
            </div>

            <div id="cargando" class="box box-danger">
            <div class="box-header">
              <h3 class="box-title">Cargando...</h3>
            </div>
            <div class="box-body">
              <!--The body of the box -->
            </div>
            <!-- /.box-body -->
            <!-- Loading (remove the following to stop the loading)-->
            <div class="overlay">
              <i class="fa fa-refresh fa-spin"></i>
            </div>
            <!-- end loading -->
          </div>


            <!-- /.box-header -->
            <div id="listado" class="box-body">
            
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->


    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.3.6
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="http://almsaeedstudio.com">Almsaeed Studio</a>.</strong> All rights
    reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-user bg-yellow"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                <p>New phone +1(800)555-1234</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                <p>nora@example.com</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-file-code-o bg-green"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                <p>Execution time 5 seconds</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="label label-danger pull-right">70%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Update Resume
                <span class="label label-success pull-right">95%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Laravel Integration
                <span class="label label-warning pull-right">50%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Back End Framework
                <span class="label label-primary pull-right">68%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Allow mail redirect
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Other sets of options are available
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Expose author name in posts
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Allow the user to show his name in blog posts
            </p>
          </div>
          <!-- /.form-group -->

          <h3 class="control-sidebar-heading">Chat Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Show me as online
              <input type="checkbox" class="pull-right" checked>
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Turn off notifications
              <input type="checkbox" class="pull-right">
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Delete chat history
              <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
            </label>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="../../bootstrap/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="../../plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../../plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
<!-- page script -->
<script>
  
  $(document).ready(function(){

    $('#tx_preferencia').on('change', function (e) {
    //var optionSelected = $("option:selected", this);
    var valueSelected = this.value;
    //#tx_preferencia').val()
    console.log("Evento OnChange");
    console.log(valueSelected);
    if (valueSelected=="77"){
        
        crearTablaSuscriptores("PNM"); 
    }else{
        crearTablaSuscriptores(valueSelected); 
    }
    
    });
    
    var opcion = $('#tx_preferencia').val();
    console.log(opcion);
    if (opcion=="77"){
        
        crearTablaSuscriptores("PNM"); 
    }
    
    
    
    
    /*
       $('#form-suscriptor-editar').submit(function(){    
                
        
        $.ajax({
            type: 'POST',
            url: '../../../src/adm_suscriptor.php',
            data: $(this).serialize()
        })
        .done(function(data){
             
            // show the response
            //$('#respuesta').html(data);
            //alert( "Enviado" );
            console.log(data);
            var datos = JSON.parse(data);
            //alert(datos.valido);
            if (datos.valido == 1){              
              $('#myModalEditar').modal('hide');             
              $('#ModalGuardar').modal('show');
            }             
        })
        .fail(function() {
         
            // just in case posting your form failed
            //alert( "Por favor intentelo más tarde." );             
        });
 
        // to prevent refreshing the whole page page
        return false;
 
    });
*/
       /* Guardar datos*/
     /*
        $('#form-suscriptor').submit(function(){    
                
        
        $.ajax({
            type: 'POST',
            url: '../../../src/adm_suscriptor.php',
            data: $(this).serialize()
        })
        .done(function(data){
             
            // show the response
            //$('#respuesta').html(data);
            //alert( "Enviado" );
            console.log(data);
            var datos = JSON.parse(data);
            //alert(datos.valido);
            if (datos.valido == 1){              
              
              $('#myModal').modal('hide');             
              $('#ModalGuardar').modal('show');
            }

             
        })
        .fail(function() {
         
            // just in case posting your form failed
            //alert( "Por favor intentelo más tarde." );
             
        });
 
        // to prevent refreshing the whole page page
        return false;
 
    });*/

     /* Eliminar datos*/
     /*
        $('#form-suscriptor-eliminar').submit(function(){    
                
       $.ajax({
            type: 'POST',
            url: '../../../src/adm_suscriptor.php?a=eliminar',
            data: $(this).serialize()
        })
        .done(function(data){
             
            // show the response
            //$('#respuesta').html(data);
            //alert( "Enviado" );
            //console.log(data);
            var datos = JSON.parse(data);  

            if (datos.valido == 1){              
              $('#ModalEliminar').modal('hide');
              console.log(datos.codigo);
              $("#"+datos.codigo).css("background-color", "#FA5858");
              $("#"+datos.codigo).hide( "slow" );
              // window.location.href = "lugares.php";
              //crearTablaLugares();
            }

             
        })
        .fail(function() {
         
            // just in case posting your form failed
            //alert( "Por favor intentelo más tarde." );
             
        });
 
        // to prevent refreshing the whole page page
        return false;
 
    });
    */

     /*$('#ModalEliminar').on('show.bs.modal', function(e) {
        var  id_lugar = $(e.relatedTarget).data('suscriptor-id');        
        //alert(id_lugar);
        $(e.currentTarget).find('input[name="h-id-suscriptor"]').val(id_lugar);
     });*/

     
     /* Editar suscriptor*/
     /*
        $('#myModalEditar').on('show.bs.modal', function(e) {
       
       var  id_suscriptor = $(e.relatedTarget).data('suscriptor-id');  

        $.ajax({    
      url: "../../../src/adm_suscriptor.php?a=buscar&id_suscriptor="+id_suscriptor,      
       })
     .done(function( data, textStatus, jqXHR ) {
      if(data!="VACIO"){        
        console.log(data);
        var suscriptor = JSON.parse(data);   

        $("#tx_nombre_apellido_e").val(suscriptor[0].tx_nombre_apellido);  
        $("#tx_correo_electronico_e").val(suscriptor[0].tx_correo_electronico);  
        $("#tx_preferencia_e").val(suscriptor[0].tx_preferencia);  
        $("#tx_preferencia_hora_e").val(suscriptor[0].tx_preferencia_hora);  
        $("#tx_telefono_e").val(suscriptor[0].tx_telefono);  
        $("#activo_e").val(suscriptor[0].activo);
        $("#h-id-suscriptor_e").val(id_suscriptor);
        
        
      }else{
        //No existen registros
        alert("no existe registros");
      }
      
     })

     .fail(function( jqXHR, textStatus, errorThrown ) {
        alert("Falla de conexion:" + textStatus);

    });
      
     }
                );
    */


      /* Ver suscriptor*/
    /*
        $('#myModalVer').on('show.bs.modal', function(e) {
       
       var  ambito = $(e.relatedTarget).data('suscriptor-id');  

        $.ajax({    
      url: "../../../src/adm_suscriptor.php?a=buscar&ambito="+ambito,      
       })
     .done(function( data, textStatus, jqXHR ) {
      if(data!="VACIO"){        
        console.log(data);
        var suscriptor = JSON.parse(data);   

        $("#tx_nombre_apellido_v").val(suscriptor[0].tx_nombre_apellido);  
        $("#tx_correo_electronico_v").val(suscriptor[0].tx_correo_electronico);  
        $("#tx_preferencia_v").val(suscriptor[0].tx_preferencia);  
        $("#tx_preferencia_hora_v").val(suscriptor[0].tx_preferencia_hora);  
        $("#tx_telefono_v").val(suscriptor[0].tx_telefono);  
        $("#activo_v").val(suscriptor[0].activo);
        
      }else{
        //No existen registros
        alert("no existe registros");
      }
      
     })

     .fail(function( jqXHR, textStatus, errorThrown ) {
        alert("Falla de conexion:" + textStatus);

    });
      
     });

    */
  });// fin ready


  

  function crearTablaSuscriptores(tx_ambito){

   
   
    $('#listado').hide();
    //console.log($('#tx_preferencia').val());
   console.log("crearTablaSuscriptores");
   console.log(tx_ambito);
    

    $.ajax({    
      url: "../../../src/adm_monitor.php?a=buscar&ambito="+tx_ambito,      
      //url: "../../../src/adm_monitor.php?a=listar",      
    })
     .done(function( data, textStatus, jqXHR ) {
      if(data!="VACIO"){
        var tabla = '';
        console.log(data);
        var aLista = JSON.parse(data);
        tabla+="<table id='lista' class='table table-bordered table-striped table-hover'>";
        tabla+="<thead><tr>";
        tabla+="<th>Incidente</th>";
        tabla+="<th>Fecha</th>";
        tabla+="<th>Clase de incidente</th>";
        tabla+="<th>Ámbito</th>";
        tabla+="<th>Lugar</th>";
        tabla+="<th>Km aproximado</th>";
        tabla+="<th>Cuenta de usuario</th>";
        tabla+="<th>Nombre de usuario</th>";   
        tabla+="</tr></thead>";
        tabla+="<tbody>";
        
        for(i = 0; i < aLista.length; i++) {
            
            tabla+="<tr class='' id=''>";
            tabla+="<td>"+aLista[i].text+"</td>";
            tabla+="<td>"+aLista[i].date+"</td>";
            tabla+="<td>"+aLista[i].clase_incidente+"</td>";
            tabla+="<td>"+aLista[i].tx_ambito+"</td>";
            tabla+="<td>"+aLista[i].lugar+"</td>";
            tabla+="<td>"+aLista[i].km_aprox+"</td>";
            tabla+="<td>"+aLista[i].name+"</td>";  
            tabla+="<td>"+aLista[i].screen_name+"</td>";              
            tabla+="</tr>";

            //console.log(aLista[i].tx_correo_electronico);
        }

        tabla+="</tbody>";
        tabla+="</table>";
        
       $('#listado').html(tabla);
       $('#listado').show();
       $('#lista').DataTable({
       
        "language": {
            "destroy": true,
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        }
      });
       $( "#cargando" ).hide();
        
      }else{
        //No existen registros
        alert("no existe registros");
      }
      
     })

     .fail(function( jqXHR, textStatus, errorThrown ) {
        alert("Falla de conexion:" + textStatus);

    });

  
  }


  /*$(function () {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });*/
</script>
</body>
</html>
