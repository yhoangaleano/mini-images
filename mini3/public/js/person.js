$(document).ready(function () {
    person.init();
});

var person = {

    init: function () {

        $("#formPerson").on("submit", function (e) {
            e.preventDefault();

            var formIsValid = person.valid();

            if(formIsValid.valid == false){
                alert(formIsValid.message);
            }else{
                person.save();
            }
            
        });

        $("#txtPhoto").change(function(){
            validImage(this);
        });

        $("#txtMultiple").change(function(){
            validImage(this);
        });

    },

    save: function () {

        var fd = getDataFromForm("formPerson");

        // Envia una petición ajax a la siguiente URL: localhost/person/addPerson
        // "url" esta definida en views/_templates/footer.php
        $.ajax({
            url: url + "/person/ajaxImages",
            method: "POST",
            data: fd,
            contentType: false,
            processData: false,
            dataType: "json"
        })
            .done(function (result) {
                console.log(result);
                alert("Datos guardados correctamente", result);
            })
            .fail(function (error) {
                console.log("Oppss!! Ocurrio un error", error);
            });

    },

    edit: function () {

    },

    update: function () {

    },

    changeStatus: function () {

    },

    list: function () {

    },

    valid: function(){

        var formIsValid = {
            valid: true,
            message: ""
        };

        var fieldValid = $('#txtName').val();
        if (fieldValid == '') {
            formIsValid.valid = false;
            formIsValid.message += "El nombre es requerido";
            formIsValid.message += "</br>";
        }

        var fieldValid = $('#txtSurName').val();
        if (fieldValid == '') {
            formIsValid.valid = false;
            formIsValid.message += "El apellido es requerido";
            formIsValid.message += "</br>";
        }

        var fieldValid = $('#txtPhoto').val();
        if (fieldValid == '') {
            formIsValid.valid = false;
            formIsValid.message += "La foto es requerida";
            formIsValid.message += "</br>";
        }
        
        var fieldValid = $('#txtMultiple').val();
        if (fieldValid == '') {
            formIsValid.valid = false;
            formIsValid.message += "Las fotos multiples son requeridas";
            formIsValid.message += "</br>";
        }

        return formIsValid;

    }

}