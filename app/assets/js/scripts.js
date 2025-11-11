tinymce.init({
    selector: 'textarea.tinymce',
    min_height: 350,
    plugins: "table , link , lists , hr , paste , print , textcolor , code , emoticons , searchreplace , spellchecker , directionality",
    toolbar: "undo redo | styleselect | searchreplace spellchecker | bold italic | emoticons hr | link unlink | forecolor backcolor | alignleft aligncenter alignright alignjustify | ltr rtl | bullist numlist outdent indent | table | fontsizeselect"
});
$(document).ready(function(){
    $(".chosen-select").chosen({width: "100%"});
});
