<div class="container">

    <form class="form-horizontal" method="POST" action="<?php echo URL; ?>person/saveImages" enctype="multipart/form-data">
        <fieldset>

            <!-- Form Name -->
            <legend>Información de Persona</legend>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="txtName">Nombre</label>
                <div class="col-md-4">
                    <input id="txtName" name="txtName" type="text" placeholder="Ejm: Yhoan" class="form-control input-md" required="">

                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="txtSurName">Apellidos</label>
                <div class="col-md-4">
                    <input id="txtSurName" name="txtSurName" type="text" placeholder="Ejm: Galeano" class="form-control input-md" required="">

                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label" for="txtPhoto">Foto</label>
                <div class="col-md-4">
                    <input id="txtPhoto" name="txtPhoto" type="file" class="form-control input-md" required="">

                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label" for="txtMultiple">Multiple Fotos</label>
                <div class="col-md-4">
                    <input id="txtMultiple" name="txtMultiple[]" type="file" multiple class="form-control input-md" required="">

                </div>
            </div>

            <!-- Button -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="btnSave"></label>
                <div class="col-md-4">
                    <button id="btnSave" name="btnSave" class="btn btn-success">Guardar</button>
                </div>
            </div>

        </fieldset>
    </form>

</div>