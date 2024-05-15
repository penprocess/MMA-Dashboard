$(document).ready(function(){
    // Handle change event on module dropdown
    $('#moduleDropdown').change(function(){
        var selectedModule = $(this).val();

        // AJAX request to get labels for the selected module
        $.ajax({
            type: 'POST',
            url: 'get_labels.php',
            data: {module: selectedModule},
            success: function(response){
                // Populate labels dropdown with the received options
                $('#labelDropdown').html(response);

                // Clear rules dropdown
                
            }
        });
    });

    // Handle change event on label dropdown
    $('#labelDropdown').change(function(){
        var selectedLabel = $(this).val();

        // AJAX request to get rules for the selected label
        $.ajax({
            type: 'POST',
            url: 'get_rules.php',
            data: {label: selectedLabel},
            success: function(response){
                // Populate rules dropdown with the received options
                $('#ruleDropdown').html(response);
            }
        });
    });

    $('#ruleDropdown').multiselect({
        enableFiltering: true,
        includeSelectAllOption: true
    });
});
