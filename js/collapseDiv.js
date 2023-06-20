// collapse for lecture adjustment
        $(document).ready(function() {
            $('.collapse-btn').click(function() {
                $('.collapse-containt').slideToggle();
            });
            $('.collapse-btn-previous').click(function() {
                $('.collapse-containt-privious').slideToggle();
            });
        });

// collapse for task adjustment
        $(document).ready(function() {
            $('.collapse-btn-adjustment').click(function() {
                $('.collapse-containt-adjustment').slideToggle();
            });
            $('.collapse-btn-previous-adjustment').click(function() {
                $('.collapse-containt-privious-adjustment').slideToggle();
            });
        });

// collapse for leave type that you have taken
        $(document).ready(function() {
            $('.collapse-btn-leaveType').click(function() {
                $('.collapse-containt-leaveType').slideToggle();
            });
        });

// collapse for leave type and balance 
        $(document).ready(function() {
            $('.collapse-btn-leaveTypeandBalance').click(function() {
                $('.collapse-containt-leaveTypeandBalance').slideToggle();
            });
            $('.collapse-containt-leaveTypeandBalance').click(function() {
                $('.collapse-containt-privious-adjustment').slideToggle();
            });
        });