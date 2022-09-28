$('td').on('click', function (e) {
    foodId = $(e.currentTarget).data('foodid');
    mealId = $("#mealid").data("mealid");
    var pageNumber = $('li.active').first().text().trim();
    var pageLimit = $("#mealid").data("pagelimit");
    $packet = JSON.stringify([foodId, mealId]);
    $.post('http://diet/meal/' + mealId + '/addFoodToMeal', $packet, function (response) {
        editFoods = $.parseJSON(response);
        var readyToEat = $.parseJSON(editFoods[0]);
        var pantry = $.parseJSON(editFoods[1]);
        var table = document.getElementById('ready_foods')
        $('#ready_foods tr:not(:first)').remove();
        $.each(readyToEat, function (key, food) {
            var row = table.insertRow(-1);
            var cell = row.insertCell(0);
            cell.innerHTML = food;
        })

        var table = document.getElementById('pantry')
        $('#pantry tr:not(:first)').remove();
        $('li.active').removeClass('active');
        $('li.page-item:nth-of-type(2)').addClass('active');
        $.each(pantry.slice(0, pageLimit), function (key, array) {
            food = array.split(",");
            foodId = food[0];
            foodName = food[1];
            var row = table.insertRow(-1);
            var cell = row.insertCell(0);
            cell.innerHTML = foodName;
            cell.setAttribute('data-foodid', foodId);
        })
    });
});