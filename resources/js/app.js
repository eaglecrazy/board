/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

$(document).on('click', '#second-search-button', function () {
    $('#second-search-button').trigger('click');
});


//скрипт открытия телефона в странице просмотра объявления
//нужно предусмотреть защиту от парсинга телефонных номеров
//например при помощи csrf токена
$(document).on('click', '.phone-button', function () {
    let button = $(this);
    let url = button.data('source');
    axios.post(url).then(function (response) {
        button.find('.number').html(response.data)
    }).catch(function (error) {
        console.error(error);
    });
});

$('.bitem').each(function () {
    let block = $(this);
    let url = block.data('url');
    let format = block.data('format');
    let category = block.data('category');
    let region = block.data('region');

    axios
        .get(url, {
            params: {
                format: format,
                category: category,
                region: region
            }
        })
        .then(function (response) {
            block.html(response.data);
        })
        .catch(function (error) {
            console.error(error);
        });
});


//
$(document).on('click', '.location-button', function () {
    var button = $(this);
    var target = $(button.data('target'));

    window.geocode_callback = function (response) {
        if (response.response.GeoObjectCollection.metaDataProperty.GeocoderResponseMetaData.found > 0) {
            target.val(response.response.GeoObjectCollection.featureMember['0'].GeoObject.metaDataProperty.GeocoderMetaData.Address.formatted);
        } else {
            alert('Unable to detect your address.');
        }
    };

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var location = position.coords.longitude + ',' + position.coords.latitude;
            var url = 'https://geocode-maps.yandex.ru/1.x/?format=json&callback=geocode_callback&geocode=' + location + '&apikey=418aa256-e41b-4893-aa9f-5530867df1a5';
            var script = $('<script>').appendTo($('body'));
            script.attr('src', url);
        }, function (error) {
            console.warn(error.message);
        });
    } else {
        alert('Unable to detect your location.');
    }
});


$(document).ready(function () {
    $('.summernote').summernote({
        height: 300,
        callbacks: {
            onImageUpload: function (files) {
                var editor = $(this);
                var url = editor.data('image-url');
                var data = new FormData();
                data.append('file', files[0]);
                axios
                    .post(url, data).then(function (response) {
                    editor.summernote('insertImage', response.data);
                })
                .catch(function (error) {
                    console.error(error);
                });
            }
        }
    });
});


$(document).on('click', '.second-photo', function ($e) {

    let photo = $(this);
    let src = photo.attr('src');
    let main = $('.main-photo');
    main.attr('src', src);
});

require('./maskedinput.min');
$(function(){
    $("#phone").mask("+7(999)999-99-99");
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
