/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');


//начальное заполнение
let root = $('.region-select');
if (root.length > 0) {
    const block = $('.region-selector');
    const url = block.data('source');

    //получим данные
    axios.get(url, {params: {parent: null}})
        .then(function (response) {
            response.data.forEach(function (region) {
                root.append(
                    $("<option>")
                        .attr('name', 'regions[]')
                        .attr('value', region.id)
                        .text(region.name)
                );
            });
        });
    //повесим событие
    root.change(function () {
        changeRegionSelect(1, root.val(), url, block);
    });
}

function deleteChildSelects(parentLevel) {
    $('.region-select').each(function(){
        if($(this).attr('data-level') > parentLevel){
            $(this).remove();
        }
    });
}

function changeRegionSelect(parentLevel, parentValue, url, block) {

    //удаляем все селекты уровнем больше parentValue
    deleteChildSelects(parentLevel);

    //делаем запрос
    axios.get(url, {params: {parent: parentValue}})
        .then(function (response) {

            //если пришли данные, то добавляем ещё один селект
            if (response.data.length > 0) {

                //добавим элементы
                const currentLevel = parentLevel + 1;
                let group = $('<div class="form-group">');
                let select = $(`<select class="form-control region-select" data-level='${currentLevel}'>`);
                select.append($('<option value=""></option>'));
                group.append(select);
                block.append(group);

                //добавление option в select
                response.data.forEach(function (region) {
                    select.append(
                        $("<option>")
                            .attr('name', 'regions[]')
                            .attr('value', region.id)
                            .text(region.name)
                    );
                });

                //повесим событие
                select.change(function () {
                    changeRegionSelect(currentLevel, select.val(), url, block);
                });
            }
        });
}
