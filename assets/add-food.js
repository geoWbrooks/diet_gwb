$('td').on('click', function (e) {
    foodId = $(e.currentTarget).data('foodid');
    mealId = $("#mealid").data("mealid");
    $packet = JSON.stringify([foodId, mealId]);
    $.post('http://diet/meal/' + mealId + '/addFoodToMeal', $packet, function (response) {
        editFoods = $.parseJSON(response);
        var readyToEat = $.parseJSON(editFoods[0]);
        var pantry = $.parseJSON(editFoods[1]);
        var table = document.getElementById('ready_foods')
        $('#ready_foods tr:not(:first)').remove();
        $.each(readyToEat, function(key, food) {
            var row = table.insertRow(-1);
            var cell = row.insertCell(0);
            cell.innerHTML = food;
        })
    });

});