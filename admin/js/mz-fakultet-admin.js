(function($) {

    $(document).ready(function() {

        var studenti = $(".student strong");
        var odabrani_student = $("#student_select");

        odabrani_student.on('change', function() {
            studenti.each(function (index, el) {
                $(el).parent().parent().show();
                if(this.innerText != odabrani_student.find(':selected').text() && odabrani_student.find(':selected').text() != 'Izaberi Studenta') {
                    $(el).parent().parent().hide();
                }
            });
        });

        var locat = location.search.substr(1).split("&");

        if( location.hash === '#success=true' )
              location.href = mz_fakultet.base_url + '/wp-admin/admin.php?' + locat[0];
        
    });
})(jQuery);


