$(document).ready(function () {
    var rArray = JSON.parse($("#rte").text());  
    $("table#ready_foods tbody tr").each(function () {
        f = $(this).data('foodid');
        if (rArray.indexOf(f) === -1) {
            $(this).toggle();
        } else {
            $("table#meal_pantry tbody tr[data-foodid=" + f + "]").toggle();
        }
    });
    $("#ready_foods").css("visibility", "visible");
    $("#meal_pantry").css("visibility", "visible");

    $('td').on('click', function (e) {
        var foodId = $(e.currentTarget).data('foodid');
        var tableId = $(e.currentTarget).parents('table').attr('id');
        var mealId = $("#mealid").data("mealid");
//        $packet = '?foodId='+foodId+'&mealId='+mealId+'&tableId='+tableId;
        $packet = JSON.stringify([foodId, mealId, tableId]);
        $.post(document.location.origin + '/meal/' + mealId + '/editMealFood', $packet, function (response) {
            if (response) {
                
            }
            $("tr[data-foodid=" + foodId + "]").toggle();

            if (tableId === "ready_foods") {
                mealPantryScroll(foodId);
            }

            function mealPantryScroll(foodId) {
                over = $(".overflow-auto").height();
                mpY = $("#meal_pantry").height();
                rowY = $("#meal_pantry tbody tr").height();
                target = $("#meal_pantry tbody tr[data-foodid="+foodId+"]")
                tgtIndx = target.parent().index('#meal_pantry  tbody tr');
                mpl = $("#meal_pantry tbody tr").length;
                loc = (mpY-over)*(tgtIndx/mpl);
            
                $(".overflow-auto").scrollTop(loc+rowY);
            
                return false;
            }    
        });
    });
});

