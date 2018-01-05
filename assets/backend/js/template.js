$(document).ready(function () {
    var Template = {
        init: function () {
            this.Formvalidation();
        },
        Formvalidation: function () {
            $("#form_save_template").validationEngine();
            $("#form_import_template").validationEngine();
        }
    };
    Template.init();
}); 