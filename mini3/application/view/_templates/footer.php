
    <div class="footer">
        Find <a href="https://github.com/panique/mini3">MINI3 on GitHub</a>.
        If you like the project, support it by <a href="http://tracking.rackspace.com/SH1ES">using Rackspace</a> as your hoster [affiliate link].
    </div>

    <!-- jQuery, loaded in the recommended protocol-less way -->
    <!-- more http://www.paulirish.com/2010/the-protocol-relative-url/ -->
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

    <!-- define the project's URL (to make AJAX calls possible, even when using this in sub-folders etc) -->
    <script>
        var url = "<?php echo URL; ?>";
        
        function getDataFromForm(formName) {

            var fd = new FormData();
            var files = $('#' + formName).find('input[type="file"]');
            
            if(files.length > 0){

                var size_fields = files.length;

                for (let j = 0; j < size_fields; j++) {

                    var file_data = files[j].files;
                    var size_files = file_data.length;
                    var fieldName = $(files[j]).attr("id");
                    fieldName += size_files > 1 ? "[]" : "";

                    for (var i = 0; i < size_files; i++) {
                        fd.append(fieldName, file_data[i]);
                    }
                }
            }

            var other_data = $('#' + formName).serializeArray();
            $.each(other_data, function (key, input) {
                fd.append(input.name, input.value);
            });

            return fd;
        }

        function validImage(field) {

            var self = field;

            var size = self.files.length;
            for (let index = 0; index < size; index++) {
                var file = self.files[index];
                var imagefile = file.type;
                var match = ["image/gif", "image/jpeg", "image/png", "image/jpg"];
                if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]))) {
                    alert('Por favor selecciona una imagen valida (JPEG/JPG/PNG/GIF).');
                    $(self).val("");
                    return false;
                }
            };        
        }

    </script>

    <!-- our JavaScript -->
    <script src="<?php echo URL; ?>js/application.js"></script>

    <script src="<?php echo URL; ?>js/person.js"></script>

    <script src="<?php echo URL; ?>js/messages.js"></script>

    <?php if( isset($message) ) { ?>

        <script>
            $(document).ready(function() {
                <?php echo $message; ?>();
            });
        </script>

    <?php } ?>
</body>
</html>
