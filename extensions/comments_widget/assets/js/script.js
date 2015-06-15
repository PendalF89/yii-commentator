/**
 * @see http://stackoverflow.com/questions/190560/jquery-animate-backgroundcolor
 */
(function (d) {
    d.each(["backgroundColor", "borderBottomColor", "borderLeftColor", "borderRightColor", "borderTopColor", "color", "outlineColor"], function (f, e) {
        d.fx.step[e] = function (g) {
            if (!g.colorInit) {
                g.start = c(g.elem, e);
                g.end = b(g.end);
                g.colorInit = true
            }
            g.elem.style[e] = "rgb(" + [Math.max(Math.min(parseInt((g.pos * (g.end[0] - g.start[0])) + g.start[0]), 255), 0), Math.max(Math.min(parseInt((g.pos * (g.end[1] - g.start[1])) + g.start[1]), 255), 0), Math.max(Math.min(parseInt((g.pos * (g.end[2] - g.start[2])) + g.start[2]), 255), 0)].join(",") + ")"
        }
    });

    function b(f) {
        var e;
        if (f && f.constructor == Array && f.length == 3) {
            return f
        }
        if (e = /rgb\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*\)/.exec(f)) {
            return [parseInt(e[1]), parseInt(e[2]), parseInt(e[3])]
        }
        if (e = /rgb\(\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*\)/.exec(f)) {
            return [parseFloat(e[1]) * 2.55, parseFloat(e[2]) * 2.55, parseFloat(e[3]) * 2.55]
        }
        if (e = /#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})/.exec(f)) {
            return [parseInt(e[1], 16), parseInt(e[2], 16), parseInt(e[3], 16)]
        }
        if (e = /#([a-fA-F0-9])([a-fA-F0-9])([a-fA-F0-9])/.exec(f)) {
            return [parseInt(e[1] + e[1], 16), parseInt(e[2] + e[2], 16), parseInt(e[3] + e[3], 16)]
        }
        if (e = /rgba\(0, 0, 0, 0\)/.exec(f)) {
            return a.transparent
        }
        return a[d.trim(f).toLowerCase()]
    }
    function c(g, e) {
        var f;
        do {
            f = d.css(g, e);
            if (f != "" && f != "transparent" || d.nodeName(g, "body")) {
                break
            }
            e = "backgroundColor"
        } while (g = g.parentNode);
        return b(f)
    }
    var a = {
        aqua: [0, 255, 255],
        azure: [240, 255, 255],
        beige: [245, 245, 220],
        black: [0, 0, 0],
        blue: [0, 0, 255],
        brown: [165, 42, 42],
        cyan: [0, 255, 255],
        darkblue: [0, 0, 139],
        darkcyan: [0, 139, 139],
        darkgrey: [169, 169, 169],
        darkgreen: [0, 100, 0],
        darkkhaki: [189, 183, 107],
        darkmagenta: [139, 0, 139],
        darkolivegreen: [85, 107, 47],
        darkorange: [255, 140, 0],
        darkorchid: [153, 50, 204],
        darkred: [139, 0, 0],
        darksalmon: [233, 150, 122],
        darkviolet: [148, 0, 211],
        fuchsia: [255, 0, 255],
        gold: [255, 215, 0],
        green: [0, 128, 0],
        indigo: [75, 0, 130],
        khaki: [240, 230, 140],
        lightblue: [173, 216, 230],
        lightcyan: [224, 255, 255],
        lightgreen: [144, 238, 144],
        lightgrey: [211, 211, 211],
        lightpink: [255, 182, 193],
        lightyellow: [255, 255, 224],
        lime: [0, 255, 0],
        magenta: [255, 0, 255],
        maroon: [128, 0, 0],
        navy: [0, 0, 128],
        olive: [128, 128, 0],
        orange: [255, 165, 0],
        pink: [255, 192, 203],
        purple: [128, 0, 128],
        violet: [128, 0, 128],
        red: [255, 0, 0],
        silver: [192, 192, 192],
        white: [255, 255, 255],
        yellow: [255, 255, 0],
        transparent: [255, 255, 255]
    }
})(jQuery);


