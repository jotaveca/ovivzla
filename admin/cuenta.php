         <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="http://ovi.org.ve/admin/dist/img/<?php echo $foto_usuario ?>-160x160.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $nb_usuario ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="http://ovi.org.ve/admin/dist/img/<?php echo $foto_usuario?>-160x160.jpg" class="img-circle" alt="User Image">
                <p>
                  <?php echo $nb_usuario ." - ".$perfil_usuario?>
                  <small><?php echo $usuario_fecha ?></small>
                </p>
              </li>
              <!-- Menu Body -->
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Seguidores</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Indicadores</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Contactos</a>
                  </div>
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Perfil</a>
                </div>
                <div class="pull-right">
                  <a href="http://ovi.org.ve/admin/index.html" class="btn btn-default btn-flat">Cerrar Sesión</a>
                </div>
              </li>
            </ul>
          </li>