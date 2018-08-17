<div class="container">

    <form id="formPerson" class="form-horizontal">
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

    <table>
        <thead>
            <tr>
                <th>
                    Código
                </th>
                <th>
                    Nombre
                </th>
                <th>
                    Apellido
                </th>
                <th>
                    Estado
                </th>
                <th>
                    Operaciones
                </th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($allPersons as $person) { ?>

                <tr>
                    <td> <?php echo $person->idPerson; ?> </td>
                    <td> <?php echo $person->name; ?> </td>
                    <td> <?php echo $person->surName; ?> </td>
                    <td> <?php echo $person->status; ?> </td>
                    <td>
                        <button id="btnEditar" type="button">Editar</button>
                        <button id="btnEliminar" type="button">Eliminar</button>
                    </td>
                </tr>

            <?php } ?>

        </tbody>
    </table>


    <select name="ddlPerson" id="ddlPerson">
        <?php foreach ($dropDownList as $person) { ?>
            <option value="<?php echo $person->idPerson; ?>">
                <?php echo $person->name . " " . $person->surName; ?>
            </option>
        <?php } ?>
    </select>


    <?php if(isset($message)) { ?>
        <?php echo $message; ?>
    <?php } ?>


</div>