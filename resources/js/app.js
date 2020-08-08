/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

$(document).on('click', '.phone-button', function () {
    var button = $(this);
    axios.post(button.data('source')).then(function (response) {
        alert('ok');
        alert(response.data);
        button.find('.number').html(response.data)
    }).catch(function (error) {
        alert('error');
        alert(response.data);
        // console.error(error);
    });
});



//начальное заполнение
// let root = $('.region-select');
// let root = [];
// if (root.length > 0) {
//     const block = $('.region-selector');
//     const url = block.data('source');
//
//     //получим данные
//     axios.get(url, {params: {parent: null}})
//         .then(function (response) {
//             response.data.forEach(function (region) {
//                 root.append(
//                     $("<option>")
//                         .attr('name', 'regions[]')
//                         .attr('value', region.id)
//                         .text(region.name)
//                 );
//             });
//         });
//     //повесим событие
//     root.change(function (e) {
//         changeRegionSelect(e.target, root.val(), url, block);
//     });
// }
//
// function deleteChildSelects(parentLevel) {
//     $('.region-select').each(function(){
//         if($(this).attr('data-level') > parentLevel){
//             $(this).remove();
//         }
//     });
// }
//
// function changeRegionSelect(parent, parentValue, url, block) {
//
//     let parentLevel = parseInt($(parent).attr('data-level'));
//
//     //удаляем все селекты уровнем больше parentValue
//     deleteChildSelects(parentLevel);
//
//     //был выбран пустой пункт
//     if($(parent).val() === '')
//         return;
//
//     //делаем запрос
//     axios.get(url, {params: {parent: parentValue}})
//         .then(function (response) {
//             // console.log(response.data);
//             //если пришли данные, то добавляем ещё один селект
//             if (response.data.length > 0) {
//
//                 //добавим элементы
//                 let group = $('<div class="form-group">');
//                 let select = $(`<select class="form-control region-select" data-level='${ parentLevel + 1 }'>`);
//                 select.append($('<option value=""></option>'));
//                 group.append(select);
//                 block.append(group);
//
//                 //добавление option в select
//                 response.data.forEach(function (region) {
//                     select.append(
//                         $("<option>")
//                             .attr('name', 'regions[]')
//                             .attr('value', region.id)
//                             .text(region.name)
//                     );
//                 });
//
//                 //повесим событие
//                 select.change(function (e) {
//                     changeRegionSelect(e.target, select.val(), url, block);
//                 });
//             }
//         });
// }