$(document).ready( function(){

    var colorSuccess = "#baefba",
        colorEdit = "#ED9C28";

    /**
     * Анимирует комментарий
     * @param color цвет
     * @param id id комментария
     */
    function animateComment(id, color){
        var originalBG =  $('.comment[data-id="' + id + '"]').css("backgroundColor");
        $('.comment[data-id="' + id + '"]')
            .animate({"backgroundColor": color}, 500)
            .animate({"backgroundColor": originalBG}, 800);
    }

    /**
     * Загружает форму (форма ответа или редактирования)
     * @param elem элемент, по которму кликнули
     * @param url урл, с которого получаем форму
     */
    function loadForm(elem, url){
        var id = elem.data('id'),
            form = elem.parents('.comment').find('[data-role="dynamic-form-container"]'),
            btnGroup = elem.parents('.comment').find('.btn-group');

        $.ajax({
            "url": url,
            "type": "post",
            "dataType": "html",
            "data": "id=" + id + "&url=" + pageUrl,
            "success": function(data) {
                form.html(data);
                btnGroup.hide();
            }
        });
    }

    /**
     * Создаёт комментарий по аяксу
     * @param form
     */
    function ajaxCreate(form)
    {
        $.ajax({
            "url": commentsCreateUrl,
            "type": "post",
            "dataType": "json",
            "data": form.serialize(),
            "success": function(data) {
                // Сбрасываем форму
                form[0].reset();
                // Перерисовываем дерево
                $('[data-role="tree"]').html(data.tree);

                // Если включена премодераця, то показываем сообщение и выходим
                if (data.premoderate){
                    $('[data-role="modal-container"]').html(data.modal);
                    $('[data-role="launch-modal"]').click();
                    return;
                }

                // Скроллимся к добавленному комментарию
                scrollToElem($('[name="comment_' + data.id + '"]'), 1000);
                // Анимируем добавленный комментарий
                animateComment(data.id, colorSuccess);
                // Обновляем счётчик комментариев
                $('[data-role="count"]').html(data.count);
            },
            "beforeSend": function() {
                $(".comments button").attr("disabled", true);
            },
            "complete": function() {
                // Включаем кнопки
                $(".comments button").attr("disabled", false);
            }
        });
    }

    /**
     * Обновляет комментарий по аяксу
     * @param form
     */
    function ajaxUpdate(form)
    {
        $.ajax({
            "url": commentsUpdateUrl,
            "type": "post",
            "dataType": "json",
            "data": form.serialize(),
            "success": function(data) {
                // Перерисовываем дерево
                $('[data-role="tree"]').html(data.tree);
                // Скроллимся к добавленному комментарию
                scrollToElem($('[name="comment_' + data.id + '"]'), 1000);
                // Анимируем добавленный комментарий
                animateComment(data.id, colorEdit);
            },
            "beforeSend": function() {
                $(".comments button").attr("disabled", true);
            },
            "complete": function() {
                // Включаем кнопки
                $(".comments button").attr("disabled", false);
            }
        });
    }

    // Обрабатываем клик по кнопке "ответ"
    $(".comments").on("click", '[href="#comment_reply"]', function(){
        loadForm($(this), commentsReplyFormUrl);
    });

    // Обрабатываем клик по кнопке "редактировать"
    $(".comments").on("click", '[href="#comment_edit"]', function(){
        loadForm($(this), commentsUpdateFormUrl);
    });

    // Обрабатываем клик по кнопке "отмена"
    $(".comments").on("click", '[data-role="cancel"]', function(e){
        e.preventDefault();
        $(this).parents('.comment').find('.btn-group').show();
        $(this).parents('.comment').find('[data-role="dynamic-form-container"]').empty();
    });

    // Обрабатываем отправку форму и вызываем либо обновление, либо создание нового комментария
    $(".comments").on("submit", "form", function(e){
        e.preventDefault();

        if ( $(this).find('[data-is-new]').data('is-new') )
            ajaxCreate( $(this) );
        else
            ajaxUpdate( $(this) );
    });

    // Обрабатываем удаление комментария
    $(".comments").on("click", '[href="#comment_delete"]', function(e){
        e.preventDefault();

        $.ajax({
            "url": commentsDeleteUrl,
            "type": "post",
            "dataType": "json",
            "data": "id=" + $(this).data('id'),
            "success": function(data) {
                // Перерисовываем дерево
                $('[data-role="tree"]').html(data.tree);
                // Обновляем счётчик комментариев
                $('[data-role="count"]').html(data.count);
                // Обновляем контейнер с сообщением и показываем сообщение
                $('[data-role="modal-container"]').html(data.modal);
                $('[data-role="launch-modal"]').click();
            },
            "beforeSend": function() {
                $(".comments button").attr("disabled", true);
            },
            "complete": function() {
                // Включаем кнопки
                $(".comments button").attr("disabled", false);
            }
        });
    });

    // Обрабатываем клик по лайку
    $(".comments").on("click", '[href="#comment_like"]', function(e){
        e.preventDefault();
        var likes = $(this).parents(".comment").find('[data-role="likes"]'),
            like = $(this).data("like"),
            id = $(this).parents(".comment").data("id");

        $.ajax({
            "url": commentsLikesUrl,
            "type": "post",
            "dataType": "json",
            "data": "id=" + id + "&like=" + like,
            "success": function(data) {
                // Обновляем значение лайков
                if (data)
                    likes.text(data.likes);
            }
        });
    });

    /**
     * Сролл к элементу
     *
     * @param elem
     * @param speed
     */
    function scrollToElem(elem, speed) {
        $("html, body").animate({
            scrollTop: elem.offset().top
        }, speed);
    }
});